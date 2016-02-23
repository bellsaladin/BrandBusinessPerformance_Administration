<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;

class DemandechangementlicenceAdmin extends Admin
{
    protected $baseRoutePattern = 'DemandeChangeLicence';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            //->add('animateurId')
            ->add('localisation', 'entity', array('class' => 'Acme\DemoBundle\Entity\Localisation','property' => 'id'))
            ->add('licence')
            ->add('motif')

            //->add('author', 'entity', array('class' => 'Acme\DemoBundle\Entity\User'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            //->add('animateurId')
            //->add('pdvId')
            //->add('licence')
            //->add('author')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('licence', null, array('label'=>'Licence remplacée'))
            ->add('localisation', 'entity', array('class' => 'Acme\DemoBundle\Entity\Localisation','associated_property' => 'pdv.nom','route' => array(
                     'name' => 'show'
                 )))
            //->add('slug')
            //->add('author')
            ->add('_action', 'actions', array(
            'actions' => array(
                'show' => array(),
                //'edit' => array(),
                'delete' => array(),
            )
        ))
        ;

    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        // Here we set the fields of the ShowMapper variable, $showMapper (but this can be called anything)
        $showMapper
            ->add('licence')
            ->add('motif')

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
