<?php
namespace Core\Framework\Router;

class Route
{
    private string $name;
    private $function;
    private array $params;

    /**
     * on enregistre les informations liée à la route
     *
     * @param string $name Nom de la route (exemple : user.login)
     * @param [type] $function Fonction de controller a appeler lors du match de la route
     * @param array $params Tableau de parametre de la route
     */
    public function __construct(string $name, $function, array $params)
    {
        $this->name = $name;
        $this->function = $function;
        $this->params = $params;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retourne la fonction de controller liée à la route
     * Get the value of function
     */ 
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Get the value of params
     */ 
    public function getParams()
    {
        return $this->params;
    }
}