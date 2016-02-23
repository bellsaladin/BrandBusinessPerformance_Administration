<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use KnpMenuItemInterface as MenuItemInterface;

class ClientAdmin extends Admin
{
        protected $translationDomain = 'AcmeDemoBundle'; // default is 'messages'


    protected $baseRoutePattern = 'client';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Informations du client', array('collapsed' => false))
            ->add('nom')
            ->add('email')
            ->add('telephone')
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
            ->add('email')
            ->add('telephone')
            ->add('user.username','entity', array('class' => 'Acme\DemoBundle\Entity\FosUserUser', 'label' => 'Nom d\'utilisateur'))
            ->add('user.email','entity', array('class' => 'Acme\DemoBundle\Entity\FosUserUser', 'label' => 'Email'))
            ->add('enabled', null, array('label' => 'Accède aux données','editable' => true))
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
            ->add('nom')
            ->add('email')
            ->add('telephone')
            ->add('user', 'entity', array('class' => 'Acme\DemoBundle\Entity\FosUserUser', 'label' => 'Compte utilisateur', 'associated_property' => 'username'))
        ;

    }

    public function prePersist($client)
    {
      $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
      $groups = $em->getRepository('ApplicationSonataUserBundle:Group')->findBy(array('name' => 'Client'));
      $client->getUser()->setGroups($groups);
    }

    public function preUpdate($client)
    {
      if(method_exists($client,'getUser')){
        $this->getConfigurationPool()->getAdminByAdminCode('sonata.user.admin.user')->preUpdate($client->getUser());
      }
    }

    public function preRemove($client)
    {
      $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
      $em->remove($client->getUser());
      $em->flush();
    }

    public function configureRoutes(RouteCollection $collection)
    {
      // to remove a single route
      $collection->remove('add');
      $collection->remove('create');
      $collection->remove('delete');
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
