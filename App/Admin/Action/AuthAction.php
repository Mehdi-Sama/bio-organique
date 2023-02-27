<?php
namespace App\Admin\Action;

use Core\Toaster\Toaster;
use GuzzleHttp\Psr7\Response;
use Core\Framework\Router\Router;
use Core\Framework\Auth\AdminAuth;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
use Core\Framework\Validator\Validator;
use Core\Framework\Renderer\RendererInterface;

class AuthAction
{
    private ContainerInterface $container;
    private RendererInterface $renderer;
    private Router $router;
    private Toaster $toaster;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->renderer = $container->get(RendererInterface::class);
        $this->router = $container->get(Router::class);
        $this->toaster =  $container->get(Toaster::class);
    }

    public function login(ServerRequest $request)
    {
        $method = $request->getMethod();
        if($method === 'POST'){
            $auth = $this->container->get(AdminAuth::class);
            $data = $request->getParsedBody();
            $validator = new Validator($data);
            $errors = $validator->required('mail', 'mdp')
                ->getErrors();
            if (!empty($errors))
            {
                    foreach ($errors as $error) {
                    $this->toaster->makeToast($error->toString(), Toaster::ERROR);
                    }
                    return (new Response())
                    ->withHeader('Location', '/admin/login');
            }
            if($auth->login($data['mail'], $data['mdp']))
            {
                $this->toaster->makeToast('Connexion réussie', Toaster::SUCCESS);
                return(new Response())
                    ->withHeader('Location', '/admin/home');
            }
            $this->toaster->makeToast('Connexion échouée', Toaster::ERROR);
            return(new Response())
                    ->withHeader('Location', '/admin/login');
        }
        return $this->renderer->render('@admin/login');
    }

    public function logout()
    {
        $auth = $this->container->get(AdminAuth::class);
        $auth->logout();
        $this->toaster->makeToast('Déconnexion réussie', Toaster::SUCCESS);
        return(new Response())
                    ->withHeader('Location', '/admin/login');
    }
}