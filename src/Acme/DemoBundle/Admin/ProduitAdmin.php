<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Acme\DemoBundle\Entity\Produit\REF;
use Acme\DemoBundle\Entity\Produit\WM;
use Acme\DemoBundle\Entity\Produit\CTV;
use Acme\DemoBundle\Entity\Produit\AC;


class ProduitAdmin extends Admin
{
    protected $baseRoutePattern = 'produit';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();

        $formMapper
            ->add('sku', 'text', array('label' => 'SKU'))
            ->add('libelle', 'text', array('label' => 'Libéllé'))
            ->add('categorie',null,array('label' => 'Catégorie'))
            ->add('marque',null,array('label' => 'Marque'))
            ->add('modele',null,array('label' => 'Modèle'))
            ->add('type',null,array('label' => 'Type'));
            //->add('author', 'entity', array('class' => 'Acme\DemoBundle\Entity\User'))
        ;

        if ($subject instanceof REF || $subject->getEntityType() == 'REF') {
            $formMapper->add('couleur', 'text');
            $formMapper->add('volume', 'text');
            $formMapper->add('typeCompresseur', 'text',array('label' => 'Type compresseur'));
            $formMapper->add('dimension', 'text');
        }

        if ($subject instanceof WM || $subject->getEntityType() == 'WM') {
            $formMapper->add('couleur', 'text');
            $formMapper->add('capacite', 'text');
            $formMapper->add('typeMoteur', 'text',array('label' => 'Type moteur'));
        }

        if ($subject instanceof CTV || $subject->getEntityType() == 'CTV') {
            $formMapper->add('smart');
            $formMapper->add('inch', 'text');
            $formMapper->add('serie', 'text');
            $formMapper->add('resolution', 'text');
        }

        if ($subject instanceof AC || $subject->getEntityType() == 'AC'){
            $formMapper->add('couleur', 'text');
            $formMapper->add('capacite', 'text');
        }

        $formMapper->add('dimension', 'text');
        $formMapper->add('garantie', 'text');
        $formMapper->add('prix', 'text');
        $formMapper->add('prixPromotionnel', 'text',array('label' => 'Prix promotionnel'));
        $formMapper->add('attribut1', 'text',array('label' => 'Attribut extra 1'));
        $formMapper->add('attribut2', 'text',array('label' => 'Attribut extra 2'));
        $formMapper->add('attribut3', 'text',array('label' => 'Attribut extra 3'));
        $formMapper->add('attribut4', 'text',array('label' => 'Attribut extra 4'));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            //->add('libelle')
            //->add('author')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper            
            ->addIdentifier('sku','text',array('label' => 'SKU'))
            ->addIdentifier('libelle','text',array('label' => 'Libéllé'))
            ->add('enabled', null, array('editable' => true,'label' => 'Activé'))
            ->add('categorie',null,array('label' => 'Catégorie'))
            //->add('slug')
            //->add('author')
            ->add('_action', 'actions', array(
            'actions' => array(
                'show' => array(),
                'edit' => array(),
                'delete' => array(),
            )
        ))
        ;

    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        // Here we set the fields of the ShowMapper variable, $showMapper (but this can be called anything)
        $showMapper
            ->add('libelle')
            ->add('enabled')
        ;

    }

     /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array(
            //'json', 'xml', 'csv', 'xls'
        );
    }
}