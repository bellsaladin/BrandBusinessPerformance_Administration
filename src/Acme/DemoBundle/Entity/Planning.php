<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var \Pdv
     *
     * @ORM\ManyToMany(targetEntity="Pdv")     
     */
    private $pdv;


    public function __construct()
    {
        $this->pdv = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add pdv
     *
     * @param \Acme\DemoBundle\Entity\Pdv $pdv
     * @return Planning
     */
    public function addPdv(\Acme\DemoBundle\Entity\Pdv $pdv)
    {
        $this->pdv[] = $pdv;

        return $this;
    }

    /**
     * Remove pdv
     *
     * @param \Acme\DemoBundle\Entity\Pdv $pdv
     */
    public function removePdv(\Acme\DemoBundle\Entity\Pdv $pdv)
    {
        $this->pdv->removeElement($pdv);
    }

    /**
     * Get pdv
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPdv()
    {
        return $this->pdv;
    }

    public function __toString()
    {
        return 'planning';
    }
}
