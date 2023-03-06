<?php
namespace App\Product;

use Core\Framework\Router\Router;
use App\Product\Action\MarqueAction;
use App\Product\Action\ProductAction;
use Psr\Container\ContainerInterface;
use Core\Framework\Renderer\RendererInterface;
use Core\Framework\AbstractClass\AbstractModule;

class PanierModule extends AbstractModule
{
    public function __construct()
    {
        if(!isset($_SESSION)){
            session_start();
        }
        if(!isset($_SESSION['panier'])){
            $_SESSION['panier'] = array();
        }
    } 
}