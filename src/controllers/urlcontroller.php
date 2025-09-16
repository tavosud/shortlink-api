<?php

class UrlController implements IMethods {
    private $urlModel;

    public function __construct() {
        $this->urlModel = new Url();
    }

    public function shorten($request, $response, $args) {
        $data = $request->getParsedBody();
        $originalUrl = $data['url'] ?? null;
        $userId = $request->getAttribute("user_id"); // de JWT

        if (!$originalUrl) {
            $response->getBody()->write(json_encode(["error" => "URL requerida"]));
            return $response->withHeader("Content-Type", "application/json")->withStatus(400);
        }

        // 🔹 validar contra blacklist
        if (!UrlValidator::isValid($originalUrl)) {
            $response->getBody()->write(json_encode(["error" => "URL no permitida"]));
            return $response->withHeader("Content-Type", "application/json")->withStatus(400);
        }

        try {
            $shortCode = substr(md5(uniqid()), 0, 6);
            $this->urlModel->create($originalUrl, $shortCode, $userId);
            
            $response->getBody()->write(json_encode([
                "short_url" => getenv("BASE_URL") . "/" . $shortCode
            ]));
            return $response->withHeader("Content-Type", "application/json");
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(["error" => $e->getMessage()]));
            return $response->withHeader("Content-Type", "application/json")->withStatus(400);
        }
    }

    public function redirect($request, $response, $args) {
        $url = $this->urlModel->findByShortCode($args['code']);
        if ($url) {
            return $response
                ->withHeader("Location", $url['original_url'])
                ->withStatus(302);
        }
        $response->getBody()->write("URL no encontrada");
        return $response->withStatus(404);
    }

    public function myUrls($request, $response, $args) {
        $userId = $request->getAttribute("user_id");
        $urls = $this->urlModel->findByUserId($userId);

        $response->getBody()->write(json_encode($urls));
        return $response->withHeader("Content-Type", "application/json");
    }
}
