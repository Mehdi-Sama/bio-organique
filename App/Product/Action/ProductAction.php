<?php

namespace App\Product\Action;


use Core\Toaster\Toaster;
use Model\Entity\Product;
use GuzzleHttp\Psr7\Response;
use Doctrine\ORM\EntityManager;
use Core\Framework\Router\Router;
use GuzzleHttp\Psr7\UploadedFile;
use Psr\Container\ContainerInterface;
use Core\Framework\Validator\Validator;
use Core\Framework\Router\RedirectTrait;
use Psr\Http\Message\ServerRequestInterface;
use Core\Framework\Renderer\RendererInterface;

class ProductAction
{
    use RedirectTrait;

    private RendererInterface $renderer;
    private EntityManager $manager;
    private Toaster $toaster;
    private $repository;
    private $marqueRepository;
    private ContainerInterface $container;
    private Router $router;

    public function __construct(RendererInterface $renderer, EntityManager $manager, Toaster $toaster, ContainerInterface $container)
    {
        $this->renderer = $renderer;
        $this->manager = $manager;
        $this->toaster = $toaster;
        $this->container = $container;
        $this->router = $container->get(Router::class);
        // sert à manipuler les marques en bdd
        // $this->marqueRepository = $manager->getRepository(Marque::class);
        // sert à manipuler les véhicules
        $this->repository = $manager->getRepository(Product::class);
    }

    /**
     * Methode ajoutant un vehicule en bdd
     *
     * @param ServerRequestInterface $request
     * @return void
     */

    public function addVehicule(ServerRequestInterface $request)
    {
        // recupère la méthode utilisé pour la requête (POST ou GET)
        $method = $request->getMethod();

        // si le formulaire a été soumis
        if ($method === 'POST') {
            // on récupère le contenu de $_POST (les valeurs saisie dans le formulaire)
            $data = $request->getParsedBody();
            // on récupère le contenu de $_FILES à l'index 'image' avec un input de type file
            $file = $request->getUploadedFiles()['image'];
            // on instancie le Validator en passant le tableau de données à valider
            $validator = new Validator($data);
            // on fixe les règles à respecter sur chaque input du formulaire et on récupère les erreurs s'il y en a
            $errors = $validator
                ->required('model', 'color', 'marque')
                ->getErrors();
            // si il y a des erreurs, on crée un Toast par erreur et on redirige l'utilisateur afin d'afficher les messages
            if ($errors) {
                // boucle sur le tableau
                foreach ($errors as $error) {
                    // création du Toast
                    $this->toaster->makeToast($error->toString(), Toaster::ERROR);
                }
                // redirection
                return $this->redirect('Ajout de véhicules');
                // return (new Response())
                //     ->withHeader('Location', '/admin/addVehicule');
            }

            // on vérifie que l'image soit conforme (voir commentaire de la methode)
            $error = $this->fileGuards($file);
            // si on a des erreur on retourne le Toast (le Toast a été généré par 'fileGuard')
            if($error !== true){
                return $error;
            }
            // si tout va bien avec le fichier, on récupère le nom
            $fileName = $file->getClientFileName();
            // on assemble le nom du fichier avec le chemin du dossier ou il sera enregistré
            $imgPath = $this->container->get('img.basePath').$fileName;
            // on tente de le déplacer au chemin voulu
            $file->moveTo($imgPath);
            // si le déplacement n'est pas possible, on créer un Toast et redirection
            if (!$file->isMoved()) {
                $this->toaster->makeToast("Une erreur s'est produite durant l'enregistrement de votre image, merci de réessayer", Toaster::ERROR);
                return $this->redirect('Ajout de véhicules');
                // return (new Response())
                //     ->withHeader('Location', '/admin/addVehicule');
            }
            // si tout s'est bien passée, on créer un nouveau véhicule
            $new = new Product();
            // on récupère l'objet réprésentant la marque choisie
            $marque = $this->marqueRepository->find($data['marque']);
            // si on a bien réussi à récuprer une marque, on complète les infos du véhicule et on l'enregistre
            if ($marque) {
                // complétion des infos du véhicule 
                $new
                    
                    ->setImgPath($fileName);
                // préparation à l'enregistrement en bdd
                $this->manager->persist($new);
                // enregistrement en bdd
                $this->manager->flush();
                // Création d'un Toast
                $this->toaster->makeToast('Voiture ajoutée avec succès', Toaster::SUCCESS);
            }
            // dans tous les cas on fini par renvoyé la vue 
            return $this->redirect('car.list');
            // return (new Response)
            //     ->withHeader('Location', '/admin/listCar');
        }

        $marques = $this->marqueRepository->findAll();
        return $this->renderer->render('@car/addVehicule', ['marques' => $marques]);
    }

