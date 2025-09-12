<?php
namespace App\middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as SlimResponse;

class AuthMiddleware {
    public function __invoke(Request $request, $handler): Response {
        $authHeader = $request->getHeaderLine("Authorization");

        if (!$authHeader || !str_starts_with($authHeader, "Bearer ")) {
            return $this->unauthorized("No token provided", 403);
        }

        $token = substr($authHeader, 7);

        try {
            // Obtener JWKS de Keycloak
            $jwksJson = @file_get_contents($_ENV['JWKS_URL']);
            if ($jwksJson === false) {
                return $this->unauthorized("Cannot fetch JWKS from Keycloak");
            }

            $jwks = json_decode($jwksJson, true);
            if (!$jwks || !isset($jwks['keys'])) {
                return $this->unauthorized("Invalid JWKS format");
            }

            // Decodificar el token con las claves públicas de Keycloak
            $decoded = JWT::decode($token, JWK::parseKeySet($jwks));

            // Validar claims críticos
            if (!isset($decoded->iss) || $decoded->iss !== $_ENV['KC_ISSUER']) {
                return $this->unauthorized("Invalid issuer");
            }

            if (!isset($decoded->aud) || $decoded->aud !== $_ENV['KC_CLIENT_ID']) {
                return $this->unauthorized("Invalid audience");
            }

            // Guardar usuario en la request
            $request = $request->withAttribute("user", $decoded);
            $request = $request->withAttribute("user_id", $decoded->sub);
            return $handler->handle($request);

        } catch (\Throwable $e) {
            return $this->unauthorized("Invalid token", 401, $e->getMessage());
        }
    }

    private function unauthorized(string $message, int $status = 401, string $debug = null): Response {
        $response = new SlimResponse();
        $payload = ["error" => $message];
        if ($debug && ($_ENV['APP_ENV'] ?? 'prod') === 'dev') {
            $payload['debug'] = $debug;
        }
        $response->getBody()->write(json_encode($payload));
        return $response->withStatus($status)->withHeader("Content-Type", "application/json");
    }
}
