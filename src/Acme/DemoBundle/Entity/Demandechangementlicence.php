<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Demandechangementlicence
 *
 * @ORM\Table(name="demandechangementlicence", indexes={@ORM\Index(name="fk_demandechangementlicence_localisation_idx", columns={"localisation_id"})})
 * @ORM\Entity
 */
class Demandechangementlicence
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
     * @ORM\Column(name="licence", type="integer", nullable=true)
     */
    private $licence;

    /**
     * @var string
     *
     * @ORM\Column(name="motif", type="string", length=200, nullable=true)
     */
    private $motif;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set licence
     *
     * @param integer $licence
     * @return Demandechangementlicence
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
     * Set motif
     *
     * @param string $motif
     * @return Demandechangementlicence
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * Set localisation
     *
     * @param \Acme\DemoBundle\Entity\Localisation $localisation
     * @return Demandechangementlicence
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

    public function __toString()
    {
        return $this->motif;
    }
}
