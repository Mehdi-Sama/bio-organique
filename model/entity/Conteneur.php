<?php
namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contenu")
 */
class Conteneur
{
    /**
     * Many conteneur have Many product.
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="Conteneur")
     * @ORM\JoinTable(name="produit_contenu")
     * 
     */
    private $conteneur;
    


    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length="50")
     * @var string
     */
    private string $volume;

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
     * Set volume.
     *
     * @param string $volume
     *
     * @return Conteneur
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume.
     *
     * @return string
     */
    public function getVolume()
    {
        return $this->volume;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->conteneur = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add conteneur.
     *
     * @param \Model\Entity\Product $conteneur
     *
     * @return Conteneur
     */
    public function addConteneur(\Model\Entity\Product $conteneur)
    {
        $this->conteneur[] = $conteneur;

        return $this;
    }

    /**
     * Remove conteneur.
     *
     * @param \Model\Entity\Product $conteneur
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeConteneur(\Model\Entity\Product $conteneur)
    {
        return $this->conteneur->removeElement($conteneur);
    }

    /**
     * Get conteneur.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConteneur()
    {
        return $this->conteneur;
    }
}
