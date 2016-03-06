<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class QuestionnaireDisponibiliteAdmin extends Admin
{
    protected $baseRoutePattern = 'questionnaireDisponibilite';
    protected $datagridValues = array(
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'dateCreation'  // name of the ordered field
                                 // (default = the model's id field, if any)
        );
    
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('dateCreation')
            ->add('localisation.pdv')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            //->add('dateCreation')
            //->add('author')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper            
            ->addIdentifier('dateCreation', null, array(
                 'route' => array(
                     'name' => 'show'
                 )
             ))
            ->add('localisation.pdv', 'entity', array('label' => 'Pdv', 'route' => array('name' => 'show')))
            ->add('localisation.sfo', 'entity', array('label' => 'SFO', 'route' => array('name' => 'show')))
            
            //->add('slug')
            //->add('author')
            ->add('_action', 'actions', array(
            'actions' => array(
                'show' => array(),
                //'edit' => array(),
                //'delete' => array(),
            )
        ))
        ;

    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('dateCreation')
            ->add('localisation.pdv', 'entity', array('label' => 'Point de Vente', 'route' => array('name' => 'show')))
            //->add('quantities')
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

    protected function configureRoutes(RouteCollection $collection)
    {
        // to remove a single route
        $collection->remove('create');
        $collection->remove('edit');
        // $collection->remove('delete');        
    }
}