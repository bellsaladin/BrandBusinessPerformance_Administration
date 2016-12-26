<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\DemoBundle\Entity\Animateur;
use Acme\DemoBundle\Entity\FosUserUser;

class WebserviceController extends Controller
{
    public function authentificationAction(Request $request)
    {
      $sfoId = 0; //not found by default

	    $userName = $request->request->get('username');
	    $password = $request->request->get('password');

	    $userManager = $this->get('fos_user.user_manager');
	    $user = $userManager->FindUserByUsername($userName);
	    if($user != null){
		    $encoder = $this->get('security.encoder_factory')->getEncoder($user);
		    $encodedPass = $encoder->encodePassword($password, $user->getSalt());

		    if($user->getPassword() == $encodedPass){

		    	$em = $this->getDoctrine()->getManager();

		    	// get FosUserUser entity using the found user's id
		        $sfo = $em->getRepository('AcmeDemoBundle:SFO')->findOneBy(array('user' => $user->getId()));

		         //echo $entity->getId();
		        if ($sfo) {
		            $sfoId = $sfo->getId();
		        }
		    }
	    }

	    return new Response($sfoId);
    }

    public function getShelfShareDataAction(){
    	$em = $this->getDoctrine()->getManager();

	    //$startDate = $request->request->get('startDate');
	    //$endDate = $request->request->get('endDate');

	    /* Get : shelfshare data */
        $sql = "SELECT pdv.nom pdv, poi.libelle poi, c.name categorieProduits, s.name segment, m.libelle marque, ville, SUM(qte) ".
        	   "FROM questionnaireshelfshare q, localisation l, questionnaireshelfshare_marque qd, " .
        	   "classification__category c, classification__category s,poi, pdv, marque m " .
               "WHERE q.localisation_id = l.id AND q.id = qd.questionnaireshelfshare_id ".
               "AND qd.categorieProduits_id = c.id AND qd.segment_id = s.id AND l.pdv_id = pdv.id ".
               "AND qd.poi_id = poi.id AND qd.marque_id = m.id ".
               "GROUP BY 1,2,3,4,5,6";
               //"date_creation >= '" . $param_startDate . "' AND date_creation <= '" . $param_endDate ."'";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $dataArray = array();
        $gotColumnsNames = false;
        while ($row = $queryResult->fetch()) {
        	if(!$gotColumnsNames){
        		$dataArray[] = array_keys($row);
        		$gotColumnsNames = true;
        	}
            $dataArray[] = array_values($row);// remove keys, keeping only values
            //$dataArray[] = $row;
        }

	    $jsonContent = json_encode($dataArray, JSON_NUMERIC_CHECK);

	    $response = new Response($jsonContent);
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
    }

    public function getSfosPerformanceDataAction(){
    	$em = $this->getDoctrine()->getManager();

	    //$startDate = $request->request->get('startDate');
	    //$endDate = $request->request->get('endDate');

	    /* Get : SFOs performance data */

        /* Get : Nbr Enquêtes validées */

        $subRequest1 = "SELECT l.sfo_id, q.*  FROM questionnairedisponibilite q, localisation l
                WHERE l.id = q.localisation_id'";

        $subRequest2 = "SELECT l.sfo_id, q.*  FROM questionnaireshelfshare q, localisation l
                WHERE l.id = q.localisation_id'";

        $sql = "select CONCAT(sfo_t1.nom,' ',sfo_t1.prenom) as 'SFO', COUNT(distinct s1.questionnaire_id) + COUNT(distinct s2.questionnaire_id) as 'Nbr Rapports', IFNULL( AVG(s1.nbrLignesTraitees) + AVG(s2.nbrLignesTraitees), 0) as 'Moy. Nbr Lignes Traitees', IFNULL( AVG(s1.tempsremplissage) + AVG(s2.tempsremplissage), 0) as 'Moy. Temps remplissage'
				FROM sfo sfo_t1 LEFT OUTER JOIN
				(SELECT l.sfo_id, q.id as questionnaire_id, q.* FROM questionnairedisponibilite q, localisation l
				WHERE l.id = q.localisation_id) s1
				ON sfo_t1.id = s1.sfo_id LEFT OUTER JOIN
				(SELECT l.sfo_id, q.id as questionnaire_id, q.* FROM questionnaireshelfshare q, localisation l
				WHERE l.id = q.localisation_id) s2 ON sfo_t1.id = s2.sfo_id
				GROUP BY 1";
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }
        //"date_creation >= '" . $param_startDate . "' AND date_creation <= '" . $param_endDate ."'";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $dataArray = array();
        $gotColumnsNames = false;
        while ($row = $queryResult->fetch()) {
        	if(!$gotColumnsNames){
        		$dataArray[] = array_keys($row);
        		$gotColumnsNames = true;
        	}
            $dataArray[] = array_values($row);// remove keys, keeping only values
            //$dataArray[] = $row;
        }

