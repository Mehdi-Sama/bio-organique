<?php
namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="panier")
 */
class Panier
{
    /** Plusieurs ligne de la table panier peuvent correspondre à un seul user
     * @ORM\ManyToOne(targetEntity="User", inversedBy="panier")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */ 
    private User $user;

    /** Plusieurs ligne de la table panier peuvent correspondre à un seul user
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="panier")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */ 
    private Product $product;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", length="10")
     * @var integer
     */
    private int $qte;

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
     * Set qte.
     *
     * @param int $qte
     *
     * @return Panier
     */
    public function setQte($qte)
    {
        $this->qte = $qte;

        return $this;
    }

    /**
     * Get qte.
     *
     * @return int
     */
    public function getQte()
    {
        return $this->qte;
    }

    /**
     * Set user.
     *
     * @param \Model\Entity\User|null $user
     *
     * @return Panier
     */
    public function setUser(\Model\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Model\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set product.
     *
     * @param \Model\Entity\Product|null $product
     *
     * @return Panier
     */
    public function setProduct(\Model\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return \Model\Entity\Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }
}
