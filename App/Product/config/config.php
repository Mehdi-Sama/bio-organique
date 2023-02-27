<?php

use \App\Product\ProductModule;
// Chaque fichier de configuration doit retourner associatif et peut autant déclarer des manières d'instancier une class
// que simplement déclarer des valeurs à enregistrer
return [
  ProductModule::class => \DI\autowire(),
  'img.basePath' => dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' .
  DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR,
]

?>