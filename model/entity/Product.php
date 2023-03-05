<?php
namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="produit")
 */
class Product
{
    /**
     * Un seul produit peut se retrouver dans plusieurs panier
     * @ORM\OneToMany(targetEntity="Panier", mappedBy="product")
     */
    private $panier; 


    /**
     * @ORM\ManyToOne(targetEntity="Conteneur", inversedBy="Product")
     * @ORM\JoinColumn(name="volume", referencedColumnName="id", onDelete="CASCADE")
     * @var Conteneur
     */
    private Conteneur $conteneur; 
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length="155")
     * @var string
     */
    private string $libelle;

    /**
     * @ORM\Column(type="float", length="155")
     * @var float
     */
    private float $prix;

    /**
     * @ORM\Column(type="string", name="img_path")
     *
     * @var string
     */
    private string $imgPath;
    
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
     * Set libelle.
     *
     * @param string $libelle
     *
     * @return Product
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle.
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set prix.
     *
     * @param float $prix
     *
     * @return Product
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set imgPath.
     *
     * @param string $imgPath
     *
     * @return Product
     */
    public function setImgPath($imgPath)
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    /**
     * Get imgPath.
     *
     * @return string
     */
    public function getImgPath()
    {
        return $this->imgPath;
    }

    /**
     * Add panier.
     *
     * @param \Model\Entity\Panier $panier
     *
     * @return Product
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

    /**
     * Set conteneur.
     *
     * @param \Model\Entity\Conteneur|null $conteneur
     *
     * @return Product
     */
    public function setConteneur(\Model\Entity\Conteneur $conteneur = null)
    {
        $this->conteneur = $conteneur;

        return $this;
    }

    /**
     * Get conteneur.
     *
     * @return \Model\Entity\Conteneur|null
     */
    public function getConteneur()
    {
        return $this->conteneur;
    }

}