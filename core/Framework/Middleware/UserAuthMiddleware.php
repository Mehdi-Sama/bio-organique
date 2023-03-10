<?php
namespace Core\Framework\Middleware;

use Core\Framework\Auth\UserAuth;
use Core\Framework\Router\RedirectTrait;
use Core\Toaster\Toaster;
use Core\Framework\Router\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * verifie si la route est protégé grâce au début de l'url,
 * si oui, s'assure que l'utilisateur à le droit d'y accéder
 */
class UserAuthMiddleware extends AbstractMiddleware
{
    use RedirectTrait;

    private ContainerInterface $container;
    private Toaster $toaster;
    private Router $router;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->toaster = $container->get(Toaster::class);
        $this->router = $container->get(Router::class);
    }

    public function process(ServerRequestInterface $request)
    {
        $uri = $request->getUri()->getPath();
        if(str_starts_with($uri, '/user')){
            $auth = $this->container->get(UserAuth::class);
            if(!$auth->isLogged() or !$auth->isUser()){
                $toaster = $this->container->get(Toaster::class);
                $toaster->makeToast("Veuillez vous connecté pour continuer", Toaster::ERROR);
                return $this->redirect('user.login');
            } 
        }
        return parent::process($request);
    }

  
}