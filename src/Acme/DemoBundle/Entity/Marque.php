<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marque
 *
 * @ORM\Table(name="marque")
 * @ORM\Entity
 */
class Marque
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=100, nullable=true)
     */
    private $libelle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @var \categoriesProduits
     * Explication : Catégories des produits dans lesquelles opère cette marque
     *
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Classification\Category")
     */
    private $categoriesProduits;


    public function __construct()
    {
        $this->categoriesProduits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Marque
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

     /**
     * Add categoriesProduits
     *
     * @param \AppBundle\Entity\Classification\Category $categoriesProduits
     * @return Planning
     */
    public function addCategoriesProduits(\AppBundle\Entity\Classification\Category $categorie)
    {
        $this->categoriesProduits[] = $categorie;

        return $this;
    }

    /**
     * Remove categoriesProduits
     *
     * @param \AppBundle\Entity\Classification\Category $categorie
     */
    public function removeCategoriesProduits(\AppBundle\Entity\Classification\Category $categorie)
    {
        $this->categoriesProduits->removeElement($categorie);
    }

    /**
     * Get categoriesProduits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategoriesProduits()
    {
        return $this->categoriesProduits;
    }


    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Marque
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    public function __toString()
    {
        return $this->libelle;
    }
}
