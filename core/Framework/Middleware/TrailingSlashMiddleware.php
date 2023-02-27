<?php
namespace Core\Framework\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

/**
 * retirer le slash à la fin de l'url si il y en a 1
 */
class TrailingSlashMiddleware extends AbstractMiddleware
{
    public function process(ServerRequestInterface $request)
    {
        $uri = $request->getUri()->getPath();
        if(!empty($uri) && $uri[-1] === '/' && $uri !='/'){
            return (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));
        }
        return parent::process($request);
    }
}