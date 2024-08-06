<?php

namespace MapasCulturais\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use MapasCulturais\App;

class Redirect
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $app = App::i();

        $response = $handler->handle($request);

        $endTime = microtime(true);
        $execution_time = number_format($endTime - $app->startTime, 3);
        $mem = memory_get_usage(true) / 1024 / 1024;

        $route = $app->request->route;
        if (
            str_contains($route, 'site.index') ||
            str_contains($route, 'panel.index') ||
            str_contains($route, 'panel.registration')
        ) {
            $response = $response->withStatus(302);
            return $response->withHeader('Location', '/edital/4');
        }

        return $response;
    }

    function rutime($ru, $rus, $index)
    {
        return ($ru["ru_$index.tv_sec"] * 1000 + intval($ru["ru_$index.tv_usec"] / 1000))
            -  ($rus["ru_$index.tv_sec"] * 1000 + intval($rus["ru_$index.tv_usec"] / 1000));
    }
}