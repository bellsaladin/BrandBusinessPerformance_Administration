<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;

use KnpMenuItemInterface as MenuItemInterface;

class SuperviseurAdmin extends Admin
{
        protected $translationDomain = 'AcmeDemoBundle'; // default is 'messages'


    protected $baseRoutePattern = 'superviseur';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
      $formMapper
      ->with('Informations personnelles', array('collapsed' => false))
      ->add('nom')
      ->add('prenom')
      ->end()
      ->with('Compte utilisateur', array('collapsed' => false))
      ->add('user', 'sonata_type_admin', array(
        'delete' => false ,
        'label' => false,
        'btn_add' => false

      ))
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
      ->add('nom')
      ->add('prenom')
      ->add('user.username','entity', array('class' => 'Acme\DemoBundle\Entity\FosUserUser', 'label' => 'Nom d\'utilisateur'))
      ->add('user.email','entity', array('class' => 'Acme\DemoBundle\Entity\FosUserUser', 'label' => 'Email'))
      ->add('user.enabled', null, array('editable' => true,'label' => 'Activé'))
      ->add('_action', 'actions', array(
        'actions' => array(
          'show' => array(),
          'edit' => array(),
          'delete' => array(),
        )
      ))
      ;

    }

    public function prePersist($superviseur)
    {
      $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
      $groups = $em->getRepository('ApplicationSonataUserBundle:Group')->findBy(array('name' => 'Superviseur'));
      $superviseur->getUser()->setGroups($groups);
    }

    public function preUpdate($superviseur)
    {
      if(method_exists($superviseur,'getUser')){
        $this->getConfigurationPool()->getAdminByAdminCode('sonata.user.admin.user')->preUpdate($superviseur->getUser());
      }
    }

    public function postRemove($superviseur)
    {
      $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
      $em->remove($superviseur->getUser());
      $em->flush();
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        // Here we set the fields of the ShowMapper variable, $showMapper (but this can be called anything)
        $showMapper
          ->add('nom')
          ->add('prenom')
          ->add('user.username','entity', array('class' => 'Acme\DemoBundle\Entity\FosUserUser', 'label' => 'Nom d\'utilisateur'))
          ->add('user.email','entity', array('class' => 'Acme\DemoBundle\Entity\FosUserUser', 'label' => 'Email'))
        ;

    }

    public function getExportFormats()
    {
        return array(
            //'json', 'xml', 'csv', 'xls'
            'xls'
        );
    }
}
