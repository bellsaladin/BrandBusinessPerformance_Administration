<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Planning
 * 
 * @ORM\Table(name="planning", indexes={@ORM\Index(name="fk_planning_animateur_idx", columns={"animateur_id"})}) 
 * @ORM\Entity
 */
class Planning
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
     * @var \Date
     *
     * @ORM\Column(name="datedebut_semaine", type="date", nullable=true)
     */
    private $dateDebutSemaine;

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
     * @ORM\OneToMany(targetEntity="Visite", cascade={"persist", "remove"}, orphanRemoval=True, mappedBy="planning")
     * @Assert\Valid
     */
    private $visites;


    public function __construct()
    {
        $this->visites = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set sfo
     *
     * @param \Acme\DemoBundle\Entity\SFO $sfo
     * @return Planning
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
     * Set dateDebutSemaine
     *
     * @param \Date $dateDebutSemaine
     * @return Planning
     */
    public function setDateDebutSemaine($dateDebutSemaine)
    {
        $this->dateDebutSemaine = $dateDebutSemaine;

        return $this;
    }

    /**
     * Get dateDebutSemaine
     *
     * @return \Date 
     */
    public function getDateDebutSemaine()
    {
        return $this->dateDebutSemaine;
    }

    /**
     * @param Visites[] $visites
     */
    public function setVisites($visites)
    {
        $this->visites = new ArrayCollection();

        foreach ($visites as $visite) {
            $this->addVisite($visite);
        }
    }

    /**
     * Add visite
     *
     * @param \Acme\DemoBundle\Entity\Visite $visite
     * @return Planning
     */
    public function addVisite(\Acme\DemoBundle\Entity\Visite $visite)
    {
        $visite->setPlanning($this);

        $this->visites->add($visite);

        return $this;
    }

    /**
     * Remove visite
     *
     * @param \Acme\DemoBundle\Entity\Visite $visites
     */
    public function removeVisite(\Acme\DemoBundle\Entity\Visite $visite)
    {
        $this->visites->removeElement($visite);
    }

    /**
     * Get visites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVisites()
    {
        return $this->visites;
    }

    public function __toString()
    {
        return 'planning';
    }
}
