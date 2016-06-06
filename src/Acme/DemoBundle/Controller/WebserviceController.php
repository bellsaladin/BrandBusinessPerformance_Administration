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
}



