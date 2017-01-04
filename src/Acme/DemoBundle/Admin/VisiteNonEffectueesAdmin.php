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

class VisiteNonEffectueesAdmin extends Admin
{
    protected $baseRoutePattern = 'visite-non-effectuee';
    protected $baseRouteName = 'admin_acmeDemoBundle_visiteNonEffectuee';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        //if($this->getSubject()->getId() > 0){
        //  $formMapper->add('commentaire', 'text');
        //}else{

          if (!$this->hasParentFieldDescription()) {
              //$formMapper->add('planning', null, array('constraints' => new Assert\NotNull()));
              $formMapper->add('commentaire', 'text');
          }else{

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
        //}
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            /*->add('planning.dateDebutSemaine', null, array('label' => 'Week'), 'choice', array('choices' => Utils::getWeeksList()))*/
            ->add('planning.year', null, array('label' => 'Année'), 'choice', array('choices' => Utils::getYearsList())
            );
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
            ->add('planning.year')
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

    public function createQuery($context = 'list')
    {
        // $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();
        // /*$query = $em->createQuery('
        //   SELECT v FROM AcmeDemoBundle:Visite v, AcmeDemoBundle:Planning p
        //   WHERE v.planning = p.id  AND p.id = :p1'
        //               )->setParameter('p1', 30);*/
        // return $query;
        if($context == 'list'){
          $query = parent::createQuery($context);
          //var_dump($query);
          //$fields = array($query->getRootAlias().'', 'DATE_DIFF(CURRENT_DATE(), p.dateDebutSemaine) as dateDiff');
          //$query->select($fields);
          $query->innerJoin('AcmeDemoBundle:Planning', 'p', 'WITH', $query->getRootAlias().'.planning = p.id');//->where($query->getRootAlias().'.planning = p.id');
          //$query->innerJoin('AcmeDemoBundle:Localisation', 'l',  'WITH', $query->getRootAlias().'.pdv != l.pdv');
          //$query->where('p.pdv != l.pdv');
          //$query->where($query->getRootAlias().'.planning = p.id');
          //$query->andWhere('DATE_DIFF(CURRENT_TIME(), p.dateDebutSemaine)');
          /*$query->andWhere(
              $query->expr()->eq('p.dateDebutSemaine', ':date')
          );
          $query->setParameter('date', '2016-01-04');*/
          $query->andWhere(
              $query->expr()->gte('DATE_DIFF(CURRENT_DATE(), p.dateDebutSemaine)', ':p2')
          );
          $query->andWhere(
              $query->expr()->eq($query->getRootAlias().'.done', '0')
          );
          $query->setParameter('p2', 7);
          return $query;
        }
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        // remove delete if this form has no parent
        // only update is possible
        if (!$this->hasParentFieldDescription())  {

           $collection->remove('delete');
        }
    }
}
