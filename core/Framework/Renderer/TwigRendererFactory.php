<?php
namespace Core\Framework\Renderer;

use Core\Framework\Renderer\TwigRenderer;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRendererfactory
{
    /**
     * __invoke est une méthode magique qui est appelé quand on essaye d'utiliser l'objet comme si il s'agissait d'une fonction
     * Exemple : $twig = TwigRendererFactory()
     *
     * @param ContainerInterface $container
     * @return TwigRenderer|null
     */
    public function __invoke(ContainerInterface $container): ?TwigRenderer
    {
        $loader = new FilesystemLoader($container->get('config.viewPath'));

        $twig = new Environment($loader, []);

        // récupère la liste d'extensions Twig à charger
        $extensions = $container->get("twig.extensions");

        // boucle sur la liste et ajoute à Twig
        foreach ($extensions as $extension){
            $twig->addExtension($container->get($extension));
        }

        return new TwigRenderer($loader, $twig);
    }
}