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
     * @ORM\OneToMany(targetEntity="Product", mappedBy="Conteneur")
     *
     * @var [type]
     */
    private $product;

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
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product.
     *
     * @param \Model\Entity\Product $product
     *
     * @return Conteneur
     */
    public function addProduct(\Model\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param \Model\Entity\Product $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(\Model\Entity\Product $product)
    {
        return $this->product->removeElement($product);
    }

    /**
     * Get product.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduct()
    {
        return $this->product;
    }
}
