<?php

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    interface IMethods {
        function shorten(Request $request, Response $response, array $args);
        function redirect(Request $request, Response $response, array $args);
        function myUrls(Request $request, Response $response, array $args);
    }