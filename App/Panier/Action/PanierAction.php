<?php

namespace App\Panier\Action;

use Model\Entity\Marque;
use Core\Toaster\Toaster;
use Model\Entity\Product;
use Model\Entity\Conteneur;
use GuzzleHttp\Psr7\Response;
use Doctrine\ORM\EntityManager;
use Core\Framework\Router\Router;
use GuzzleHttp\Psr7\UploadedFile;
use Core\Session\SessionInterface;
use GuzzleHttp\Psr7\ServerRequest;
use App\Product\Action\ProductAction;
use Psr\Container\ContainerInterface;
use Core\Framework\Validator\Validator;
use App\Panier\Action\Productcontroller;
use Core\Framework\Router\RedirectTrait;
use Psr\Http\Message\ServerRequestInterface;
use Core\Framework\Renderer\RendererInterface;

class PanierAction
{
    use RedirectTrait;

    private RendererInterface $renderer;
    private EntityManager $manager;
    private Toaster $toaster;
    private $repository;
    private $marqueRepository;
    private ContainerInterface $container;
    private Router $router;
    private SessionInterface $session;
    

    public function __construct(RendererInterface $renderer, EntityManager $manager, Toaster $toaster, ContainerInterface $container, SessionInterface $session)
    {
        $this->renderer = $renderer;
        $this->manager = $manager;
        $this->toaster = $toaster;
        $this->container = $container;
        $this->router = $container->get(Router::class);
        $this->session = $container->get(SessionInterface::class);
        $this->repository = $manager->getRepository(Product::class);
        $this->marqueRepository = $manager->getRepository(Conteneur::class);
    }

    
    public function homePanier(ServerRequestInterface $request)
    {
        $panier = $this->session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $product = $this->repository->find($id);
            $volume = $product->getConteneur();
            $dataPanier[] = [
                "produit" => $product,
                "volume" => $volume,
                "quantite" => $quantite
            ];
            $total += $product->getPrix() * $quantite;
        }
        return $this->renderer->render('@panier/homePanier', compact("dataPanier", "total") );
    }

    
    public function addPanier(ServerRequestInterface $request)
    {
        // On récupère le panier actuel
        $panier = $this->session->get("panier", []);
        $id = $request->getAttribute('id');
        
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $this->session->set("panier", $panier);

        return $this->redirect('home.panier');
    }

   
    public function remove(ServerRequestInterface $request)
    {
        // On récupère le panier actuel
        $panier = $this->session->get("panier", []);
        $id = $request->getAttribute('id');

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $this->session->set("panier", $panier);

        return $this->redirect('home.panier');
    }

   
    public function delete(ServerRequestInterface $request)
    {
        // On récupère le panier actuel
        $panier = $this->session->get("panier", []);
        $id = $request->getAttribute('id');

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $this->session->set("panier", $panier);

       
        return $this->redirect('home.panier');
    }

    

}
