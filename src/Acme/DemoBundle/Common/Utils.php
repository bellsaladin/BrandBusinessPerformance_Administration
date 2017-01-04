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
    private static $weeksList = null;
    private static $MAX_WEEKS = 52;

    public static function getWeeksList($year = null) {
      if($year == null) $year = date('Y');
      
      //if(self::$weeksList) return self::$weeksList;
      //else
      $weeksList = array();
      $currentYear = $year;
      $nextYear = $year + 1;
      $firstMondayOfCurrentYear = date("Y-m-d", strtotime("first monday". $currentYear."-1"));
      $weekNum = 1;
      $nextWeekMonday = $firstMondayOfCurrentYear;
      while ($weekNum <= Utils::$MAX_WEEKS){
          $weeksList[$nextWeekMonday] = 'Week '. $weekNum;
          $nextWeekMonday = date("Y-m-d", strtotime( $nextWeekMonday . " +1 week"));
          $weekNum++;
      }
      //self::$weeksList = $weeksList;
      return $weeksList;
    }

    public static function getYearsList(){
      $yearsList = array();
      for($i = intval(date('Y')) - 1; $i <= intval(date('Y')); $i ++){
        $yearsList[$i] = $i;
      }
      return $yearsList;
    }

    public static function getDateDebutSemaineOfYearAndWeek($pYear, $pWeek){
      $weeksList = Utils::getWeeksList(intval($pYear));
      foreach($weeksList as $date => $week){
        if($week == $pWeek){
          return $date;
        }
      }
    }

}
