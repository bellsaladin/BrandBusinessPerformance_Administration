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

class SFOAdmin extends Admin
{
    protected $translationDomain = 'AcmeDemoBundle'; // default is 'messages'
    public $deleteMessageWarning = "ATTENTION : En supprimant un SFO toutes les données (Localisations, rapports, plannings...etc) basées sur cet enregistrement seront également supprimées";
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

    public function getExportFields()
    {
       $exportFields = parent::getExportFields(); //It's not working in case of *-to many relation, so remove it, but it's ok for small customisation
       $exportFields["Username"] = 'user.username';
       $exportFields["Email"] = 'user.email';
       $exportFields["Team leader"] = 'teamLeader';
       return $exportFields;
   }

    public function getExportFormats()
    {
        return array(
            //'json', 'xml', 'csv', 'xls'
            'xls'
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
