<?php
namespace Core\Db;

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;

class DatabaseFactory
{
    public function __invoke(Containerinterface $container): ?EntityManager
    {
        $paths = [dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'model/entity'];
        $isDevMode = $container->get('doctrine.devMode');
        $dbParams = [
            "driver" => $container->get("doctrine.driver"),
            "user" => $container->get("doctrine.user"),
            "password" => $container->get("doctrine.mdp"),
            "dbname" => $container->get("doctrine.dbname"),
            "driverOptions" => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
            ]
        ];

        $config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode);

        try{
            $conn = DriverManager::getConnection($dbParams, $config);
            return new EntityManager($conn, $config);
        } catch (\Exception $e){
            echo "[ERREUR] : ". $e->getMessage();
            die;
        }
    }
}

?>