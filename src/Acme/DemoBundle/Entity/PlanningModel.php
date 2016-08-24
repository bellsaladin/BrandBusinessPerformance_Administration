<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Planning
 *
 * @ORM\Table(name="planningmodel" )})
 * @ORM\Entity
 */
class PlanningModel
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
     * @var \SFO
     *
     * @ORM\ManyToOne(targetEntity="SFO")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sfo_id", referencedColumnName="id")
     * })
     */
    private $sfo;

    /**
     * @ORM\OneToMany(targetEntity="PlanningModelVisite", cascade={"persist", "remove"}, orphanRemoval=True, mappedBy="planningmodel")
     * @Assert\Valid
     */
    private $visites;


    public function __construct()
    {
        $this->visites = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->visites->add(new Visite()); // !important  : never remove or planning visites (rows) loading after sfo selection won't work
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
     * @param \Acme\DemoBundle\Entity\PlanningModelVisite $visite
     * @return Planning
     */
    public function addVisite(\Acme\DemoBundle\Entity\PlanningModelVisite $visite)
    {
        $visite->setPlanningmodel($this);

        $this->visites->add($visite);

        return $this;
    }

    /**
     * Remove visite
     *
     * @param \Acme\DemoBundle\Entity\PlanningModelVisite $visites
     */
    public function removeVisite(\Acme\DemoBundle\Entity\PlanningModelVisite $visite)
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
        return 'modèle de planning';
    }
}
