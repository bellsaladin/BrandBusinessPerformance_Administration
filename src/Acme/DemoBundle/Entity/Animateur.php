<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Animateur
 *
 * @ORM\Table(name="animateur", indexes={@ORM\Index(name="fk_animateur_user_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class Animateur
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
     * @ORM\Column(name="nom", type="string", length=90, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=90, nullable=true)
     */
    private $prenom;


    /**
     * @var \Superviseur
     *
     * @ORM\ManyToOne(targetEntity="Superviseur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="superviseur_id", referencedColumnName="id")
     * })
     */
    private $superviseur;

    /**
     * @var \FosUserUser
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\User\User",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function __construct()
    {
        $this->user = new \AppBundle\Entity\User\User();
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
     * Set nom
     *
     * @param string $nom
     * @return Animateur
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
     * Set prenom
     *
     * @param string $prenom
     * @return Animateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

   /**
     * Set superviseur
     *
     * @param \Acme\DemoBundle\Entity\Superviseur $superviseur
     * @return Rapport
     */
    public function setSuperviseur(\Acme\DemoBundle\Entity\Superviseur $superviseur = null)
    {
        $this->superviseur = $superviseur;

        return $this;
    }

    /**
     * Get superviseur
     *
     * @return \Acme\DemoBundle\Entity\Superviseur
     */
    public function getSuperviseur()
    {
        return $this->superviseur;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User\User $user
     * @return Animateur
     */
    public function setUser(\AppBundle\Entity\User\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __toString(){
        return $this->prenom . " " . $this->nom;
    }
}
