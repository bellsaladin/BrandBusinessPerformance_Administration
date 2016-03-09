<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;

class MarqueAdmin extends Admin
{
    protected $baseRoutePattern = 'marque';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
        $categories = $em->getRepository('AppBundle:Classification\Category')->findAll();
        $rootCategories = array();
        foreach($categories as $categorie){
            if($categorie->getParent() == null)
                $rootCategories[] = $categorie;
        }

        $choosableCategories = array();
        foreach($categories as $categorie){
            foreach($rootCategories as $rootCategorie){
                if($categorie->getParent() == $rootCategorie)
                    $choosableCategories[$categorie->getId()] = $categorie;
            }
        }

        $formMapper
            ->add('libelle', 'text', array('label' => 'Nom'))
            ->add('categoriesProduits',null,array('label' => 'Catégories des produits', 'choices' =>$choosableCategories));
            //->add('author', 'entity', array('class' => 'Acme\DemoBundle\Entity\User'))
        ;
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
            ->addIdentifier('libelle','text',array('label' => 'Nom'))
            ->add('enabled', null, array('editable' => true,'label' => 'Activé'))
            ->add('categoriesProduits')
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