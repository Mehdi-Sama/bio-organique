<?php
namespace App\Admin;

use App\Admin\Action\AdminAction;
use App\Admin\Action\AuthAction;
use Core\Framework\AbstractClass\AbstractModule;
use Core\Framework\Router\Router;
use Core\Framework\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;

class AdminModule extends AbstractModule
{

    public const DEFINITIONS = __DIR__. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

    private Router $router;
    private RendererInterface $renderer;
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->router = $this->container->get(Router::class);
        $this->renderer = $container->get(RendererInterface::class);
        $authAction = $container->get(AuthAction::class);
        $adminAction = $container->get(AdminAction::class);
        
        $this->renderer->addPath('admin', __DIR__ . DIRECTORY_SEPARATOR . 'view');
        $this->router->get('/admin/login', [$authAction, 'login'], 'admin.login');
        $this->router->post('/admin/login', [$authAction, 'login']);

        $this->router->get('/admin/home', [$adminAction, 'home'], 'admin.home');
        $this->router->get('/admin/logout', [$authAction, 'logout'], 'admin.logout');
    }
}