<?php
namespace Core\Framework\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * extension Twig permettant d'accéder directement au dossier public
 * utile pour donner  les chemins de style , des scripts js, des images et de tout le contenu du dossier assets
 */
class AssetsTwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [ new TwigFunction('assets', [$this, 'asset'])];
    }

    public function asset(string $path): string
    {
        $file = dirname(__DIR__, 3). '/public/'.$path;
        if(!file_exists($file)){
            throw new \Exception("Le fichier $file n'existe pas");
        }
        $path .= '?'.filemtime($file);
        return $path;
    }
}