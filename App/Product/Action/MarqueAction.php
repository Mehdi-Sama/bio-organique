<?php
namespace App\Product\Action;


use Core\Toaster\Toaster;
use Model\Entity\Conteneur;
use GuzzleHttp\Psr7\Response;
use Doctrine\ORM\EntityManager;
use Core\Framework\Router\Router;
use Psr\Container\ContainerInterface;
use Core\Framework\Validator\Validator;
use Core\Framework\Router\RedirectTrait;
use Psr\Http\Message\ServerRequestInterface;
use Core\Framework\Renderer\RendererInterface;
use Model\Entity\Vehicule;

class MarqueAction
{
    use RedirectTrait;

    private RendererInterface $renderer;
    private EntityManager $manager;
    private Toaster $toaster;
    private $marqueRepository;
    private Router $router;
    private ContainerInterface $container;

    public function __construct(RendererInterface $renderer, EntityManager $manager, Toaster $toaster, ContainerInterface $container)
    {
        $this->renderer = $renderer;
        $this->manager = $manager;
        $this->toaster = $toaster;
        $this->marqueRepository = $manager->getRepository(Conteneur::class);
        $this->router = $container->get(Router::class);
    }

    /**
     * Rajoute une marque de véhicule
     *
     * @param ServerRequestInterface $request
     * @return void
     */

    public function addMarque(ServerRequestInterface $request)
    {
        $method = $request->getMethod();

        if ($method === 'POST') {
            $data = $request->getParsedBody();
            $marques = $this->marqueRepository->findAll();
            $validator = new Validator($data);
            $errors = $validator
                ->required('volume')
                ->getErrors();
                if ($errors) {
                    foreach ($errors as $error) {
                        $this->toaster->makeToast($error->toString(), Toaster::ERROR);
                    }
                    return $this->redirect('marque.add');
                    // return (new Response())
                    //     ->withHeader('Location', '/admin/addMarque');
                }
            foreach ($marques as $marque) {
                if ($marque->getVolume() === $data['volume']) {
                    $this->toaster->makeToast('Ce conteneur existe déjà', Toaster::ERROR);
                    return $this->renderer->render('@product/addMarque');
                }
            }
            $new = new Conteneur();
            $new->setVolume($data['volume']);
            $this->manager->persist($new);
            $this->manager->flush();
            $this->toaster->makeToast('Conteneur créée avec succès', Toaster::SUCCESS);
            return $this->redirect('marque.list');
            // return (new Response)
            //     ->withHeader('Location', '/admin/listCar');
        }
        return $this->renderer->render('@product/addMarque');
    }

    /**
     * Affiche la liste des marques existantes
     *
     * @param ServerRequestInterface $request
     * @return void
     */
    public function listMarque(ServerRequestInterface $request)
    {
        $marques = $this->marqueRepository->findAll();
        return $this->renderer->render('@product/listMarque', ["marques" => $marques]);
    }

    /**
     * Supprime une marque de véhicule
     *
     * @param ServerRequestInterface $request
     * @return void
     */
    public function deleteMarque(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        $marque = $this->marqueRepository->find($id);
        $this->manager->remove($marque);
        $this->manager->flush();
        $this->toaster->makeToast('Conteneur supprimée', Toaster::SUCCESS);
        return $this->redirect('marque.list');
        // return (new Response())
        //     ->withHeader('Location', '/admin/listMarque');
    }

    public function update(ServerRequestInterface $request)
    {
        $method = $request->getMethod();
        $id = $request->getAttribute('id');
        $marque = $this->marqueRepository->find($id);

        if ($method === 'POST')
        {
            $data = $request->getParsedBody();
            $validator = new Validator($data);
            $errors = $validator
                ->required('volume')
                ->getErrors();
                if ($errors) {
                    foreach ($errors as $error) {
                        $this->toaster->makeToast($error->toString(), Toaster::ERROR);
                    }
                    return $this->redirect('marque.update');
                    // return (new Response())
                    //     ->withHeader('Location', '/admin/updateMarque'.$id);
                }
            $marque->setVolume($data['volume']);
            $this->manager->flush();
            $this->toaster->makeToast('Conteneur modifée', Toaster::SUCCESS);
            return $this->redirect('marque.list');
            // return (new Response())
            // ->withHeader('Location', '/admin/listMarque');
        }

        return $this->renderer->render('@product/updateMarque', ['volume' => $marque]); 
    }
}
