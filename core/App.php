<?php
namespace Core;

use Core\Framework\Middleware\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Core\Framework\Renderer\PHPRenderer;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use Core\Framework\Router\Router;
use Exception;

class App
{
    private Router $router;
    private array $modules;
    private ContainerInterface $container;
    private MiddlewareInterface $middleware;
    /**
     * initialise  la lsite des modules et enregistre le container de dépendance
     * @param ContainerInterface $container
     * @param array $modules 
     */

    public function __construct(ContainerInterface $container, array $modules = [])
    {
        $this->router = $container->get(Router::class);

        foreach($modules as $module){
            $this->modules[] = $container->get($module);
        }
        
        $this->container = $container;
       
    }

    /**
     * traite la requete du serveur en l'envoyant dans la chaine de responsabilité
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middleware->process($request);
    }

    /**
     * enregistre le premier middleware de la chaine de responsabilité
     *
     * @param MiddlewareInterface $middleware
     * @return MiddlewareInterface
     */
    public function linkFirst(MiddlewareInterface $middleware): MiddlewareInterface
    {
        $this->middleware = $middleware;
        return $middleware;
    }

    /**
     * retourne l'instance de PHP DI
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}

?>