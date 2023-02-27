<?php
namespace Core\Framework\Middleware;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * si une route a été matché,et appel la fonction liée à celle-ci
 */
class RouterDispatcherMiddleware extends AbstractMiddleware
{

    /**
     * @throws Exception
     */
    public function process(ServerRequestInterface $request)
    { 
        $route = $request->getAttribute('_route');
        if (is_null($route)) {
            return parent::process($request);
        }
        
        $response = call_user_func_array($route->getFunction(), [$request]);

        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new Exception("Le serveur n'a pas renvoyé de réponse valable.");
        }
    }
}