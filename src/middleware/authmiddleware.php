<?php

use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as SlimResponse;

// Función global para respuestas no autorizadas
function unauthorized(string $message, int $status = 401, ?string $debug = null) {
    $response = new SlimResponse();
    $payload = ["error" => $message];

    if ($debug && ($_ENV['APP_ENV'] ?? 'prod') === 'dev') {
        $payload['debug'] = $debug;
    }

    $response->getBody()->write(json_encode($payload));
    return $response
        ->withStatus($status)
        ->withHeader("Content-Type", "application/json");
}

$auth_mw = function (Request $request, RequestHandler $handler) {
    $authHeader = $request->getHeaderLine("Authorization");

    if (!$authHeader || !str_starts_with($authHeader, "Bearer ")) {
        return unauthorized("No token provided", 403);
    }

    $token = substr($authHeader, 7);

    try {
        // Obtener JWKS de Keycloak
        $jwksJson = @file_get_contents($_ENV['JWKS_URL']);
        if ($jwksJson === false) {
            return unauthorized("Cannot fetch JWKS from Keycloak");
        }

        $jwks = json_decode($jwksJson, true);
        if (!$jwks || !isset($jwks['keys'])) {
            return unauthorized("Invalid JWKS format");
        }

        // Decodificar el token con las claves públicas de Keycloak
        $decoded = JWT::decode($token, JWK::parseKeySet($jwks));

        // Validar claims críticos
        if (!isset($decoded->iss) || $decoded->iss !== $_ENV['KC_ISSUER']) {
            return unauthorized("Invalid issuer");
        }

        if (!isset($decoded->aud) || $decoded->aud !== $_ENV['KC_CLIENT_ID']) {
            return unauthorized("Invalid audience");
        }

        // Guardar usuario en la request
        $request = $request->withAttribute("user", $decoded);
        $request = $request->withAttribute("user_id", $decoded->sub);

        return $handler->handle($request);

    } catch (\Throwable $e) {
        return unauthorized("Invalid token", 401, $e->getMessage());
    }
};
