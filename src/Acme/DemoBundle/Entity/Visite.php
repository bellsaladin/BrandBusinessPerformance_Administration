<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visite
 *
 * @ORM\Table(name="visite")
 * @ORM\Entity
 */
class Visite
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
     * @var integer
     *
     * @ORM\Column(name="dayOfWeek", type="integer", nullable=false)
     */
    private $dayOfWeek;

    /**
     * @var \pdv
     *
     * @ORM\ManyToOne(targetEntity="Pdv",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pdv_id", referencedColumnName="id")
     * })
     */
    private $pdv;

    /**
     * @var \Planning
     * @ORM\ManyToOne(targetEntity="Planning", inversedBy="visites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $planning;

    /**
     * @var boolean
     *
     * @ORM\Column(name="done", type="boolean", nullable=true)
     */
    private $done;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", nullable=true)
     */
    private $commentaire;

    public function __construct()
    {
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
     * Set dayOfWeek
     *
     * @param integer $dayOfWeek
     * @return Visite
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }

    /**
     * Get dayOfWeek
     *
     * @return integer
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * Set pdv
     *
     * @param Pdv $pdv
     * @return Visite
     */
    public function setPdv(\Acme\DemoBundle\Entity\Pdv $pdv= null)
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
     * Set planning
     *
     * @param Planning $planning
     * @return Visite
     */
    public function setPlanning(\Acme\DemoBundle\Entity\Planning $planning= null)
    {
        $this->planning = $planning;

        return $this;
    }

    /**
     * Get planning
     *
     * @return \Acme\DemoBundle\Entity\Planning
     */
    public function getPlanning()
    {
        return $this->planning;
    }

    /**
     * Set done
     *
     * @param boolean $done
     * @return Marque
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done
     *
     * @return boolean
     */
    public function getDone()
    {
        return $this->done;
    }


    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Visite
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

    public function __toString(){
        $dowMap = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');

        return $dowMap[$this->dayOfWeek] ." visite au PDV '" . $this->pdv . "'";
    }
}
