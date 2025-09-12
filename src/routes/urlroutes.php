<?php

    Use Slim\App;
    use App\Controllers\UrlController;
    use App\Middleware\AuthMiddleware;
    
    return function (App $app) {
        $app->post('/shorten', UrlController::class . ':shorten')->add(new AuthMiddleware());
        $app->get('/my-urls', UrlController::class . ':myUrls')->add(new AuthMiddleware());

        $app->get('/{code}', UrlController::class . ':redirect');
    };