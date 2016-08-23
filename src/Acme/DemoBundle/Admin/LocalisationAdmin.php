<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class LocalisationAdmin extends Admin
{
    protected $baseRoutePattern = 'localisation';
    public $deleteMessageWarning = "ATTENTION : En supprimant une localisation toutes les données (Rapports...etc) basées sur cet enregistrement seront également supprimées";

    protected $datagridValues = array(
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'dateCreation'  // name of the ordered field
                                 // (default = the model's id field, if any)

        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            //->add('dateCreation')

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
             ->add('pdv', 'entity', array('class' => 'Acme\DemoBundle\Entity\Pdv', 'associated_property' => 'nom'))

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
            ->add('dateCreation','datetime',array('label'=>'Date de création'))
            ->add('pdv', 'entity', array('class' => 'Acme\DemoBundle\Entity\Pdv', 'associated_property' => 'nom'))
            ->add('SFO', 'entity', array('class' => 'Acme\DemoBundle\Entity\Sfo', 'associated_property' => 'nom', 'label' => 'SFO'))
            ->add('emplacement','string', array('template' => 'AcmeDemoBundle:LocalisationAdmin:emplacement_localisation.html.twig','label' =>'Emplacement'))
            ->add('imagefilename', 'string', array('template' => 'AcmeDemoBundle:LocalisationAdmin:list_image.html.twig'))
            ->add('questionnaires','sonata_type_collection', array('options'=>array(
                 'route' => array(
                     'name' => 'show'
                 ))
             ))
        ;
    }

    public function getExportFields()
    {
       $exportFields = parent::getExportFields(); //It's not working in case of *-to many relation, so remove it, but it's ok for small customisation
       $exportFields["SFO"] = 'sfo';
       $exportFields["PDV"] = 'pdv';
       return $exportFields;
    }

    public function getExportFormats()
    {
        return array(
            //'json', 'xml', 'csv', 'xls'
            'xls'
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
