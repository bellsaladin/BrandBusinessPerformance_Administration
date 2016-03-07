<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commercial
 *
 * @ORM\Table(name="commercial")
 * @ORM\Entity
 */
class Commercial
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
     * @ORM\Column(name="nom", type="string", length=45, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=45, nullable=true)
     */
    private $prenom;

    /**
     * @var \boolean
     *
     * @ORM\Column(name="directeur", type="boolean", nullable=false)
     */
    private $directeur;

    /**
     * @var \PDV
     *
     * @ORM\ManyToMany(targetEntity="Pdv")     
     */
    private $pdv;

    /**
    * @var \User
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
     * Set nom
     *
     * @param string $nom
     * @return Commercial
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
     * @return Commercial
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
     * Set directeur
     *
     * @param boolean $directeur
     * @return Questionnaire
     */
    public function setDirecteur($directeur)
    {
        $this->directeur = $directeur;

        return $this;
    }

    /**
     * Get directeur
     *
     * @return boolean 
     */
    public function getDirecteur()
    {
        return $this->directeur;
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
