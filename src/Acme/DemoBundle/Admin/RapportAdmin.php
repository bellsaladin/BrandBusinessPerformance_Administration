<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class RapportAdmin extends Admin
{
    protected $baseRoutePattern = 'Rapport';
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
            ->add('achete')
            ->add('sexe')
            //->add('fidelite')
            ->add('cadeau')
            ->add('marqueHabituelle', 'entity', array('class' => 'Acme\DemoBundle\Entity\Marque'))            
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
            ->add('localisation.pdv.nom', 'entity', array('label' => 'PDV', 'route' => array('name' => 'show')))
            ->add('achete', null, array('label' => 'Achat') )
            
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
        if($this->getSubject()->getAchete() == 1){
            // Here we set the fields of the ShowMapper variable, $showMapper (but this can be called anything)
            $showMapper
                ->add('dateCreation')
                ->add('localisation', 'entity', array('class' => 'Acme\DemoBundle\Entity\Localsation', 'associated_property' => 'pdv.nom','route' => array(
                         'name' => 'show'
                     )))
                ->add('achete')
                ->add('sexe')
                ->add('trancheage','entity', array('class' => 'Acme\DemoBundle\Entity\Trancheage', 'associated_property' => 'libelle'))
                ->add('fidelite')        
                ->add('raisonachat','entity', array('class' => 'Acme\DemoBundle\Entity\Raisonachat', 'associated_property' => 'libelle'))                
                ->add('marqueHabituelle', 'entity', array('class' => 'Acme\DemoBundle\Entity\Marque', 'associated_property' => 'libelle'))
                ->add('marqueHabituelleQte')
                ->add('marqueAchetee', 'entity', array('class' => 'Acme\DemoBundle\Entity\Marque', 'associated_property' => 'libelle'))
                ->add('marqueAcheteeQte')                
                ->add('cadeau')
                // ->add('cadeau', 'entity', array('class' => 'Acme\DemoBundle\Entity\Cadeau'))
                ->add('tombola')
            ;
        }else{
            $showMapper
                ->add('dateCreation')
                ->add('localisation', 'entity', array('class' => 'Acme\DemoBundle\Entity\Localsation', 'associated_property' => 'pdv.nom','route' => array(
                         'name' => 'show'
                     )))
                ->add('achete')
                ->add('sexe')
                ->add('trancheage','entity', array('class' => 'Acme\DemoBundle\Entity\Trancheage', 'associated_property' => 'libelle'))                
                ->add('raisonrefus','entity', array('class' => 'Acme\DemoBundle\Entity\Raisonrefus', 'associated_property' => 'libelle'))
                ->add('marqueHabituelle', 'entity', array('class' => 'Acme\DemoBundle\Entity\Marque', 'associated_property' => 'libelle'))
                ->add('marqueHabituelleQte')
                ->add('commentaire')                
            ;
        }
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

    protected function configureRoutes(RouteCollection $collection)
    {
        // to remove a single route
        $collection->remove('create');
        $collection->remove('edit');
        // $collection->remove('delete');        
    }
}