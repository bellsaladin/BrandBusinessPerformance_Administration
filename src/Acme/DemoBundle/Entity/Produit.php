<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity
 */
class Produit
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
     * @ORM\Column(name="sku", type="string", length=100, nullable=true)
     */
    private $sku;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=150, nullable=true)
     */
    private $libelle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

    /**
    * @var \categorie
    *
    * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Classification\Category",cascade={"persist"})
    * @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
    * })
    */
    private $categorie;

    /**
     *
     * @ORM\Column(name="couleur", type="string", length=100, nullable=true)
     */
    private $couleur;

    /**
     *
     * @ORM\Column(name="smart", type="boolean", nullable=true)
     */
    private $smart;

    /**
     *
     * @ORM\Column(name="type", type="string", length=100, nullable=true)
     */
    private $type;

    /**
     *
     * @ORM\Column(name="serie", type="string", length=100, nullable=true)
     */
    private $serie;

    /**
     *
     * @ORM\Column(name="volume", type="float", nullable=true)
     */
    private $volume;

    /**
     *
     * @ORM\Column(name="capacite", type="float", nullable=true)
     */
    private $capacite;

    /**
     *
     * @ORM\Column(name="inch", type="float", nullable=true)
     */
    private $inch;

    /**
     *
     * @ORM\Column(name="garantie", type="float", nullable=true)
     */
    private $garantie;

    /**
     *
     * @ORM\Column(name="typeCompresseur", type="string", length=100, nullable=true)
     */
    private $typeCompresseur;

    /**
     *
     * @ORM\Column(name="typeMoteur", type="string", length=100, nullable=true)
     */
    private $typeMoteur;

    /**
     *
     * @ORM\Column(name="resolution", type="string", length=100, nullable=true)
     */
    private $resolution;

    /**
     *
     * @ORM\Column(name="modele", type="string", length=100, nullable=true)
     */
    private $modele;

    /**
     *
     * @ORM\Column(name="dimension", type="string", length=100, nullable=true)
     */
    private $dimension;

    /**
    * @var \marque
    *
    * @ORM\ManyToOne(targetEntity="Marque",cascade={"persist"})
    * @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="marque_id", referencedColumnName="id")
    * })
    */
    private $marque;

    /**
     *
     * @ORM\Column(name="prix", type="float", nullable=true)
     */
    private $prix;

    /**
     *
     * @ORM\Column(name="prixPromotionnel", type="float", nullable=true)
     */
    private $prixPromotionnel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $dateCreation;


    public function __construct()
    {
        $this->enabled = 1;
        $this->date = new \DateTime();
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
     * Set sku
     *
     * @param string $sku
     * @return Produit
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string 
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Produit
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
     * Set categorie
     *
     * @param \AppBundle\Entity\Classification\Category $categorie
     */
    public function setCategorie(\AppBundle\Entity\Classification\Category $categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * Get categorie
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }


    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Produit
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

    /**
     * Set couleur
     *
     * @param string $couleur
     * @return Produit
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return string 
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set smart
     *
     * @param boolean $smart
     * @return Produit
     */
    public function setSmart($smart)
    {
        $this->smart = $smart;

        return $this;
    }

    /**
     * Get smart
     *
     * @return boolean 
     */
    public function getSmart()
    {
        return $this->smart;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Produit
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set serie
     *
     * @param string $serie
     * @return Produit
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return string 
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set volume
     *
     * @param float $volume
     * @return Produit
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume
     *
     * @return float 
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Set capacite
     *
     * @param float $capacite
     * @return Produit
     */
    public function setCapacite($capacite)
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * Get capacite
     *
     * @return float 
     */
    public function getCapacite()
    {
        return $this->capacite;
    }

    /**
     * Set inch
     *
     * @param float $inch
     * @return Produit
     */
    public function setInch($inch)
    {
        $this->inch = $inch;

        return $this;
    }

    /**
     * Get inch
     *
     * @return float 
     */
    public function getInch()
    {
        return $this->inch;
    }

    /**
     * Set garantie
     *
     * @param String $garantie
     * @return Produit
     */
    public function setGarantie($garantie)
    {
        $this->garantie = $garantie;

        return $this;
    }

    /**
     * Get garantie
     *
     * @return \garantie 
     */
    public function getGarantie()
    {
        return $this->garantie;
    }

    /**
     * Set typeCompresseur
     *
     * @param string $typeCompresseur
     * @return Produit
     */
    public function setTypeCompresseur($typeCompresseur)
    {
        $this->typeCompresseur = $typeCompresseur;

        return $this;
    }

    /**
     * Get typeCompresseur
     *
     * @return string 
     */
    public function getTypeCompresseur()
    {
        return $this->typeCompresseur;
    }

    /**
     * Set typeMoteur
     *
     * @param string $typeMoteur
     * @return Produit
     */
    public function setTypeMoteur($typeMoteur)
    {
        $this->typeMoteur = $typeMoteur;

        return $this;
    }

    /**
     * Get typeMoteur
     *
     * @return string 
     */
    public function getTypeMoteur()
    {
        return $this->typeMoteur;
    }

    /**
     * Set resolution
     *
     * @param string $resolution
     * @return Produit
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return string 
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set modele
     *
     * @param string $modele
     * @return Produit
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return string 
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set dimension
     *
     * @param string $dimension
     * @return Produit
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * Get dimension
     *
     * @return string 
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Set prix
     *
     * @param float $prix
     * @return Produit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set prixPromotionnel
     *
     * @param float $prixPromotionnel
     * @return Produit
     */
    public function setPrixPromotionnel($prixPromotionnel)
    {
        $this->prixPromotionnel = $prixPromotionnel;

        return $this;
    }

    /**
     * Get prixPromotionnel
     *
     * @return float 
     */
    public function getPrixPromotionnel()
    {
        return $this->prixPromotionnel;
    }

    /**
     * Set marque
     *
     * @param \Acme\DemoBundle\Entity\Marque $marque
     * @return Produit
     */
    public function setMarque(\Acme\DemoBundle\Entity\Marque $marque = null)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return \Acme\DemoBundle\Entity\Marque 
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Rapport
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

}
