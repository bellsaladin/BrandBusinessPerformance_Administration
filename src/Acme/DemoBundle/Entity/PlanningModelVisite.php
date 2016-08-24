<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visite
 *
 * @ORM\Table(name="planningmodel_visite")
 * @ORM\Entity
 */
class PlanningModelVisite
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
     * @var \PlanningModel
     * @ORM\ManyToOne(targetEntity="PlanningModel", inversedBy="visites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $planningmodel;


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
    public function setPlanningmodel(\Acme\DemoBundle\Entity\PlanningModel $planningmodel= null)
    {
        $this->planningmodel = $planningmodel;

        return $this;
    }

    /**
     * Get planning
     *
     * @return \Acme\DemoBundle\Entity\PlanningModel
     */
    public function getPlanningmodel()
    {
        return $this->planningmodel;
    }

    public function __toString(){
        $dowMap = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');

        return $dowMap[$this->dayOfWeek] ." visite au PDV '" . $this->pdv . "'";
    }
}