	    $jsonContent = json_encode($dataArray, JSON_NUMERIC_CHECK);

	    $response = new Response($jsonContent);
		  $response->headers->set('Content-Type', 'application/json');
	    return $response;
    }

    public function getOutOfStockDataAction(){
    	$em = $this->getDoctrine()->getManager();

	    //$startDate = $request->request->get('startDate');
	    //$endDate = $request->request->get('endDate');

	    /* Get : SFOs performance data */

        /* Get : Nbr Enquêtes validées */

        $sql  = "SELECT DISTINCT pdv.ville, pdv.nom as 'PDV', p.sku, p.libelle FROM produit p, referencementproduit r, pdv, questionnairedisponibilite q, localisation l, questionnairedisponibilite_produit qd
                WHERE p.id = r.produit_id AND r.pdv_id = pdv.id AND qd.produit_id = p.id AND qd.questionnairedisponibilite_id = q.id AND l.id = q.localisation_id AND l.pdv_id = pdv.id AND qd.qte = 0";
        // $sql .=" q.date_creation >= '" . $startDate . "' AND q.date_creation <= '" . $endDate ."'";
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }
        //"date_creation >= '" . $param_startDate . "' AND date_creation <= '" . $param_endDate ."'";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $dataArray = array();
        $gotColumnsNames = false;
        while ($row = $queryResult->fetch()) {
        	if(!$gotColumnsNames){
        		$dataArray[] = array_keys($row);
        		$gotColumnsNames = true;
        	}
            $dataArray[] = array_values($row);// remove keys, keeping only values
            //$dataArray[] = $row;
        }

	    $jsonContent = json_encode($dataArray, JSON_NUMERIC_CHECK);

	    $response = new Response($jsonContent);
		  $response->headers->set('Content-Type', 'application/json');
	    return $response;
    }

    public function getSfoPlanningModelAction($sfoId){
    	$em = $this->getDoctrine()->getManager();

      $sql = "SELECT dayOfWeek, pdv_id FROM planningmodel pm, planningmodel_visite pmv
              WHERE pm.id = pmv.planningmodel_id AND sfo_id = " . $sfoId;

      $queryResult = $em->getConnection()->executeQuery($sql);
      while ($row = $queryResult->fetch()) {
          $exportedRowArray[] = $row;
      }
      //"date_creation >= '" . $param_startDate . "' AND date_creation <= '" . $param_endDate ."'";
      $queryResult = $em->getConnection()->executeQuery($sql);
      $dataArray = array();
      $gotColumnsNames = false;
      while ($row = $queryResult->fetch()) {
      	/*if(!$gotColumnsNames){
      		$dataArray[] = array_keys($row);
      		$gotColumnsNames = true;
      	}*/
        $dataArray[] = array_values($row);// remove keys, keeping only values
        //$dataArray[] = $row;
      }

	    $jsonContent = json_encode($dataArray, JSON_NUMERIC_CHECK);

	    $response = new Response($jsonContent);
		  $response->headers->set('Content-Type', 'application/json');
	    return $response;
    }

    public function getReferencementDataAction(Request $request){
    	$em = $this->getDoctrine()->getManager();

	    $pdvId = $request->request->get('pdvId');

	    /* Get : shelfshare data */
        $sql = "SELECT produit_id FROM referencementproduit WHERE pdv_id = $pdvId";
               //"date_creation >= '" . $param_startDate . "' AND date_creation <= '" . $param_endDate ."'";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $dataArray = array();
        $gotColumnsNames = false;
        while ($row = $queryResult->fetch()) {
            $dataArray[] = array_values($row);// remove keys, keeping only values
            //$dataArray[] = $row;
        }

	    $jsonContent = json_encode($dataArray, JSON_NUMERIC_CHECK);

	    $response = new Response($jsonContent);
		  $response->headers->set('Content-Type', 'application/json');
	    return $response;
    }

    public function saveReferencementDataAction(Request $request){
    	$em = $this->getDoctrine()->getManager();

	    $pdvId 				   = $request->request->get('pdvId');
	    $produitsReferencesArrayStr = $request->request->get('produitsReferencesArrayStr');
	    $produitsReferencesArray = explode(',', $produitsReferencesArrayStr);
	    /* Delete old references */
	    $sql = "DELETE FROM referencementproduit WHERE pdv_id = $pdvId";
	    $queryResult = $em->getConnection()->executeQuery($sql);

	    $sqlInsertSequence = "";
	    foreach($produitsReferencesArray as $produitId){
	    	$insertExpression  = "($produitId, $pdvId)";
	    	$sqlInsertSequence .= ($sqlInsertSequence == "")?$insertExpression:",".$insertExpression;
	    }
	    /* Insert new references */
        $sql = "INSERT INTO referencementproduit VALUES ". $sqlInsertSequence;

        $queryResult = $em->getConnection()->executeQuery($sql);
        return new Response('Done');
    }

    public function getGenerateRoutePlanDataAction(Request $request){
      $em = $this->getDoctrine()->getManager();

      $weekDate = $request->request->get('weekDate');

      /* Get : shelfshare data */
      $sql = "SELECT sfo_id FROM generate_routeplan WHERE week_date = '$weekDate'";
             //"date_creation >= '" . $param_startDate . "' AND date_creation <= '" . $param_endDate ."'";
      $queryResult = $em->getConnection()->executeQuery($sql);
      $dataArray = array();
      $gotColumnsNames = false;
      while ($row = $queryResult->fetch()) {
          $dataArray[] = array_values($row);// remove keys, keeping only values
          //$dataArray[] = $row;
      }

      $jsonContent = json_encode($dataArray, JSON_NUMERIC_CHECK);

      $response = new Response($jsonContent);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

    public function saveGenerateRoutePlanDataAction(Request $request){
      $em = $this->getDoctrine()->getManager();
      $weekDate           = $request->request->get('weekDate');
      $weekNum           = $request->request->get('weekNum');
      $sfosArrayStr = $request->request->get('sfosArrayStr');
      $sfosArrayStr = explode(',', $sfosArrayStr);
      /* Delete old references */
      $sql = "DELETE FROM generate_routeplan WHERE week_date = '$weekDate'";
      $queryResult = $em->getConnection()->executeQuery($sql);

      $sqlInsertSequence = "";
      foreach($sfosArrayStr as $sfoId){
        $insertExpression  = "('$weekNum', '$weekDate', $sfoId)";
        $sqlInsertSequence .= ($sqlInsertSequence == "")?$insertExpression:",".$insertExpression;
      }
      /* Insert new data */
      $sql = "INSERT INTO generate_routeplan VALUES ". $sqlInsertSequence;

      $queryResult = $em->getConnection()->executeQuery($sql);
      return new Response('Done');
    }

}
