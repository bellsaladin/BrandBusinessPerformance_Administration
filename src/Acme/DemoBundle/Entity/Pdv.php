<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pdv
 *
 * @ORM\Table(name="pdv")
 * @ORM\Entity
 */
class Pdv
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
     * @ORM\Column(name="nom", type="string", length=70, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="rayon", type="float", precision=10, scale=0, nullable=true)
     */
    private $rayon;

    /**
     * @var integer
     *
     * @ORM\Column(name="licence", type="integer", nullable=true)
     */
    private $licence;

    
    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=60, nullable=true)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="secteur", type="string", length=60, nullable=true)
     */
    private $secteur;


    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=100, nullable=true)
     */
    private $adresse;

    /**
     * @var boolean
     *
     * @ORM\Column(name="externe", type="boolean", nullable=true)
     */
    private $externe;

    /**
     * @var \POI
     *
     * @ORM\ManyToMany(targetEntity="POI")     
     */
    private $poi;


    /**
     * @var \FosUserUser
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\User\User",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_createur_id", referencedColumnName="id")
     * })
     */
    private $userCreateur;

    public function __construct()
    {
        $this->poi = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Pdv
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Pdv
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Pdv
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Pdv
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set rayon
     *
     * @param float $rayon
     * @return Pdv
     */
    public function setRayon($rayon)
    {
        $this->rayon = $rayon;

        return $this;
    }

    /**
     * Get rayon
     *
     * @return float 
     */
    public function getRayon()
    {
        return $this->rayon;
    }

    /**
     * Set licence
     *
     * @param integer $licence
     * @return Pdv
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;

        return $this;
    }

    /**
     * Get licence
     *
     * @return integer 
     */
    public function getLicence()
    {
        return $this->licence;
    }


    /**
     * Set ville
     *
     * @param string $ville
     * @return Pdv
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set secteur
     *
     * @param string $secteur
     * @return Pdv
     */
    public function setSecteur($secteur)
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * Get secteur
     *
     * @return string 
     */
    public function getSecteur()
    {
        return $this->secteur;
    }


    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Pdv
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set externe
     *
     * @param string $externe
     * @return Pdv
     */
    public function setExterne($externe)
    {
        $this->externe = $externe;

        return $this;
    }

    /**
     * Get externe
     *
     * @return string 
     */
    public function getExterne()
    {
        return $this->externe;
    }

    /**
     * Add poi
     *
     * @param \Acme\DemoBundle\Entity\POI $poi
     * @return PDV
     */
    public function addPous(\Acme\DemoBundle\Entity\POI $poi)
    {
        $this->poi[] = $poi;
        return $this;
    }

    /**
     * Remove poi
     *
     * @param \Acme\DemoBundle\Entity\POI $Poi
     */
    public function removePous(\Acme\DemoBundle\Entity\POI $poi)
    {
        $this->poi->removeElement($poi);
    }

    /**
     * Get poi
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPoi()
    {
        return $this->poi;
    }

    public function __toString(){
        return $this->nom ;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User\User $user
     * @return Animateur
     */
    public function setUserCreateur(\AppBundle\Entity\User\User $user = null)
    {
        $this->userCreateur = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User\User
     */
    public function getUserCreateur()
    {
        return $this->userCreateur;
    }
}
