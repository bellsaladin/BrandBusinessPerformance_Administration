<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionnaireDisponibliteProduit
 * 
 * @ORM\Table(name="questionnairedisponibilite_produit") 
 * @ORM\Entity
 */
class QuestionnaireDisponibiliteProduit
{
    /** 
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="QuestionnaireDisponibilite") 
     * @ORM\JoinColumn(name="questionnairedisponibilite_id", referencedColumnName="id", nullable=false) 
     */
    private $questionnaireDisponibilite;

    /** 
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Classification\Category") 
     * @ORM\JoinColumn(name="categorieProduits_id", referencedColumnName="id", nullable=false) 
     */
    private $categorieProduits;

    /** 
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="POI") 
     * @ORM\JoinColumn(name="poi_id", referencedColumnName="id", nullable=false) 
     */
    private $poi;

    /** 
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Produit") 
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id", nullable=false) 
     */
    private $produit;

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

    public function getProduit(){
        return $this->produit;
    }

    public function getCategorieProduits(){
        return $this->categorieProduits;
    }

    public function getQte(){
        return $this->qte;
    }

    public function __toString()
    {
        return 'QuestionnaireDisponibliteProduit (' . $this->questionnaireDisponibilite->getId() .','.$this->produit->getLibelle().','.$this->categorie->getName().')';
    }

}
