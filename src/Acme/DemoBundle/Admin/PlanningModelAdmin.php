<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PlanningModelAdmin extends Admin
{
    protected $baseRoutePattern = 'planning-model';
    protected $datagridValues = array(
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'id'  // name of the ordered field
                                 // (default = the model's id field, if any)
        );

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            //->add('animateur','sonata_type_model_list')
            ->add('sfo'      ,'sonata_type_model_list')
            //->add('pdv')
            /*->add('pdv', 'sonata_type_collection', array(
                'required' => true
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'editable' => false,
                'sortable'  => 'position',
            ))*/
            ->add('visites', 'sonata_type_collection', array(
                    'by_reference'       => false,
                    'cascade_validation' => true,
                ), array(
                    'edit' => 'inline',
                    'inline' => 'table'
                ))
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
            ->add('sfo')
            ->add('visites')

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
        // Here we set the fields of the ShowMapper variable, $showMapper (but this can be called anything)
        $showMapper
            ->add('sfo', 'entity', array('class' => 'Acme\DemoBundle\Entity\SFO'))
            ->add('visites')
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
        //$collection->remove('create');
        //$collection->remove('edit');
        // $collection->remove('delete');
    }

}
