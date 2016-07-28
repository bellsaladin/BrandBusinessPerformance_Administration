<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use KnpMenuItemInterface as MenuItemInterface;

class TeamLeaderAdmin extends Admin
{
    protected $translationDomain = 'AcmeDemoBundle'; // default is 'messages'

    protected $baseRoutePattern = 'teamLeader';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
      $formMapper
        ->with('Informations personnelles', array('collapsed' => false))
        ->add('nom')
        ->add('prenom')
        ->end()
        ->with('Compte utilisateur', array('collapsed' => false))
        /*->add('user', 'sonata_type_admin', array(
                  'delete' => false ,
                  'label' => false,
                  'btn_add' => false
              ))
          ;
          */
        ->add('user.email', 'text');
        if(!$this->getRoot()->getSubject()->getId()){
            $formMapper->add('user.username', 'text');
            $formMapper->add('user.plainPassword', 'text', array('label'=>'Mot de passe'));
        }
        else{
            $formMapper->add('user.username', 'text' , array( 'read_only' => true, 'disabled'  => true));
            $formMapper->add('user.plainPassword', 'text', array('label'=>'Mot de passe ( Mettre une valeur si vous voulez changer le mot de passe)','required'=>false));
        }
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
      ->add('user.username','entity', array('class' => '\AppBundle\Entity\User\User', 'label' => 'Nom d\'utilisateur'))
      ->add('user.email'   ,'entity', array('class' => '\AppBundle\Entity\User\User', 'label' => 'Email'))
      ->add('user.enabled' , null, array('editable' => true,'label' => 'Activé'))
      ->add('_action', 'actions', array(
        'actions' => array(
          'show' => array(),
          'edit' => array(),
          'delete' => array(),
        )
      ));

    }

    public function prePersist($subject)
    {
      $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
      $groups = $em->getRepository('AppBundle:User\Group')->findBy(array('name' => 'Team Leader'));
      $subject->getUser()->setGroups($groups);
    }

    public function preUpdate($subject)
    {
      if(method_exists($subject,'getUser')){
        $this->getConfigurationPool()->getAdminByAdminCode('sonata.user.admin.user')->preUpdate($subject->getUser());
      }
    }

    public function postRemove($subject)
    {
      $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
      $em->remove($subject->getUser());
      $em->flush();
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        // Here we set the fields of the ShowMapper variable, $showMapper (but this can be called anything)
        $showMapper
          ->add('nom')
          ->add('prenom')
          ->add('user.username','entity', array('class' => '\AppBundle\Entity\User\User', 'label' => 'Nom d\'utilisateur'))
          ->add('user.email',   'entity', array('class' => '\AppBundle\Entity\User\User', 'label' => 'Email'));
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

    public function validate(ErrorElement $errorElement, $object)
    {
      $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
      //$userExists = $em->getRepository('AppBundle:User\User')->findOneBy(array('username' => ));;
      $userManager = $this->getConfigurationPool()->getContainer()->get('fos_user.user_manager');

      //echo $object->getUser()->getUsername(); die();
      $userExists = $userManager->FindUserByUsername($object->getUser()->getUsername());

      //$originalObject = $em->getRepository('AcmeDemoBundle:SFO')->findOneBy(array('id' => $object->getId()));
      //var_dump($originalObject->getUser()->getId() . ' - ' .  $userExists->getUsername());die(1);
      //var_dump($originalObject->getUser()->getId() . ' - ' .  $originalObject->getUser()->getUsername());die(1);

      //$oldUsername = $originalObjectOldUser->getUsername();

      if($userExists){
        // On prend on compte le test de l'existance de l'utilisateur que lors de la création ou le changement de nom d'uitlisateur
        if(!$object->getUser()->getId()
            /*|| ($object->getUser()->getUsername() != $oldUsername)*/){
            $errorElement
              ->with('user.username')
                  ->addViolation("Le nom d'utilisateur choisi est déjà utilisé !")
              ->end();
        }
      }
    }
}
