<?php
namespace Core\Framework\Validator;

use Doctrine\ORM\EntityRepository;

class Validator
{
    private array $data;
    private array $errors;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Undocumented function
     *
     * @param string ...$keys permet de précisé que l'on s'attend a un nombre indefinie de valeurs
     * @return self
     */
    public function required(string ...$keys):self
    {
        foreach($keys as $key){
            if(!array_key_exists($key, $this->data) || $this->data[$key] === '' || $this->data[$key] === null){
                $this->addError($key, 'required');
            }
        }
        return $this;
    }

    /**
     * s'assure que le champs est une adresse valide
     *
     * @param string $key
     * @return self
     */
    public function email(string $key): self
    {
        //filter_var fonction native qui permet de verifier la conformité d'une valeur en fonction d'un filtre 
        if(!filter_var($this->data[$key], FILTER_VALIDATE_EMAIL)){
            $this->addError($key, 'email');
        }
        return $this;
    }

    /**
     * s'assure que le nombre de caractère d'une chaine soit bien compris entre un minimum et un maximum
     *
     * @param string $key
     * @param integer $min
     * @param integer $max
     * @return self
     */
    public function strSize(string $key, int $min, int $max): self
    {
        if(!array_key_exists($key, $this->data)){
            return $this;
        }
        $length = mb_strlen($this->data[$key]);
        if($length < $min){
            $this->addError($key, 'strMin');
        }
        if($length > $max){
            $this->addError($key, 'strMax');
        }
        return $this;
    }

    /**
     * s'assure que le champ saisi possère la même valeur que son champ de confirmation
     * si la valeur de $key est "mdp" le champ de confirmation doit absolument se nommée "mdp_confirm"
     *
     * @param string $key
     * @return self
     */
    public function confirm(string $key): self
    {
        $confirm = $key.'_confirm';
        if(!array_key_exists($key, $this->data)){
            return $this;
        }
        if(!array_key_exists($confirm, $this->data)){
            return $this;
        }
        if($this->data[$key] !== $this->data[$confirm]){
            $this->addError($key, 'confirm');
        }
        return $this;
    }

    /**
     * s'assure qu'une valeur soit unique en bdd 
     *
     * @param string $key Index du tableau 
     * @param EntityRepository $repo Doctrine's repositories elements to chek
     * @param string $field Champ à vérifié en bdd (par defaut vaut nom)
     * @return self
     */
    public function isUnique(string $key, EntityRepository $repo, string $field = 'nom'):self
    {
        // on récupère toutes les entités du repositories
        $all = $repo->findAll();
        // création du nom de la méthode utilisable pour récupèrer la valeur (exemple : $field = 'model' alors $method = 'getModel')
        $method = 'get'.ucfirst($field);
        // on boucle sur tout les enregistrements de la bdd
        foreach($all as $item){
            // on vérifié si la valeur saisie par l'utilisateur correspond à une valeur existante en bdd sans tenir compte des accents, sinon erreur
            if(strcasecmp($item->$method(), $this->data[$key]) === 0){
                $this->addError($key, 'unique');
                break;
            }
        }
        return $this;
    }

    /**
     * renvoi le tableau d'erreurs, doit être appelé en dernier
     *
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors ?? null;
    }

    private function addError(string $key, string $rule): void
    {
        if(!isset($this->errors[$key])){
            $this->errors[$key] = new ValidatorError($key, $rule);
        }
    }
}