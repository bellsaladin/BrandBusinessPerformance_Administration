<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Acme\DemoBundle\Form\ContactType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sonata\CoreBundle\Exporter;
use Exporter\Source\DoctrineORMQuerySourceIterator;
use Exporter\Source\ArraySourceIterator;

class DemoController extends Controller
{

    public function tableauDeBoardAction(Request $request){
        $admin_pool = $this->get('sonata.admin.pool');
        $em = $this->getDoctrine()->getManager();

        /* get parameters */
        $param_startDate = date('Y-m-d', strtotime("-7 day"));
        $param_endDate   = date('Y-m-d');
        $param_sfoId     = NULL;

        $param_startDate = $request->request->get('startDate', $param_startDate);
        $param_endDate   = $request->request->get('endDate', $param_endDate);
        $param_sfoId     = $request->request->get('sfoId', $param_sfoId);

        $params = array('startDate' => $param_startDate, 'endDate' => $param_endDate, 'sfoId' => $param_sfoId);

        /* Get : Nombre de magasins visités */
        $sql = "select COUNT( DISTINCT pdv_id, date(date_creation)) as 'NbrPDVVisites' FROM localisation l " .
               "WHERE date_creation >= '" . $param_startDate . "' AND date_creation <= '" . $param_endDate ."'";
        if($param_sfoId != NULL)
          $sql .= " AND sfo_id = $param_sfoId ";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $exportedRowArray = array();
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }
        /* Get : Nombre de magasins à visiter dans le planning */
        $sql = "select COUNT( DISTINCT pdv_id) as 'NbrVisitesPdvPlannifiees' FROM planning p, visite v ".
                "WHERE p.id = v.planning_id AND DATE_ADD(datedebut_semaine, INTERVAL dayOfWeek - 1 DAY) >= '" . $param_startDate . "' AND DATE_ADD(datedebut_semaine, INTERVAL dayOfWeek - 1 DAY) <= '" . $param_endDate ."'";
        if($param_sfoId != NULL)
          $sql .= " AND sfo_id = $param_sfoId ";

        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }
        /* Get : Nombre de lignes renseignées & temps de saisie - questionnaires disponibilite */
        $sql = "select SUM(nbrLignesTraitees) as 'nbrLignesTraiteesRapportsDisponibilite', SUM(tempsRemplissage) as 'tempsSaisieRapportsDisponibilite' FROM questionnairedisponibilite q, localisation l ".
                "WHERE l.id = q.localisation_id AND q.date_creation >= '" . $param_startDate . "' AND q.date_creation <= '" . $param_endDate ."'";
        if($param_sfoId != NULL)
          $sql .= " AND sfo_id = $param_sfoId ";

        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }
        /* Get : Nombre de lignes renseignées & temps de saisie - questionnaires shelfshare */
        $sql = "select SUM(nbrLignesTraitees) as 'nbrLignesTraiteesRapportsShelfShare', SUM(tempsRemplissage) as 'tempsSaisieRapportsShelfShare' FROM questionnaireshelfshare q, localisation l ".
                "WHERE l.id = q.localisation_id AND q.date_creation >= '" . $param_startDate . "' AND q.date_creation <= '" . $param_endDate ."'";
        if($param_sfoId != NULL)
          $sql .= " AND sfo_id = $param_sfoId ";

        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }

        /* Get : Nbr Enquêtes validées */

        $subRequest1 = "SELECT count(*) as nbrRapportsValides  FROM questionnairedisponibilite q, localisation l
                WHERE l.id = q.localisation_id AND q.date_creation >= '" . $param_startDate . "' AND q.date_creation <= '" . $param_endDate ."' AND valide = 1";
        if($param_sfoId != NULL)
          $subRequest1 .= " AND sfo_id = $param_sfoId ";

        $subRequest2 = "SELECT count(*) as nbrRapportsValides  FROM questionnaireshelfshare q, localisation l
                WHERE l.id = q.localisation_id AND q.date_creation >= '" . $param_startDate . "' AND q.date_creation <= '" . $param_endDate ."' AND valide = 1";
        if($param_sfoId != NULL)
          $subRequest2 .= " AND sfo_id = $param_sfoId ";

        $sql = "select s1.nbrRapportsValides + s2.nbrRapportsValides as nbrRapportsValides
                FROM ($subRequest1) as s1, ($subRequest2) as s2";
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }

        /* Get : Nbr Enquêtes total*/

        $subRequest1 = "SELECT count(*) as nbrRapports FROM questionnairedisponibilite q, localisation l
                WHERE l.id = q.localisation_id AND q.date_creation >= '" . $param_startDate . "' AND q.date_creation <= '" . $param_endDate ."' ";
        if($param_sfoId != NULL)
          $subRequest1 .= " AND sfo_id = $param_sfoId ";

        $subRequest2 = "SELECT count(*) as nbrRapports  FROM questionnaireshelfshare q, localisation l
                WHERE l.id = q.localisation_id AND q.date_creation >= '" . $param_startDate . "' AND q.date_creation <= '" . $param_endDate ."'";
        if($param_sfoId != NULL)
          $subRequest2 .= " AND sfo_id = $param_sfoId ";

        $sql = "select s1.nbrRapports + s2.nbrRapports as nbrRapports
                FROM ($subRequest1) as s1, ($subRequest2) as s2";
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }
        /* Get : Nombre de magasins rajoutés */
        $sql = "select COUNT( *) as 'nbrMagasinsRajoutes' FROM pdv p ".
                "WHERE date_creation >= '" . $param_startDate . "' AND date_creation <= '" . $param_endDate ."'";

        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }
        /* Get : Nombre de references rajoutées */
        $sql = "select COUNT( *) as 'nbrReferencesRajoutees' FROM produit p ".
                "WHERE date_creation >= '" . $param_startDate . "' AND date_creation <= '" . $param_endDate ."'";

        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }

        return $this->render('AcmeDemoBundle::tableauDeBord.html.twig',array('admin_pool' => $admin_pool,'exportedRowArray'=>$exportedRowArray , 'params' => $params));
    }


    public function referencementAction(Request $request){
      $admin_pool = $this->get('sonata.admin.pool');
      $em = $this->getDoctrine()->getManager();
      $data = array();
      // get : Produit List
      $sql = "select id, sku FROM produit";
      $queryResult = $em->getConnection()->executeQuery($sql);
      while ($row = $queryResult->fetch()) {
            $data['produitList'][] = $row;
      }
      // get : PDV List
      $sql = "select id, nom FROM pdv";
      $queryResult = $em->getConnection()->executeQuery($sql);
      while ($row = $queryResult->fetch()) {
            $data['pdvList'][] = $row;
      }
      //$data['produits'] = $this->getDoctrine()->getRepository('AcmeDemoBundle:Produit')->findAll();
      return $this->render('AcmeDemoBundle::referencement.html.twig',array('admin_pool' => $admin_pool,'data'=>$data));
    }

    public function mapDashboardAction()
    {
        $admin_pool = $this->get('sonata.admin.pool');
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();


        $pdvsList = $em->getRepository('AcmeDemoBundle:Pdv')->findAll();
        $animateursList = $em->getRepository('AcmeDemoBundle:Animateur')->findAll();
        $animateursAndLastLocalisationList = array();
        foreach($animateursList as $animateur){

            $sql = "select l.*
                    FROM localisation l
                    WHERE animateur_id = ".$animateur->getId()."
                    AND l.date_creation >= '".date('Y-m-d 0:0:0')."' AND l.date_creation <= '".date('Y-m-d 23:59:59')."'
                    ORDER BY l.date_creation DESC";
            $localisations = $em->getConnection()->fetchAssoc($sql);
            $localisation = ($localisations > 0)?$localisations:null;
            if($localisation != null)
                $animateursAndLastLocalisationList[] = array('animateur' => $animateur, 'localisation' => $localisation);
        }


        return $this->render('AcmeDemoBundle::map_dashboard.html.twig',array('admin_pool' => $admin_pool,'animateursAndLastLocalisationList'=>$animateursAndLastLocalisationList ,'pdvsList' => $pdvsList));
    }

    public function statisticsAction()
    {
        $admin_pool = $this->get('sonata.admin.pool');
        $em = $this->getDoctrine()->getManager();

        // $dql = "SELECT p, 4 FROM Acme\DemoBundle\Entity\Localisation l JOIN Acme\DemoBundle\Entity\Pdv p WHERE l.pdv = p";
        // $dql = "SELECT p, 4 FROM Acme\DemoBundle\Entity\Pdv p JOIN Acme\DemoBundle\Entity\Localisation l WHERE l.pdv = p  JOIN Acme\DemoBundle\Entity\Rapport r WHERE r.localisation = l";
        $dql = "SELECT p.ville, SUM(r.marqueacheteeQte) FROM Acme\DemoBundle\Entity\Pdv p JOIN Acme\DemoBundle\Entity\Localisation l WHERE l.pdv = p  JOIN Acme\DemoBundle\Entity\Rapport r WHERE r.localisation = l GROUP BY p.ville";
        $query = $em->createQuery($dql);
        //$query->setParameter('id', $class->getId());
        $result_ventesParVille = $query->execute();

        $sql = 'Select t1.atteints, t2.refus FROM (SELECT count(*) as atteints FROM rapport where achete = 1) t1, (SELECT count(*) as refus FROM rapport where achete = 0) t2';
        $result_contactsAtteintsEtRefus = $em->getConnection()->fetchAssoc($sql);

        $sql = 'select t1.ville, atteints, refus FROM
                (SELECT ville, count(*) as atteints FROM rapport r, localisation l, pdv p where r.localisation_id = l.id AND l.pdv_id = p.id AND achete = 1 GROUP BY ville)
                t1 LEFT JOIN (SELECT ville, count(*) as refus FROM rapport r, localisation l, pdv p where r.localisation_id = l.id AND l.pdv_id = p.id AND achete = 0 GROUP BY ville) t2 ON t1.ville = t2.ville';
        $result_contactsAtteintsEtRefusParVille = array();
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $result_contactsAtteintsEtRefusParVille[] = $row;
        }

        $sql = 'select m.libelle, count(*) as nbrContacts from marque m, rapport r WHERE r.marquehabituelle_id = m.id group by m.libelle';
        $result_provenanceContacts = array();
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $result_provenanceContacts[] = $row;
        }

        $sql = 'select m.libelle, count(*) as nbrContacts from marque m, rapport r WHERE r.marquehabituelle_id = m.id AND r.achete = 1 group by m.libelle';
        $result_provenanceContactsAtteints = array();
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $result_provenanceContactsAtteints[] = $row;
        }

        $sql = 'select ta.libelle, count(*) nbrContacts from trancheage ta, rapport r WHERE r.trancheage_id = ta.id AND r.achete = 1 group by ta.libelle';
        $result_profilAgeContactsAtteints = array();
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $result_profilAgeContactsAtteints[] = $row;
        }

        $sql ='select sexe, count(*) nbrContacts from rapport r WHERE  r.achete = 1 group by sexe';
        $result_profilSexeContactsAtteints = array();
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $result_profilSexeContactsAtteints[] = $row;
        }

        $sql = 'select ra.libelle, count(*) nbrContacts from raisonachat ra, rapport r WHERE  ra.id = r.raisonachat_id AND r.achete = 1 group by libelle';
        $result_raisonParticipation = array();
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $result_raisonParticipation[] = $row;
        }

        $sql = "select fidelite, count(*) nbrContactReussis from rapport r WHERE r.achete = 1 group by 1";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $result_fideliteMarque = array();
        while ($row = $queryResult->fetch()) {
            $result_fideliteMarque[] = $row;
        }

        $sql = 'select m.libelle, count(*) nbrContacts from marque m, rapport r WHERE  m.id = r.marquehabituelle_id AND r.achete = 0 group by libelle';
        $result_provenanceRefus = array();
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $result_provenanceRefus[] = $row;
        }

        return $this->render('AcmeDemoBundle::statistics.html.twig',
                            array('admin_pool' => $admin_pool,
                                  'result_ventesParVille'=>$result_ventesParVille,
                                  'result_contactsAtteintsEtRefus' => $result_contactsAtteintsEtRefus,
                                  'result_contactsAtteintsEtRefusParVille' => $result_contactsAtteintsEtRefusParVille,
                                  'result_provenanceContacts' => $result_provenanceContacts,
                                  'result_provenanceContactsAtteints' => $result_provenanceContactsAtteints,
                                  'result_profilAgeContactsAtteints' => $result_profilAgeContactsAtteints,
                                  'result_profilSexeContactsAtteints' => $result_profilSexeContactsAtteints,
                                  'result_raisonParticipation' => $result_raisonParticipation,
                                  'result_fideliteMarque' => $result_fideliteMarque,
                                  'result_provenanceRefus' => $result_provenanceRefus));
    }

    public function toggleActivateTombolaAction()
    {
      $em = $this->getDoctrine()->getManager();
      $sql = "SELECT `value` FROM `parameters` WHERE `key` = 'PARAM_TOMBOLA_ENABLED'";
      $queryResult = $em->getConnection()->executeQuery($sql);
      $row = $queryResult->fetch();
      $tombolaActive = $row['value'];

      if($tombolaActive == '0'){
        $tombolaActive = '1';
      }
      else{
        $tombolaActive = '0';
      }

      $sql = "UPDATE `parameters` SET `value` = '$tombolaActive' WHERE `key` = 'PARAM_TOMBOLA_ENABLED'";
      $em->getConnection()->executeQuery($sql);
      return new RedirectResponse($this->generateUrl('_generateXlsFileView'));
    }

    public function generateXlsFileViewAction()
    {
      $em = $this->getDoctrine()->getManager();
      $sql = "SELECT `value` FROM `parameters` WHERE `key` = 'PARAM_TOMBOLA_ENABLED'";
      $queryResult = $em->getConnection()->executeQuery($sql);
      $row = $queryResult->fetch();
      $tombolaActive = $row['value'];
      $admin_pool = $this->get('sonata.admin.pool');
      return $this->render('AcmeDemoBundle::exporter_vers_excel.html.twig', array('admin_pool' => $admin_pool, 'tombolaActive' => $tombolaActive));
    }

    public function uploadXlsFileForClientViewAction()
    {
      $admin_pool = $this->get('sonata.admin.pool');
      return $this->render('AcmeDemoBundle::uploadXlsFileForClient.html.twig', array('admin_pool' => $admin_pool));
    }

    public function uploadXlsFileForClientExecuteAction(Request $request)
    {
      $directory = dirname(__FILE__).'/../../../../web/bundles/acmedemo/fichierClient';
      $uploadDone = false;
      $fichier = $request->files->get('fichier');
      if(isset($fichier)){
        $request->files->get('fichier')->move($directory, 'fichier.xls');
        $uploadDone = true;
      }
      $admin_pool = $this->get('sonata.admin.pool');
      return $this->render('AcmeDemoBundle::uploadXlsFileForClient.html.twig',
                        array('admin_pool' => $admin_pool, 'uploadDone' => $uploadDone ));
    }

    public function generateXlsFileExecuteAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $dateDebut = \DateTime::createFromFormat('d/m/Y', $request->request->get('date_debut'))->format('Y-m-d');
      $dateFin = \DateTime::createFromFormat('d/m/Y', $request->request->get('date_fin'))->format('Y-m-d');

      if($request->get('export_data1') != null){
        $sql = "SELECT q.date_creation 'Date', p.nom 'PDV', sfo.nom 'SFO', q.valide 'Validé', m.libelle 'Marque', cat.name 'Catégorie', seg.name 'Segment', poi.libelle 'POI', qte 'Quantité'
                FROM questionnaireshelfshare q, questionnaireshelfshare_marque qm, localisation l, pdv p, sfo, marque m, poi,
                     classification__category cat, classification__category seg
                WHERE q.localisation_id = l.id AND l.pdv_id = p.id AND l.sfo_id = sfo.id AND
                 qm.questionnaireshelfshare_id = q.id AND qm.categorieProduits_id = cat.id AND
                 qm.segment_id = seg.id AND qm.poi_id = poi.id
                AND q.date_creation >= '$dateDebut' AND q.date_creation <= '$dateFin'
                ORDER BY q.date_creation";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $exportedRowArray = array();
        while ($row = $queryResult->fetch()) {
          $exportedRowArray[] = $row;
        }

        if(count($exportedRowArray) == 0){
          $exportedRowArray[0] = array('Info' =>'Aucune donnée trouvée pour l\'interval spécifié');
        }

        $sourceIterator = new ArraySourceIterator($exportedRowArray);
        $filename = sprintf( 'export_shelfshare_%s.xls', date('Y_m_d_H_i_s', strtotime('now')) );
        return $this->get('sonata.admin.exporter')->getResponse('xls', $filename, $sourceIterator);
      }

      if($request->get('export_data2') != null){
        $sql = "SELECT q.date_creation 'Date', p.nom 'PDV', sfo.nom 'SFO', q.valide 'Validé', produit.sku 'SKU', cat.name 'Catégorie', poi.libelle 'POI', qte 'Quantité'
                FROM questionnairedisponibilite q, questionnairedisponibilite_produit qm, localisation l, pdv p, sfo, produit, poi,
                     classification__category cat
                WHERE q.localisation_id = l.id AND l.pdv_id = p.id AND l.sfo_id = sfo.id AND
                 qm.questionnairedisponibilite_id = q.id AND qm.categorieProduits_id = cat.id AND
                 qm.poi_id = poi.id AND produit.id = qm.produit_id AND
                 q.date_creation >= '$dateDebut' AND q.date_creation <= '$dateFin'
                ORDER BY q.date_creation";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $exportedRowArray = array();
        while ($row = $queryResult->fetch()) {
          $exportedRowArray[] = $row;
        }

        if(count($exportedRowArray) == 0){
          $exportedRowArray[0] = array('Info' =>'Aucune donnée trouvée pour l\'interval spécifié');
        }

        $sourceIterator = new ArraySourceIterator($exportedRowArray);
        $filename = sprintf( 'export_availability_%s.xls', date('Y_m_d_H_i_s', strtotime('now')) );
        return $this->get('sonata.admin.exporter')->getResponse('xls', $filename, $sourceIterator);
      }
    }

    public function checkIfAccessAllowedAction(){
        if ($this->get('security.context')->isGranted('ROLE_CLIENT') == true){
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();

            // get FosUserUser entity using the found user's id
            $client = $em->getRepository('AcmeDemoBundle:Client')->findOneBy(array('user' => $user->getId()));

             //echo $entity->getId();
            if ($client) {
                if(!$client->getEnabled()){
                    return new Response('<div style="position:absolute;text-align:center;top:50%; width:99%">Les données sont en cours de traitement...<br/> Reéssayer plus tard !'.$user->getId().'</div>');
                }else{
                    return new RedirectResponse($this->generateUrl('_tableauDeBoard'));
                }
            }
        }
        if ($this->get('security.context')->isGranted('ROLE_SUPERVISEUR') == true){
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();

            // get FosUserUser entity using the found user's id
            $superviseur = $em->getRepository('AcmeDemoBundle:Superviseur')->findOneBy(array('user' => $user->getId()));

             //echo $entity->getId();
            if ($superviseur) {
                return new RedirectResponse($this->generateUrl('_tableauDeBoard'));
            }
        }

        if ($this->get('security.context')->isGranted('ROLE_ADMIN') == true)
            return new RedirectResponse($this->generateUrl('_tableauDeBoard'));
    }

}
