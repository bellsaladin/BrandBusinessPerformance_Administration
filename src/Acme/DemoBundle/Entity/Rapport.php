<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rapport
 * 
 * @ORM\Table(name="rapport", indexes={@ORM\Index(name="fk_rapport_marqueachetee_idx", columns={"marqueachetee_id"}), @ORM\Index(name="fk_rapport_marquehabituelle_idx", columns={"marquehabituelle_id"}), @ORM\Index(name="fk_rapport_localisation_idx", columns={"localisation_id"})}) 
 * @ORM\Entity
 */
class Rapport
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
     * @var boolean
     *
     * @ORM\Column(name="achete", type="boolean", nullable=true)
     */
    private $achete;

    /**
     * @var \Trancheage
     *
     * @ORM\ManyToOne(targetEntity="Trancheage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="trancheage_id", referencedColumnName="id")
     * })
     */
    private $trancheage;

    /**
     * @var \Raisonachat
     *
     * @ORM\ManyToOne(targetEntity="Raisonachat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="raisonachat_id", referencedColumnName="id")
     * })
     */
    private $raisonachat;

     /**
     * @var \Raisonrefus
     *
     * @ORM\ManyToOne(targetEntity="Raisonrefus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="raisonrefus_id", referencedColumnName="id")
     * })
     */
    private $raisonrefus;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=1, nullable=true)
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="fidelite", type="string", length=45, nullable=true)
     */
    private $fidelite;

    /**
     * @var integer
     *
     * @ORM\Column(name="marquehabituelle_qte", type="integer", nullable=true)
     */
    private $marquehabituelleQte;

    /**
     * @var integer
     *
     * @ORM\Column(name="marqueachetee_qte", type="integer", nullable=true)
     */
    private $marqueacheteeQte;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tombola", type="boolean", nullable=true)
     */
    private $tombola;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=350, nullable=true)
     */
    private $commentaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @var \Cadeau
     *
     * @ORM\ManyToMany(targetEntity="Cadeau")     
     */
    private $cadeau;

    /**
     * @var \Localisation
     *
     * @ORM\ManyToOne(targetEntity="Localisation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="localisation_id", referencedColumnName="id")
     * })
     */
    private $localisation;

    /**
     * @var \Marque
     *
     * @ORM\ManyToOne(targetEntity="Marque")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="marqueachetee_id", referencedColumnName="id")
     * })
     */
    private $marqueachetee;

    /**
     * @var \Marque
     *
     * @ORM\ManyToOne(targetEntity="Marque")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="marquehabituelle_id", referencedColumnName="id")
     * })
     */
    private $marquehabituelle;


    public function __construct()
    {
        $this->cadeau = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set achete
     *
     * @param boolean $achete
     * @return Rapport
     */
    public function setAchete($achete)
    {
        $this->achete = $achete;

        return $this;
    }

    /**
     * Get achete
     *
     * @return boolean 
     */
    public function getAchete()
    {
        return $this->achete;
    }

    /**
     * Set trancheage
     *
     * @param \Acme\DemoBundle\Entity\Trancheage $trancheage
     * @return Rapport
     */
    public function setTrancheAge(\Acme\DemoBundle\Entity\Trancheage $trancheage = null)
    {
        $this->trancheage = $trancheage;

        return $this;
    }

    /**
     * Get trancheage
     *
     * @return \Acme\DemoBundle\Entity\Trancheage 
     */
    public function getTrancheage()
    {
        return $this->trancheage;
    }

    /**
     * Set raisonachat
     *
     * @param \Acme\DemoBundle\Entity\Raisonachat $raisonachat
     * @return Rapport
     */
    public function setRaisonachat(\Acme\DemoBundle\Entity\Raisonachat $raisonachat = null)
    {
        $this->raisonachat = $raisonachat;

        return $this;
    }

    /**
     * Get raisonachat
     *
     * @return \Acme\DemoBundle\Entity\raisonachat 
     */
    public function getRaisonachat()
    {
        return $this->raisonachat;
    }

    /**
     * Set raisonrefus
     *
     * @param \Acme\DemoBundle\Entity\Raisonrefus $raisonrefus
     * @return Rapport
     */
    public function setRaisonrefus(\Acme\DemoBundle\Entity\Raisonrefus $raisonrefus = null)
    {
        $this->raisonrefus = $raisonrefus;

        return $this;
    }

    /**
     * Get raisonrefus
     *
     * @return \Acme\DemoBundle\Entity\raisonrefus 
     */
    public function getRaisonrefus()
    {
        return $this->raisonrefus;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     * @return Rapport
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string 
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set fidelite
     *
     * @param string $fidelite
     * @return Rapport
     */
    public function setFidelite($fidelite)
    {
        $this->fidelite = $fidelite;

        return $this;
    }

    /**
     * Get fidelite
     *
     * @return string 
     */
    public function getFidelite()
    {
        return $this->fidelite;
    }

    /**
     * Set marquehabituelleQte
     *
     * @param integer $marquehabituelleQte
     * @return Rapport
     */
    public function setMarquehabituelleQte($marquehabituelleQte)
    {
        $this->marquehabituelleQte = $marquehabituelleQte;

        return $this;
    }

    /**
     * Get marquehabituelleQte
     *
     * @return integer 
     */
    public function getMarquehabituelleQte()
    {
        return $this->marquehabituelleQte;
    }

    /**
     * Set marqueacheteeQte
     *
     * @param integer $marqueacheteeQte
     * @return Rapport
     */
    public function setMarqueacheteeQte($marqueacheteeQte)
    {
        $this->marqueacheteeQte = $marqueacheteeQte;

        return $this;
    }

    /**
     * Get marqueacheteeQte
     *
     * @return integer 
     */
    public function getMarqueacheteeQte()
    {
        return $this->marqueacheteeQte;
    }

    /**
     * Set tombola
     *
     * @param boolean $tombola
     * @return Rapport
     */
    public function setTombola($tombola)
    {
        $this->tombola = $tombola;

        return $this;
    }

    /**
     * Get tombola
     *
     * @return boolean 
     */
    public function getTombola()
    {
        return $this->tombola;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Rapport
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

    /**
     * Add cadeau
     *
     * @param \Acme\DemoBundle\Entity\Cadeau $cadeau
     * @return Rapport
     */
    public function addCadeau(\Acme\DemoBundle\Entity\Cadeau $cadeau)
    {
        $this->cadeau[] = $cadeau;

        return $this;
    }

    /**
     * Remove cadeau
     *
     * @param \Acme\DemoBundle\Entity\Cadeau $cadeau
     */
    public function removeCadeau(\Acme\DemoBundle\Entity\Cadeau $cadeau)
    {
        $this->cadeau->removeElement($cadeau);
    }

    /**
     * Get cadeau
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCadeau()
    {
        return $this->cadeau;
    }

    /**
     * Set localisation
     *
     * @param \Acme\DemoBundle\Entity\Localisation $localisation
     * @return Rapport
     */
    public function setLocalisation(\Acme\DemoBundle\Entity\Localisation $localisation = null)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get localisation
     *
     * @return \Acme\DemoBundle\Entity\Localisation 
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set marqueachetee
     *
     * @param \Acme\DemoBundle\Entity\Marque $marqueachetee
     * @return Rapport
     */
    public function setMarqueachetee(\Acme\DemoBundle\Entity\Marque $marqueachetee = null)
    {
        $this->marqueachetee = $marqueachetee;

        return $this;
    }

    /**
     * Get marqueachetee
     *
     * @return \Acme\DemoBundle\Entity\Marque 
     */
    public function getMarqueachetee()
    {
        return $this->marqueachetee;
    }

    /**
     * Set marquehabituelle
     *
     * @param \Acme\DemoBundle\Entity\Marque $marquehabituelle
     * @return Rapport
     */
    public function setMarquehabituelle(\Acme\DemoBundle\Entity\Marque $marquehabituelle = null)
    {
        $this->marquehabituelle = $marquehabituelle;

        return $this;
    }

    /**
     * Get marquehabituelle
     *
     * @return \Acme\DemoBundle\Entity\Marque 
     */
    public function getMarquehabituelle()
    {
        return $this->marquehabituelle;
    }

    public function __toString()
    {
        return 'Rapport ' . $this->id;
    }

}
