<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PdvAdmin extends Admin
{
    protected $baseRoutePattern = 'Pdv';
    public $deleteMessageWarning = "ATTENTION : En supprimant un PDV toutes les données  (Données de planning, détails de rapports, données de référencement... etc) basées sur cet enregistrement seront également supprimées";

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
        $formMapper
            ->add('nom', 'text', array('label' => 'Nom'))
            ->add('licence','number')
            ->add('ville')
            ->add('secteur')
            ->add('longitude','hidden')
            ->add('latitude','hidden')
            ->add('externe')
            ->add('poi', null,  array('label' => 'Points d\'interêt'))
            /*->add('poi', 'sonata_type_model', array('expanded' => true, 'by_reference' => false, 'multiple' => true))*/
            ->add('outletname')
            ->add('channel')
            ->add('status')
            ->add('family')
            ->add('category')
            ->add('sfo')
            ->add('week')
            ->add('jourvisite', 'choice', array('label' => 'Temps de visite',
                    'choices' => array(
                        '1' => 'Lundi',
                        '2' => 'Mardi',
                        '3' => 'Mercredi',
                        '4' => 'Jeudi',
                        '5' => 'Vendredi',
                        '6' => 'Samedi',
                        '7' => 'Dimanche',
                    ))
                )
            ->add('tempsvisite', 'choice', array('label' => 'Temps de visite',
                    'choices' => array(
                        'matin' => 'Matin',
                        'apres-midi' => 'Après-midi',
                    ))
                )
            ->add('collabore')
            ->add('incentive')
            ->add('elimine', null,  array('label' => 'Eliminé'))
            ->add('managerphone')
            ->add('managerfullname')
            ->add('ownerphone')
            ->add('ownerfullname')
            ->add('size', null,  array('label' => 'Size (m2)'))
            ->add('incentivestartweek')
            ->add('datastartweek')
            ->add('commentaire','textarea')
            //->add('userCreateur', 'entity', array('class'=>'Acme\DemoBundle\Entity\FosUserUser','property' => 'username', 'label'=>false,'value' => $user))
            //->add('rayon')

            //->add('author', 'entity', array('class' => 'Acme\DemoBundle\Entity\User'))

        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            //->add('nom')
            //->add('author')
        ;
    }

    public function createQuery($context = 'list')
    {
        if($this->getConfigurationPool()->getContainer()->get('security.context')->isGranted('ROLE_SUPERVISEUR')){
            $user = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();

            $query = parent::createQuery($context);
            $query->andWhere(
                $query->expr()->eq($query->getRootAlias().'.userCreateur', ':id')
            );
            $query->setParameter('id', $user->getId());
            return $query;
        }else{
            $user = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();

            $query = parent::createQuery($context);
            return $query;
        }
    }

    public function prePersist($pdv)
    {
      $user = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
      $pdv->setUserCreateur($user);
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nom')
            ->add('ville')
            ->add('secteur')
            ->add('poi')
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
            ->add('ville')
            ->add('secteur')
            ->add('emplacement','string', array('template' => 'AcmeDemoBundle:LocalisationAdmin:emplacement.html.twig','label' =>'Emplacement'))
            ->add('poi','')
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
        //if( $this->securityContext !=null)
        //if(!$this->securityContext->isGranted('ROLE_ADMIN') ){
            // to remove a single route
            //$collection->remove('create');
            //$collection->remove('edit');
            //$collection->remove('delete');
        //}
    }
}
