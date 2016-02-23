<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Localisation
 *
 * @ORM\Table(name="localisation", indexes={@ORM\Index(name="fk_localisation_pdv_idx", columns={"pdv_id"}), @ORM\Index(name="fk_localisation_animateur_idx", columns={"animateur_id"}), @ORM\Index(name="fk_localisation_superviseur_idx", columns={"superviseur_id"})})
 * @ORM\Entity
 */
class Localisation
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
     * @ORM\Column(name="imagefilename", type="string", length=255, nullable=true)
     */
    private $imagefilename;

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
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @var \Superviseur
     *
     * @ORM\ManyToOne(targetEntity="Superviseur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="superviseur_id", referencedColumnName="id")
     * })
     */
    private $superviseur;

    /**
     * @var \Animateur
     *
     * @ORM\ManyToOne(targetEntity="Animateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="animateur_id", referencedColumnName="id")
     * })
     */
    private $animateur;

    /**
     * @var \Pdv
     *
     * @ORM\ManyToOne(targetEntity="Pdv")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pdv_id", referencedColumnName="id")
     * })
     */
    private $pdv;

     /**
     * @ORM\OneToMany(targetEntity="Rapport", mappedBy="localisation")
     */
    public $rapport;

    

    public function __construct()
    {
        $this->rapport = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set imagefilename
     *
     * @param string $imagefilename
     * @return Localisation
     */
    public function setImagefilename($imagefilename)
    {
        $this->imagefilename = $imagefilename;

        return $this;
    }

    /**
     * Get imagefilename
     *
     * @return string 
     */
    public function getImagefilename()
    {
        return $this->imagefilename;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Localisation
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
     * @return Localisation
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Localisation
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

    /**
     * Set superviseur
     *
     * @param \Acme\DemoBundle\Entity\Superviseur $superviseur
     * @return Localisation
     */
    public function setSuperviseur(\Acme\DemoBundle\Entity\Superviseur $superviseur = null)
    {
        $this->superviseur = $superviseur;

        return $this;
    }

    /**
     * Get superviseur
     *
     * @return \Acme\DemoBundle\Entity\Superviseur 
     */
    public function getSuperviseur()
    {
        return $this->superviseur;
    }

    /**
     * Set animateur
     *
     * @param \Acme\DemoBundle\Entity\Animateur $animateur
     * @return Localisation
     */
    public function setAnimateur(\Acme\DemoBundle\Entity\Animateur $animateur = null)
    {
        $this->animateur = $animateur;

        return $this;
    }

    /**
     * Get animateur
     *
     * @return \Acme\DemoBundle\Entity\Animateur 
     */
    public function getAnimateur()
    {
        return $this->animateur;
    }

    /**
     * Set pdv
     *
     * @param \Acme\DemoBundle\Entity\Pdv $pdv
     * @return Localisation
     */
    public function setPdv(\Acme\DemoBundle\Entity\Pdv $pdv = null)
    {
        $this->pdv = $pdv;

        return $this;
    }

    /**
     * Get pdv
     *
     * @return \Acme\DemoBundle\Entity\Pdv 
     */
    public function getPdv()
    {
        return $this->pdv;
    }


    /**
     * Add rapport
     *
     * @param \Acme\DemoBundle\Entity\Rapport $rapport
     * @return Rapport
     */
    public function addRapport(\Acme\DemoBundle\Entity\Rapport $rapport)
    {
        $this->rapport[] = $rapport;

        return $this;
    }

    /**
     * Get rapport
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRapport()
    {
        return $this->rapport;
    }


    public function __toString()
    {
        return 'Localisation ' .$this->id;
    }
}
