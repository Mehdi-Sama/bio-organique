<?php
namespace App\Product;

use Core\Framework\Router\Router;
use App\Product\Action\MarqueAction;
use App\Product\Action\ProductAction;
use Psr\Container\ContainerInterface;
use Core\Framework\Renderer\RendererInterface;
use Core\Framework\AbstractClass\AbstractModule;

/**
 * @inheritDoc
 * 
 */
class ProductModule extends AbstractModule
{
    private Router $router;
    private RendererInterface $renderer;
   
    /**
     * @inheritDoc
     */
    public const DEFINITIONS = __DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';

    /**
     * déclare les routes et les méthodes disponible pour ce module, défini le chemin vers le dossier de vues du module
     * defini eventuellement des variables global à toute les vues
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) 
    {
        // Router pour déclarer les routes
        $this->router = $container->get(Router::class);
        // Renderer pour déclarer les vues
        $this->renderer = $container->get(RendererInterface::class);
        // Ensemble d'action possible
        $carAction = $container->get(ProductAction::class);
        $marqueAction = $container->get(MarqueAction::class);
        
        
        // déclaration du chemin des vues sous le namespace 'car'
        $this->renderer->addPath('product', __DIR__ . DIRECTORY_SEPARATOR . 'view');
                        //nom methode, objet, nom function et nom de la route
        // déclaration des routes disponible en méthode GET et POST 
        $this->router->get('/admin/addproduct', [$carAction, 'addproduct'], 'product.add');
        $this->router->post('/admin/addproduct', [$carAction, 'addproduct']);

        $this->router->get('/productUnique/{id:[\d]+}', [$carAction, 'productUnique'], 'product.unique');

        $this->router->get('/admin/listproduct', [$carAction, 'listProduct'], 'product.list');

        $this->router->get('/admin/update/{id:[\d]+}', [$carAction, 'update'], 'product.update');
        $this->router->post('/admin/update/{id:[\d]+}', [$carAction, 'update']);

        $this->router->get('/admin/delete/{id:[\d]+}', [$carAction, 'delete'], 'product.delete');
        
        
        
        $this->router->get('/admin/addMarque', [$marqueAction, 'addMarque'], 'marque.add');
        $this->router->post('/admin/addMarque', [$marqueAction, 'addMarque']);
        $this->router->get('/admin/listMarque', [$marqueAction, 'listMarque'], 'marque.list');
        $this->router->get('/admin/deleteMarque/{id:[\d]+}', [$marqueAction, 'deleteMarque'], 'marque.delete');
        $this->router->get('/admin/updateMarque/{id:[\d]+}', [$marqueAction, 'update'], 'marque.update');
        $this->router->post('/admin/updateMarque/{id:[\d]+}', [$marqueAction, 'update']);
    }

    
}   