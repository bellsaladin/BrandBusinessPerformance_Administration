<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;

use KnpMenuItemInterface as MenuItemInterface;

class SFOAdmin extends Admin
{
    protected $translationDomain = 'AcmeDemoBundle'; // default is 'messages'

    protected $baseRoutePattern = 'sfo';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Informations personnelles', array('collapsed' => false))
            ->add('nom')
            ->add('prenom')
            ->end()
            ->with('Team leader', array('collapsed' => false))
            ->add('teamLeader', 'entity', array('class' => 'Acme\DemoBundle\Entity\TeamLeader', 'property' => 'nom', 'label'=>false))
            ->end()
            ->with('Compte utilisateur', array('collapsed' => false))
            ->add('user.email', 'text')
            ->add('user.username', 'text')
            ->add('user.plainPassword', 'text', array('label'=>'Mot de passe'))
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
            ->add('teamLeader')
            ->add('user.username','entity', array('class' => 'AppBundle\Entity\User\User', 'label' => 'Nom d\'utilisateur'))
            ->add('user.email','entity', array('class' => 'AppBundle\Entity\User\User', 'label' => 'Email'))
            ->add('user.enabled', null, array('editable' => true,'label' => 'Activé'))
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
          ->add('nom')
          ->add('prenom')
          //->add('teamLeader')
          ->add('user.username','entity', array('class' => '\AppBundle\Entity\User\User', 'label' => 'Nom d\'utilisateur'))
          ->add('user.email',   'entity', array('class' => '\AppBundle\Entity\User\User', 'label' => 'Email'))
        ;

    }

    public function prePersist($subject)
    {
      $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
      $groups = $em->getRepository('AppBundle:User\Group')->findBy(array('name' => 'SFO'));
      $subject->getUser()->setGroups($groups);
    }

    public function preUpdate($subject)
    {
      if(method_exists($subject,'getUser')){
        $this->getConfigurationPool()->getAdminByAdminCode('sonata.user.admin.user')->preUpdate($subject->getUser());
      }
    }

    public function preRemove($subject)
    {
      $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
      $em->remove($subject->getUser());
      $em->flush();
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
