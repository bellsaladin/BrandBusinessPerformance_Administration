<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Localisation
 *
 * @ORM\Table(name="localisation", indexes={@ORM\Index(name="fk_localisation_pdv_idx", columns={"pdv_id"}), @ORM\Index(name="fk_localisation_sfo_idx", columns={"sfo_id"}) })
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
     * @var \SFO
     *
     * @ORM\ManyToOne(targetEntity="SFO")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sfo_id", referencedColumnName="id")
     * })
     */
    private $sfo;

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
     * Set sfo
     *
     * @param \Acme\DemoBundle\Entity\SFO $sfo
     * @return Localisation
     */
    public function setSfo(\Acme\DemoBundle\Entity\SFO $sfo = null)
    {
        $this->sfo = $sfo;

        return $this;
    }

    /**
     * Get sfo
     *
     * @return \Acme\DemoBundle\Entity\SFO 
     */
    public function getSfo()
    {
        return $this->sfo;
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
