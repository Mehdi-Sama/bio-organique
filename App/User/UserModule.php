<?php
namespace App\User;

use App\User\Action\UserAction;
use Core\Framework\Router\Router;
use Psr\Container\ContainerInterface;
use Core\Framework\Renderer\RendererInterface;
use Core\Framework\AbstractClass\AbstractModule;

class UserModule extends AbstractModule
{
    public const DEFINITIONS = __DIR__. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    private ContainerInterface $container;
    private RendererInterface $renderer;
    private Router $router;

    public function __construct(ContainerInterface $container)
    {
        $userAction = $container->get(UserAction::class);
        $this->container = $container;
        $this->renderer = $container->get(RendererInterface::class);
        $this->router = $container->get(Router::class);
        $this->renderer->addPath('user', __DIR__.DIRECTORY_SEPARATOR.'view');
        $this->router->get('/login', [$userAction, 'logView'], 'user.login');
        $this->router->post('/newUser', [$userAction, 'signin'], 'user.new');
        $this->router->post('/connexion', [$userAction, 'login'], 'user.connexion');
        $this->router->get('/user/home', [$userAction, 'home'], 'user.home');
        $this->router->get('/user/logout', [$userAction, 'logout'], 'user.logout');
        $this->router->get('/about', [$userAction, 'about'], 'user.about');
        

    }
}