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

    /**
     * @var string
     *
     * @ORM\Column(name="outletName", type="string", length=45, nullable=true)
     */
    private $outletname;

    /**
     * @var string
     *
     * @ORM\Column(name="channel", type="string", length=45, nullable=true)
     */
    private $channel;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="family", type="string", length=45, nullable=true)
     */
    private $family;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=45, nullable=true)
     */
    private $category;

    /**
     * @var \Sfo
     *
     * @ORM\ManyToOne(targetEntity="\Acme\DemoBundle\Entity\SFO",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sfo_id", referencedColumnName="id")
     * })
     */
    private $sfo;

    /**
     * @var string
     *
     * @ORM\Column(name="week", type="string", length=45, nullable=true)
     */
    private $week;

    /**
     * @var string
     *
     * @ORM\Column(name="jourVisite", type="string", length=45, nullable=true)
     */
    private $jourvisite;

    /**
     * @var string
     *
     * @ORM\Column(name="tempsVisite", type="string", length=45, nullable=true)
     */
    private $tempsvisite;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=300, nullable=true)
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="collabore", type="string", length=45, nullable=true)
     */
    private $collabore;

    /**
     * @var string
     *
     * @ORM\Column(name="incentive", type="string", length=45, nullable=true)
     */
    private $incentive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="elimine", type="boolean", nullable=true)
     */
    private $elimine;

    /**
     * @var string
     *
     * @ORM\Column(name="managerPhone", type="string", length=45, nullable=true)
     */
    private $managerphone;

    /**
     * @var string
     *
     * @ORM\Column(name="managerFullName", type="string", length=45, nullable=true)
     */
    private $managerfullname;

    /**
     * @var string
     *
     * @ORM\Column(name="ownerPhone", type="string", length=45, nullable=true)
     */
    private $ownerphone;

    /**
     * @var string
     *
     * @ORM\Column(name="ownerFullName", type="string", length=45, nullable=true)
     */
    private $ownerfullname;

    /**
     * @var float
     *
     * @ORM\Column(name="size", type="float", precision=10, scale=0, nullable=true)
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="incentiveStartWeek", type="string", length=45, nullable=true)
     */
    private $incentivestartweek;

    /**
     * @var string
     *
     * @ORM\Column(name="dataStartWeek", type="string", length=45, nullable=true)
     */
    private $datastartweek;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $dateCreation;


    public function __construct()
    {
        $this->poi = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
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
     * @param boolean $externe
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
     * @return boolean 
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

    /**
     * Set userCreateurId
     *
     * @param integer $userCreateurId
     * @return Pdv
     */
    public function setUserCreateurId($userCreateurId)
    {
        $this->userCreateurId = $userCreateurId;

        return $this;
    }

    /**
     * Get userCreateurId
     *
     * @return integer 
     */
    public function getUserCreateurId()
    {
        return $this->userCreateurId;
    }

    /**
     * Set outletname
     *
     * @param string $outletname
     * @return Pdv
     */
    public function setOutletname($outletname)
    {
        $this->outletname = $outletname;

        return $this;
    }

    /**
     * Get outletname
     *
     * @return string 
     */
    public function getOutletname()
    {
        return $this->outletname;
    }

    /**
     * Set channel
     *
     * @param string $channel
     * @return Pdv
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string 
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Pdv
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set family
     *
     * @param string $family
     * @return Pdv
     */
    public function setFamily($family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return string 
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Pdv
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set sfo
     *
     * @param integer $sfo
     * @return Pdv
     */
    public function setSfo($sfo)
    {
        $this->sfo = $sfo;

        return $this;
    }

    /**
     * Get sfo
     *
     * @return integer 
     */
    public function getSfo()
    {
        return $this->sfo;
    }

    /**
     * Set week
     *
     * @param string $week
     * @return Pdv
     */
    public function setWeek($week)
    {
        $this->week = $week;

        return $this;
    }

    /**
     * Get week
     *
     * @return string 
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * Set jourvisite
     *
     * @param string $jourvisite
     * @return Pdv
     */
    public function setJourvisite($jourvisite)
    {
        $this->jourvisite = $jourvisite;

        return $this;
    }

    /**
     * Get jourvisite
     *
     * @return string 
     */
    public function getJourvisite()
    {
        return $this->jourvisite;
    }

    /**
     * Set tempsvisite
     *
     * @param string $tempsvisite
     * @return Pdv
     */
    public function setTempsvisite($tempsvisite)
    {
        $this->tempsvisite = $tempsvisite;

        return $this;
    }

    /**
     * Get tempsvisite
     *
     * @return string 
     */
    public function getTempsvisite()
    {
        return $this->tempsvisite;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Pdv
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set collabore
     *
     * @param string $collabore
     * @return Pdv
     */
    public function setCollabore($collabore)
    {
        $this->collabore = $collabore;

        return $this;
    }

    /**
     * Get collabore
     *
     * @return string 
     */
    public function getCollabore()
    {
        return $this->collabore;
    }

    /**
     * Set incentive
     *
     * @param string $incentive
     * @return Pdv
     */
    public function setIncentive($incentive)
    {
        $this->incentive = $incentive;

        return $this;
    }

    /**
     * Get incentive
     *
     * @return string 
     */
    public function getIncentive()
    {
        return $this->incentive;
    }

    /**
     * Set elimine
     *
     * @param boolean $elimine
     * @return Pdv
     */
    public function setElimine($elimine)
    {
        $this->elimine = $elimine;

        return $this;
    }

    /**
     * Get elimine
     *
     * @return boolean 
     */
    public function getElimine()
    {
        return $this->elimine;
    }

    /**
     * Set managerphone
     *
     * @param string $managerphone
     * @return Pdv
     */
    public function setManagerphone($managerphone)
    {
        $this->managerphone = $managerphone;

        return $this;
    }

    /**
     * Get managerphone
     *
     * @return string 
     */
    public function getManagerphone()
    {
        return $this->managerphone;
    }

    /**
     * Set managerfullname
     *
     * @param string $managerfullname
     * @return Pdv
     */
    public function setManagerfullname($managerfullname)
    {
        $this->managerfullname = $managerfullname;

        return $this;
    }

    /**
     * Get managerfullname
     *
     * @return string 
     */
    public function getManagerfullname()
    {
        return $this->managerfullname;
    }

    /**
     * Set ownerphone
     *
     * @param string $ownerphone
     * @return Pdv
     */
    public function setOwnerphone($ownerphone)
    {
        $this->ownerphone = $ownerphone;

        return $this;
    }

    /**
     * Get ownerphone
     *
     * @return string 
     */
    public function getOwnerphone()
    {
        return $this->ownerphone;
    }

    /**
     * Set ownerfullname
     *
     * @param string $ownerfullname
     * @return Pdv
     */
    public function setOwnerfullname($ownerfullname)
    {
        $this->ownerfullname = $ownerfullname;

        return $this;
    }

    /**
     * Get ownerfullname
     *
     * @return string 
     */
    public function getOwnerfullname()
    {
        return $this->ownerfullname;
    }

    /**
     * Set size
     *
     * @param float $size
     * @return Pdv
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return float 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set incentivestartweek
     *
     * @param string $incentivestartweek
     * @return Pdv
     */
    public function setIncentivestartweek($incentivestartweek)
    {
        $this->incentivestartweek = $incentivestartweek;

        return $this;
    }

    /**
     * Get incentivestartweek
     *
     * @return string 
     */
    public function getIncentivestartweek()
    {
        return $this->incentivestartweek;
    }

    /**
     * Set datastartweek
     *
     * @param string $datastartweek
     * @return Pdv
     */
    public function setDatastartweek($datastartweek)
    {
        $this->datastartweek = $datastartweek;

        return $this;
    }

    /**
     * Get datastartweek
     *
     * @return string 
     */
    public function getDatastartweek()
    {
        return $this->datastartweek;
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


    public function __toString(){
        return $this->nom ;
    }

}