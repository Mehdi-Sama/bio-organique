<?php
namespace App\Panier;

use Core\Framework\Router\Router;
use App\Panier\Action\PanierAction;
use Psr\Container\ContainerInterface;
use Core\Framework\Renderer\RendererInterface;
use Core\Framework\AbstractClass\AbstractModule;

/**
 * @inheritDoc
 * 
 */
class PanierModule extends AbstractModule
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
        $panier = $container->get(PanierAction::class);
        
        
        // déclaration du chemin des vues sous le namespace 'car'
        $this->renderer->addPath('panier', __DIR__ . DIRECTORY_SEPARATOR . 'view');
                        //nom methode, objet, nom function et nom de la route
        // déclaration des routes disponible en méthode GET et POST 
        $this->router->get('/home/panier', [$panier, 'homePanier'], 'home.panier');
        $this->router->get('/add/{id:[\d]+}', [$panier, 'addPanier'], 'productpanier.add');
        $this->router->post('/add/{id:[\d]+}', [$panier, 'addPanier']);
        $this->router->get('/remove/{id}', [$panier, 'remove'], 'productpanier.remove');
        $this->router->get('/delete/{id}', [$panier, 'delete'], 'productpanier.delete');

        
    }

    
}   