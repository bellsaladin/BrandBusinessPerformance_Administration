<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Acme\DemoBundle\Common\Utils;
use Acme\DemoBundle\Form\DateTimeToWeekTransformer;

class VisiteAdmin extends Admin
{
    //protected $baseRoutePattern = 'visite';
    protected $baseRoutePattern = 'visite';
    protected $baseRouteName = 'admin_acmeDemoBundle_visite';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

      $choices = array(
          1 => 'Lundi',
          2 => 'Mardi',
          3 => 'Mercredi',
          4 => 'Jeudi',
          5 => 'Vendredi',
          6 => 'Samedi',
          7 => 'Dimanche',
      );

      $formMapper->add('dayOfWeek', 'choice', array('choices' => $choices));
      $formMapper->add('pdv');
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('pdv')
            //->add('author')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            //->addIdentifier('planning')
            ->add('planning.dateDebutSemaineToWeek', null, array(
                'label' => 'Week ',
                'route' => array(
                    'name' => 'show'
                )
             ))
            ->add('pdv')
            ->add('planning.sfo')
            ->add('commentaire')

            ->add('_action', 'actions', array(
            'actions' => array(
                //'commenter' => array(),
                'edit' => array()
            )))
        ;

    }
}
