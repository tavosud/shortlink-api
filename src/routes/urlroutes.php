<?php

    Use Slim\App;
    use App\controllers\UrlController;
    use App\middleware\AuthMiddleware;
    
    return function (App $app) {
        $app->post('/shorten', UrlController::class . ':shorten')->add(new AuthMiddleware());
        $app->get('/my-urls', UrlController::class . ':myUrls')->add(new AuthMiddleware());

        $app->get('/{code}', UrlController::class . ':redirect');
    };