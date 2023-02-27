<?php


use GuzzleHttp\Psr7\ServerRequest;
use DI\ContainerBuilder;
use function Http\Response\send;

require dirname(__DIR__).'/vendor/autoload.php';

$modules = [
   
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
    
