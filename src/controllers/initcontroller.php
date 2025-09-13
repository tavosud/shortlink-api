<?php

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
            
    class InitController{
        
        public function init(Request $request, Response $response, array $args){
            $result = '<h2 align="center">'. $_ENV['API_NAME'] .'</h2><p align="center">by Tavosud</p>';
            $response->getBody()->write($result);
            return $response;
            
        }

    }