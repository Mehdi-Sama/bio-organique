<?php
namespace Core\Framework\Middleware;

use Core\Framework\Auth\AdminAuth;
use Core\Toaster\Toaster;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * verifie si la route est protégé grâce au début de l'url,
 * si oui, s'assure que l'utilisateur à le droit d'y accéder
 */
class AdminAuthMiddleware extends AbstractMiddleware
{
    private ContainerInterface $container;
    private Toaster $toaster;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->toaster = $container->get(Toaster::class);
    }
    public function process(ServerRequestInterface $request)
    {
        $uri = $request->getUri()->getPath();
        // on vérifie si l'url commence par '/admin' et n'est pas égale à '/admin/login'
        if(str_starts_with($uri, '/admin') && $uri !== '/admin/login')
        {
            // on récupère l'objet qui gère l'administrateur 
            $auth = $this->container->get(AdminAuth::class);
            // on vérifie si l'administrateur est connecté et qu'il s'agit bien d'un admin
            if(!$auth->isLogged() || !$auth->isAdmin()){
                if(!$auth->isLogged()){
                    // si personne n'est connecté on renvoi un message en conséquence
                    $this->toaster->makeToast("Vous devez être connecté pour accéder à cette page", Toaster::ERROR);
                }elseif(!$auth->isAdmin()){
                    // si quelqu'un connecté mais n'est pas admin alors on lui refuse l'accès
                    $this->toaster->makeToast("Vous ne possédez pas les droits d'accès", Toaster::ERROR);
                }
                return (new Response())
                    ->withHeader('Location', '/');
            }
        }
        return parent::process($request);
    }
}