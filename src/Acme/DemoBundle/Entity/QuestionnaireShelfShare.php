<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionnaireShelfShare
 * 
 * @ORM\Table(name="questionnaireshelfshare") 
 * @ORM\Entity
 */
class QuestionnaireShelfShare
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $dateCreation;

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
     * @var \boolean
     *
     * @ORM\Column(name="valide", type="boolean", nullable=false)
     */
    private $valide;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbrLignesTraitees", type="integer", nullable=false)
     */
    private $nbrLignesTraitees;

    /**
     * @var float
     *
     * @ORM\Column(name="tempsRemplissage", type="float", nullable=false)
     */
    private $tempsRemplissage;

    /** @ORM\OneToMany(targetEntity="QuestionnaireShelfShareMarque", mappedBy="questionnaireShelfShare") */
    private $quantities;

    public function __construct()
    {
        $this->quantities = new ArrayCollection();
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
     * @return QuestionnaireShelfShare
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
     * Set localisation
     *
     * @param \Acme\DemoBundle\Entity\Localisation $localisation
     * @return QuestionnaireShelfShare
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
     * Set valide
     *
     * @param boolean $valide
     * @return Questionnaire
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * Get valide
     *
     * @return boolean 
     */
    public function getValide()
    {
        return $this->valide;
    }

    public function getNbrLignesTraitees()
    {
        return $this->nbrLignesTraitees;
    }

    public function getTempsRemplissage()
    {
        return $this->tempsRemplissage;
    }


    public function getQuantities(){
        return $this->quantities;
    }

    public function __toString()
    {
        return 'QuestionnaireShelfShare ' . $this->id;
    }

}
