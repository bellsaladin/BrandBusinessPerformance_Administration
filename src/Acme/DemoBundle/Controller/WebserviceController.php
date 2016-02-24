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
}



