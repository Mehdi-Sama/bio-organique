<?php
namespace Core\Framework\Renderer;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements RendererInterface
{
    private $twig;
    private $loader;

    /**
     * s'attend à une instance de FilesystemLoader et d'Environment
     *
     * @param FilesystemLoader $loader Objet qui recense les chemins vers les différents dossier de vues
     * @param Environment $twig Objet qui enregistre nos différentes extensions et permet de faire communiqué vue et controller
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        $this->loader = $loader;
        $this->twig = $twig;
    }

    /**
     * permet de d'enregistrer un chemin vers un ensemble de vue
     *
     * @param string $namespace Si $path est défini $namespace représente un alias du chemin vers les vues, sinon contient simplement le chemin
     * @param string|null $path Si définie contient le chemin vers les vues qui seront enregistrer sous la valeur de $namespace
     * @return void
     */
    public function addPath(string $namespace, ?string $path = null): void
    {
        $this->loader->addPath($path, $namespace);
    }

    /**
     * affiche la vue demandée
     *
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string
    {
        return $this->twig->render($view . '.html.twig', $params);
    }

    /**
     * ajoute des variables globales commune à toute less vues
     *
     * @param string $key
     * @param [type] $value
     * @return void
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}