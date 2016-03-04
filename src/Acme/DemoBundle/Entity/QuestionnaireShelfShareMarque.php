<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionnaireShelfShareMarque
 * 
 * @ORM\Table(name="questionnaireshelfshare_marque") 
 * @ORM\Entity
 */
class QuestionnaireShelfShareMarque
{
    /** 
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="QuestionnaireShelfShare") 
     * @ORM\JoinColumn(name="questionnaireshelfshare_id", referencedColumnName="id", nullable=false) 
     */
    private $questionnaireShelfShare;

    /** 
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Marque") 
     * @ORM\JoinColumn(name="marque_id", referencedColumnName="id", nullable=false) 
     */
    private $marque;

    /** 
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Classification\Category") 
     * @ORM\JoinColumn(name="categorieProduits_id", referencedColumnName="id", nullable=false) 
     */
    private $categorieProduits;

    /** 
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Classification\Category") 
     * @ORM\JoinColumn(name="segment_id", referencedColumnName="id", nullable=false) 
     */
    private $segment;

    /** 
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="POI") 
     * @ORM\JoinColumn(name="poi_id", referencedColumnName="id", nullable=false) 
     */
    private $poi;

     /**
     * @var \Integer
     *
     * @ORM\Column(name="qte", type="integer", nullable=true)
     */
    private $qte;

    public function __construct()
    {

    }

    public function getPoi(){
        return $this->poi;
    }

    public function getMarque(){
        return $this->marque;
    }

    public function getCategorieProduits(){
        return $this->categorieProduits;
    }

    public function getSegment(){
        return $this->segment;
    }

    public function getQte(){
        return $this->qte;
    }

    public function __toString()
    {
        return 'QuestionnaireShelfShareMarque (' . $this->questionnaireShelfShare->getId() .','.$this->marque->getLibelle().','.$this->categorie->getName().')';
    }

}
