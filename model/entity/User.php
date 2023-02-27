<?php
namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateur")
 */
class User
{
    /** Un seul user peux correspondre à plusieurs lignes de panier 
     * @ORM\OneToMany(targetEntity="Panier", mappedBy="user")
     */ 
    private $panier;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length="55")
     * @var string
     */
    private string $nom;

    /**
     * @ORM\Column(type="string", length="55")
     * @var string
     */
    private string $prenom;

    /**
     * @ORM\Column(type="string", length="150")
     * @var string
     */
    private string $mail;

    /**
     * @ORM\Column(type="string", length="255")
     * @var string
     */
    private string $password;

    /**
     * #ORM\Column(type="string", length="255")
     * @var string
     */
    private string $adresse;

     /**
     * #ORM\Column(type="string", length="155")
     * @var string
     */
    private string $ville;

     /**
     * #ORM\Column(type="string", length="50")
     * @var string
     */
    private string $cp;

     /**
     * #ORM\Column(type="string", length="50")
     * @var string
     */
    private string $pays;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->panier = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom.
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set mail.
     *
     * @param string $mail
     *
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail.
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add panier.
     *
     * @param \Model\Entity\Panier $panier
     *
     * @return User
     */
    public function addPanier(\Model\Entity\Panier $panier)
    {
        $this->panier[] = $panier;

        return $this;
    }

    /**
     * Remove panier.
     *
     * @param \Model\Entity\Panier $panier
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePanier(\Model\Entity\Panier $panier)
    {
        return $this->panier->removeElement($panier);
    }

    /**
     * Get panier.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPanier()
    {
        return $this->panier;
    }
}
