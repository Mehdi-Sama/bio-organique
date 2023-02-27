<?php
namespace Core\Framework\AbstractClass;

/**
 * un module représente un ensemble de page qui sont chargé d'une responsabilité particulière
 * (exemple : CarModule est chargé à tout ce qui touche au véhicule ajout, modif, suppression , etc)
 * chaque module que l'on souhaite charger dans l'application doit être déclarer dans $modules dans /public/index.php
 */
abstract class AbstractModule
{
    /**
     * chemin du fichier de configuration destiné à PHP DI
     */
    public const DEFINITIONS = null; 
}

?>