    /**
     * Retourne la liste des véhicules en bss
     *
     * @param ServerRequestInterface $request
     * @return string
     */

    public function listCar(ServerRequestInterface $request): string
    {
        $voitures = $this->repository->findAll();

        return $this->renderer->render('@car/list', ["voitures" => $voitures]);
    }



    /**
     * Retourne les détails d'un véhicule sélectionné selon son id 
     *
     * @param ServerRequestInterface $request
     * @return string
     */

    public function carUnique(ServerRequestInterface $request): string
    {
        $id = $request->getAttribute('id');

        $voiture = $this->repository->find($id);

        return $this->renderer->render('@car/carUnique', ["unique" => $voiture]);
    }

    /**
     * Modifie un véhicule en bdd
     *
     * @param ServerRequestInterface $request
     * @return void
     */

    public function update(ServerRequestInterface $request)
    {
        $method = $request->getMethod();
        $id = $request->getAttribute('id');
        $voiture = $this->repository->find($id);

        if ($method === 'POST') {

            $data = $request->getParsedBody();
            $files = $request->getUploadedFiles();

            if (sizeof($files) > 0 && $files['img']->getError() !== 4) {
                $oldImg = $voiture->getImgPath();
                $newImg = $files['img'];
                $imgName = $newImg->getClientFileName();
                $imgPath = $this->container->get('img.basePath').$imgName;

                $this->fileGuards($newImg);
                $newImg->moveTo($imgPath);
                if ($newImg->isMoved()) {
                    $voiture->setImgPath($imgName);
                    $oldPath = $this->container->get('img.basePath').$oldImg;
                    unlink($oldPath);
                }
            }

            $marque = $this->marqueRepository->find($data['marque']);
            $voiture->setModel($data['model'])
                ->setIdMarque($marque)
                ->setCouleur($data['color']);

            $this->manager->flush();
            $this->toaster->makeToast('Modifications prise en compte', Toaster::SUCCESS);
            return $this->redirect('car.list');
            // return (new Response)
            //     ->withHeader('Location', '/admin/listCar');
        }

        $marques = $this->marqueRepository->findAll();
        return $this->renderer->render('@car/update', ["voiture" => $voiture, "marques" => $marques]);
    }

    /**
     * Supprime un véhicule de la bdd
     *
     * @param ServerRequestInterface $request
     * @return void
     */

    public function delete(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        $voiture = $this->repository->find($id);
        $this->manager->remove($voiture);
        $this->manager->flush();
        $this->toaster->makeToast('Véhicule supprimé', Toaster::SUCCESS);
        return $this->redirect('car.list');
        // return (new Response())
        //     ->withHeader('Location', '/admin/listCar');
    }

    private function fileGuards(UploadedFile $file)
    {
        //Handle server error
        if ($file->getError() === 4) {
            $this->toaster->makeToast('Une erreur est survenue lors du chargement du fichier', Toaster::ERROR);
            return $this->redirect('Ajout de véhicules');
            // return (new Response())
            //     ->withHeader('Location', '/admin/addVehicule');
        }

        //function native qui récupère le type et le format (destructuration de tableau)
        list($type, $format) = explode('/', $file->getClientMediaType());

        //handle format error
        if (!in_array($type, ['image']) or !in_array($format, ['jpg', 'jpeg', 'png'])) {
            $this->toaster->makeToast("ERREUR / Le format du fichier n'est pas valide, merci de charger un .jpg ou .jpeg ou .png ", Toaster::ERROR);
            return $this->redirect('Ajout de véhicules');
            // return (new Response())
            //     ->withHeader('Location', '/admin/addVehicule');
        }

        //handle excessive size
        if ($file->getSize() > 2047674) {
            $this->toaster->makeToast("Merci de choisir un fichier n'excédant pas 2mo", Toaster::ERROR);
            return $this->redirect('Ajout de véhicules');
            // return (new Response())
            //     ->withHeader('Location', '/admin/addVehicule');
        }

        return true;
    }
}
