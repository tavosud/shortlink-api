<?php

    require __DIR__ . '/../controllers/urlcontroller.php';
    require __DIR__ . '/../controllers/initcontroller.php';

    $app->get('/', \InitController::class . ':init');
        
    $app->post('/shorten', \UrlController::class . ':shorten')->add($auth_mw);
    $app->get('/my-urls', \UrlController::class . ':myUrls')->add($auth_mw);

    $app->get('/{code}', \UrlController::class . ':redirect');