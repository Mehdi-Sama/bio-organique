<?php

use Core\Toaster\Toaster;
use Core\Db\DatabaseFactory;
use Core\Session\PHPSession;
use Doctrine\ORM\EntityManager;
use Core\Framework\Router\Router;
use Core\Session\SessionInterface;
use Core\Framework\Renderer\RendererInterface;
use Core\Framework\Router\RouterTwigExtension;
use Core\Framework\Renderer\TwigRendererfactory;
use Core\Framework\TwigExtensions\AssetsTwigExtension;
use Core\Toaster\ToasterTwigExtension;

return [
    "doctrine.user" => "root", 
    "doctrine.dbname" => "bio_organique",
    "doctrine.mdp" => "",
    "doctrine.driver" => "pdo_mysql",
    "doctrine.devMode" => true,
    "config.viewPath" => dirname(__DIR__).DIRECTORY_SEPARATOR.'view',
    "twig.extensions" => [
        RouterTwigExtension::class,
        ToasterTwigExtension::class,
        AssetsTwigExtension::class
        ],
    Router::class => \DI\create(),
    RendererInterface::class => \DI\factory(TwigRendererfactory::class),
    EntityManager::class => \DI\factory(DatabaseFactory::class),
    SessionInterface::class => \DI\get(PHPSession::class),
    Toaster::class => \DI\autowire()
];


?>