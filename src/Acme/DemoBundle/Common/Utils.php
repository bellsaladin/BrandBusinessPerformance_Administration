<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Acme\DemoBundle\Common;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Acme\DemoBundle\Entity\Visite;

class Utils
{
    public static function getWeeksList() {
      $weeksList = array();
      $currentYear = date('Y');
      $nextYear = date('Y') + 1;
      $firstMondayOfCurrentYear = date("Y-m-d", strtotime("first monday". $currentYear."-1"));
      $firstMondayOfNextYear = date("Y-m-d", strtotime("first monday ".$nextYear."-1"));
      $weekNum = 1;
      $nextWeekMonday = $firstMondayOfCurrentYear;
      while ($nextWeekMonday < $firstMondayOfNextYear){
          $weeksList[$nextWeekMonday] = 'Week '. $weekNum;
          $nextWeekMonday = date("Y-m-d", strtotime( $nextWeekMonday . " +1 week"));
          $weekNum++;
      }

      return $weeksList;
    }

}
