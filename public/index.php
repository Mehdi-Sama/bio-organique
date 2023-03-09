<?php

use Core\App;
use App\Home\HomeModule;
use App\User\UserModule;
use DI\ContainerBuilder;
use Model\Entity\Product;
use App\Admin\AdminModule;
use App\Panier\PanierModule;
use App\Product\ProductModule;
use function Http\Response\send;
use GuzzleHttp\Psr7\ServerRequest;
use Core\Framework\Middleware\RouterMiddleware;
use Core\Framework\Middleware\NotFoundMiddleware;
use Core\Framework\Middleware\UserAuthMiddleware;
use Core\Framework\Middleware\AdminAuthMiddleware;
use Core\Framework\Middleware\TrailingSlashMiddleware;
use Core\Framework\Middleware\RouterDispatcherMiddleware;

require dirname(__DIR__).'/vendor/autoload.php';

$modules = [
   AdminModule::class,
   UserModule::class,
   ProductModule::class,
   HomeModule::class,
   PanierModule::class
   
];

// conteneur de dépendances
$builder = new ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__). DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');

foreach ($modules as $module){
    if(!is_null($module::DEFINITIONS)){
        $builder->addDefinitions($module::DEFINITIONS);
    }
}

$container = $builder->build();
$app = new App($container, $modules,);
$app->linkFirst(new TrailingSlashMiddleware())
    ->linkWith(new RouterMiddleware($container))
    ->linkWith(new AdminAuthMiddleware($container))
    ->linkWith(new UserAuthMiddleware($container))
    ->linkWith(new RouterDispatcherMiddleware())
    ->linkWith(new NotFoundMiddleware());

if (php_sapi_name() !=='cli')
{$response = $app->run(ServerRequest::fromGlobals());
    send($response);
}
    

?>
    
