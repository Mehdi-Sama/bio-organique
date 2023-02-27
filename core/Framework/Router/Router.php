<?php
namespace Core\Framework\Router;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;
use Core\Framework\Router\Route;

class Router
{
   private FastRouteRouter $router;

    private array $routes = [];

    /**
     * instancie un fastrouterouter et l'enregistre
     */
    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * ajoute une route disponible en methode get
     *
     * @param string $path
     * @param [type] $function
     * @param string $name
     * @return void
     */
    public function get(string $path, $function, string $name): void
    {
        $this->router->addRoute(new ZendRoute($path, $function, ['GET'], $name));
        $this->routes[] = $name;
    }

    /**
     * ajoute une route disponible en methode post
     *
     * @param string $path
     * @param [type] $function
     * @param string|null $name
     * @return void
     */
    public function post(string $path, $function, string $name = null): void
    {
        $this->router->addRoute(new ZendRoute($path, $function, ['POST'], $name));
        
    }

    /**
     * on vérifie que l'URL et la méthode de la requete correspondent à une route connue
     * si oui retourne un objet Route qui correspond
     *
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request);

        if($result->isSuccess()){
            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedMiddleware(),
                $result->getMatchedParams()
            );
        }

        return null;    
    }

    /**
     * genere l'URl de la route demandée en fonction de son nom
     * [optionnel : on peut ajouter un tableau de paramètre]
     *
     * @param string $nameRoad
     * @param array|null $params [optionnel]
     * @return string|null
     */
    public function generateUri(string $nameRoad, ?array $params = []): ?string
    {
        return $this->router->generateUri($nameRoad, $params);
    }
}