<?php
namespace Core\Framework\Router;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * entension Twig permettant d'appeler des fonctions definie du Router à l'interieur des vues twig
 */
class RouterTwigExtension extends AbstractExtension
{
    private Router $router;

    /**
     * récupère l'instance du Routeur et l'enregistre 
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * on déclare les fonctions disponible coté vues
     *
     * @return void
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('path', [$this, 'path'])
        ];
    }

    /**
     * Fait appel à la méthode generateUri() du Router et retourne son résultat
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function path(string $name, array $params = [] ): string
    {
        return $this->router->generateUri($name, $params);
    }
}