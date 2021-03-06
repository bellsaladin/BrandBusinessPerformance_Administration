<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class QuestionnaireShelfShareAdmin extends Admin
{
    protected $baseRoutePattern = 'questionnaireShelfShare';
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
            ->add('nbrLignesTraitees')
            ->add('tempsRemplissage')
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
            ->add('valide', null, array('editable' => true,'label' => 'Validé'))
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
            ->add('nbrLignesTraitees')
            ->add('tempsRemplissage')
            //->add('quantities')
            ;
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

    public function getBatchActions()
    {
      // retrieve the default (currently only the delete action) actions
      $actions = parent::getBatchActions();

      // PS : https://sonata-project.org/bundles/admin/2-0/doc/reference/batch_actions.html

      // check user permissions
      if($this->isGranted('EDIT') && $this->isGranted('DELETE')){
          $originalActions = $actions; // on copie les actions et les réinitialise pour mettre les actiosn 'valider' & 'dévalider' en premier
          $actions = array(); // on réinitilaise le tableau
          $actions['valider']=array(
              'label'            => 'Valider',
              'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
          );

          $actions['devalider']=array(
              'label'            => 'Dévalider',
              'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
          );

          $actions = array_merge($actions, $originalActions);
      }

      return $actions;
    }
}
