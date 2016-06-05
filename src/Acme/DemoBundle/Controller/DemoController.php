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
        
      if($request->get('export_bdd') != null){
        $sql = "select r.id as rapportId, '' as 'N° animation', DATE_FORMAT(r.date_creation,'%Y-%m-%d') as 'Date', DATE_FORMAT(r.date_creation,'%H:%i') as 'Heure',
                p.ville, p.licence as 'Code PDV', dcl.licence as 'Code PDV Remplacé', p.nom as 'Enseigne', p.secteur as 'Secteur',
                t.libelle as 'Age', r.sexe as 'Sexe', mh.libelle as 'Marque habituelle', r.marquehabituelle_qte as 'Qte habituelle',
                ma.libelle as 'Marque achetée', r.marqueachetee_qte as 'Qte achetée', CONCAT(s.nom,' ', s.prenom) as 'Superviseur', CONCAT(a.nom,' ', a.prenom) as 'Animateur',CASE r.tombola
                WHEN 1 THEN 'Oui'
                ELSE 'Non'
                END as 'Tombola', r.commentaire as 'Commentaire'
                FROM rapport r, localisation l LEFT OUTER JOIN demandechangementlicence dcl ON dcl.localisation_id = l.id, pdv p, animateur a, trancheage t, marque mh, marque ma, superviseur s
                WHERE r.localisation_id = l.id AND l.pdv_id = p.id AND l.animateur_id = a.id AND a.superviseur_id = s.id AND r.trancheage_id = t.id AND
                r.marquehabituelle_id = mh.id AND r.marqueachetee_id = ma.id
                AND r.date_creation >= '$dateDebut' AND r.date_creation <= '$dateFin'
                ORDER BY date, a.id, p.licence, heure";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $exportedRowArray = array();
        while ($row = $queryResult->fetch()) {
          $exportedRowArray[] = $row;
        }

        // process and fill 'N° animation' column
        $lastPdvLicence = null;
        $lastPdvLicenceRemplace = null; 
        foreach($exportedRowArray as &$rapport){
          if($lastPdvLicence != $rapport['Code PDV'] || $lastPdvLicenceRemplace != $rapport['Code PDV Remplacé']){
            $rapport['N° animation'] = 1;
            $lastPdvLicence = $rapport['Code PDV'];
            $lastPdvLicenceRemplace = $rapport['Code PDV Remplacé'];
          }
        }

        // select cadeaux 
        $sql = "select r.id as rapportId, c.libelle
                FROM rapport r, rapport_cadeau rc, cadeau c
                WHERE r.id = rc.rapport_id AND  rc.cadeau_id = c.id
                AND r.date_creation >= '$dateDebut' AND r.date_creation <= '$dateFin'";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $cadeauxRapportArray = array();
        while ($row = $queryResult->fetch()) {
          $cadeauxRapportArray[] = $row;
        }

        // get distinct cadeaux
        $distinctCadeaux = array();
        foreach($cadeauxRapportArray as $cadeauRapport){
          if(!in_array($cadeauRapport['libelle'], $distinctCadeaux))
            $distinctCadeaux[] = $cadeauRapport['libelle'];
        }
        foreach($exportedRowArray as &$rapport){
          foreach($distinctCadeaux as $cadeau){
            $rapport[$cadeau] = '';
            foreach($cadeauxRapportArray as $cadeauRapport){
              if($rapport['rapportId'] == $cadeauRapport['rapportId'] && $cadeau == $cadeauRapport['libelle']){
                $rapport[$cadeau] = 1;
              }
            }
          }
        }

        if(count($exportedRowArray) == 0){
          $exportedRowArray[0] = array('Info' =>'Aucune donnée trouvée pour l\'interval spécifié');
        }

        $sourceIterator = new ArraySourceIterator($exportedRowArray);
        $filename = sprintf( 'export_global_%s.xls', date('Y_m_d_H_i_s', strtotime('now')) );
        return $this->get('sonata.admin.exporter')->getResponse('xls', $filename, $sourceIterator);
      }
      if($request->get('export_animations_report') != null){
        $sql = "select p.licence as 'PDV', CONCAT(a.nom,' ',a.prenom) as 'Animateur', count(distinct r.localisation_id) as 'Nbr Animations' 
                FROM rapport r, localisation l, animateur a, pdv p
                Where r.localisation_id = l.id ANd l.animateur_id = a.id AND l.pdv_id = p.id
                AND r.date_creation >= '$dateDebut' AND r.date_creation <= '$dateFin'
                GROUP BY PDV,Animateur
                ORDER BY 1 ASC";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $exportedRowArray = array();
        while ($row = $queryResult->fetch()) {
          $exportedRowArray[] = $row;
        }

        if(count($exportedRowArray) == 1 && $exportedRowArray[0]['PDV'] == null){
          $exportedRowArray[0] = array('Info' =>'Aucune donnée trouvée pour l\'interval spécifié');
        }
        $sourceIterator = new ArraySourceIterator($exportedRowArray);
        $filename = sprintf( 'export_animations_%s.xls', date('Y_m_d_H_i_s', strtotime('now')) );
        return $this->get('sonata.admin.exporter')->getResponse('xls', $filename, $sourceIterator);
      }
    }

    public function generateXlsFileExecute2Action()
    {
        $em = $this->getDoctrine()->getManager();
        $sql = "select p.licence, p.ville, p.secteur, animationsByPDV.nbrAnimations as 'Nbr. Animations', count(*) as 'Nbr. Contacts' FROM pdv p, localisation l, rapport r , (select p.id, count(*) nbrAnimations FROM pdv p, localisation l WHERE p.id = l.pdv_id group by 1) animationsByPDV  WHERE l.pdv_id = p.id AND r.localisation_id = l.id AND animationsByPDV.id = p.id GROUP BY p.licence, p.ville, p.secteur ORDER by ville, secteur";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $exportedRowArray = array();
        while ($row = $queryResult->fetch()) {
            $exportedRowArray[] = $row;
        }

        //################# atteints par trancheage par pdv
        $sql = "select licence, libelle, count(*) as atteints from trancheage ta, rapport r, localisation l, pdv p WHERE ta.id = r.trancheage_id AND r.localisation_id = l.id AND l.pdv_id = p.id AND r.achete = 1 GROUP BY 1,2";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $atteintsParTrancheAgeParPdv = array();
        while ($row = $queryResult->fetch()) {
            $atteintsParTrancheAgeParPdv[] = $row;
        }

        $sql = "select libelle from trancheage";
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $trancheAgeLibelle = $row['libelle'];
            $trancheAgeColumnTitle = $trancheAgeLibelle . ' (part)';
            for($i=0; $i < count($exportedRowArray);$i++){
                $trancheAgeColumn_rowValue = '0';
                foreach($atteintsParTrancheAgeParPdv as $atteintsParTrancheAge){
                    if($atteintsParTrancheAge['licence'] == $exportedRowArray[$i]['licence'] && $atteintsParTrancheAge['libelle'] == $trancheAgeLibelle){
                        $trancheAgeColumn_rowValue = $atteintsParTrancheAge['atteints'];
                    }
                }
                $exportedRowArray[$i][$trancheAgeColumnTitle] = $trancheAgeColumn_rowValue;
            }
        }

        //################# atteints par sexe par pdv
        $sql = "select licence, sexe, count(*) as atteints from rapport r, localisation l, pdv p WHERE r.localisation_id = l.id AND l.pdv_id = p.id AND r.achete = 1 GROUP BY 1,2";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $atteintsParSexeParPdv = array();
        while ($row = $queryResult->fetch()) {
            $atteintsParSexeParPdv[] = $row;
        }

        $sexeDataList = array('M','F');
        foreach($sexeDataList as $sexe) {
            $sexeLibelle = $sexe;
            $columnTitle = $sexeLibelle . ' (part)';
            for($i=0; $i < count($exportedRowArray);$i++){
                $column_rowValue = '0';
                foreach($atteintsParSexeParPdv as $atteintsParSexe){
                    if($atteintsParSexe['licence'] == $exportedRowArray[$i]['licence'] && $atteintsParSexe['sexe'] == $sexeLibelle){
                        $column_rowValue = $atteintsParSexe['atteints'];
                    }
                }
                $exportedRowArray[$i][$columnTitle] = $column_rowValue;
            }
        }

        //################# atteints par raisonachat par pdv
        $sql = "select licence, libelle, count(*) as atteints from rapport r, localisation l, raisonachat ra, pdv p WHERE r.raisonAchat_id = ra.id AND r.localisation_id = l.id AND l.pdv_id = p.id AND r.achete = 1 GROUP BY 1,2";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $atteintsParRaisonAchatParPdv = array();
        while ($row = $queryResult->fetch()) {
            $atteintsParRaisonAchatParPdv[] = $row;
        }

        $sql = "select libelle from raisonachat";
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $raisonAchatLibelle = $row['libelle'];
            $columnTitle = $raisonAchatLibelle . ' (part)';
            for($i=0; $i < count($exportedRowArray);$i++){
                $column_rowValue = '0';
                foreach($atteintsParRaisonAchatParPdv as $atteintsParRaisonAchat){
                    if($atteintsParRaisonAchat['licence'] == $exportedRowArray[$i]['licence'] && $atteintsParRaisonAchat['libelle'] == $raisonAchatLibelle){
                        $column_rowValue = $atteintsParRaisonAchat['atteints'];
                    }
                }
                $exportedRowArray[$i][$columnTitle] = $column_rowValue;
            }
        }

        //################# atteints par marque achetee - total par pdv
        $sql = "select licence, m.libelle, count(*) as atteints from rapport r, localisation l, marque m, pdv p WHERE r.marqueachetee_id = m.id AND r.localisation_id = l.id AND l.pdv_id = p.id AND r.achete = 1 GROUP BY 1,2";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $participationsParMarqueParPdv = array();
        while ($row = $queryResult->fetch()) {
            $participationsParMarqueParPdv[] = $row;
        }

        $sql = "select libelle from marque";
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $marqueLibelle = $row['libelle'];
            $columnTitle = 'p.'.$marqueLibelle;
            for($i=0; $i < count($exportedRowArray);$i++){
                $column_rowValue = '0';
                foreach($participationsParMarqueParPdv as $atteintsParMarque){
                    if($atteintsParMarque['licence'] == $exportedRowArray[$i]['licence'] && $atteintsParMarque['libelle'] == $marqueLibelle){
                        $column_rowValue = $atteintsParMarque['atteints'];
                    }
                }
                $exportedRowArray[$i][$columnTitle] = $column_rowValue;
            }
        }

        //################# refus par marque achetee  par pdv
        $sql = "select licence, m.libelle, count(*) as atteints from rapport r, localisation l, marque m, pdv p WHERE r.marquehabituelle_id = m.id AND r.localisation_id = l.id AND l.pdv_id = p.id AND r.achete = 0 GROUP BY 1,2";
        $queryResult = $em->getConnection()->executeQuery($sql);
        $refusParMarqueParPdv = array();
        while ($row = $queryResult->fetch()) {
            $refusParMarqueParPdv[] = $row;
        }

        $sql = "select libelle from marque";
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $marqueLibelle = $row['libelle'];
            $columnTitle = 'r.'.$marqueLibelle;
            for($i=0; $i < count($exportedRowArray);$i++){
                $column_rowValue = '0';
                foreach($refusParMarqueParPdv as $atteintsParMarque){
                    if($atteintsParMarque['licence'] == $exportedRowArray[$i]['licence'] && $atteintsParMarque['libelle'] == $marqueLibelle){
                        $column_rowValue = $atteintsParMarque['atteints'];
                    }
                }
                $exportedRowArray[$i][$columnTitle] = $column_rowValue;
            }
        }

        //  total

        $sql = "select libelle from marque";
        $queryResult = $em->getConnection()->executeQuery($sql);
        while ($row = $queryResult->fetch()) {
            $marqueLibelle = $row['libelle'];
            $columnTitle = $marqueLibelle;
            for($i=0; $i < count($exportedRowArray);$i++){
                $column_rowValue = '0';
                $j = 0;
                foreach($participationsParMarqueParPdv as $atteintsParMarque){
                    if($atteintsParMarque['licence'] == $exportedRowArray[$i]['licence'] && $atteintsParMarque['libelle'] == $marqueLibelle){
                        if(count($participationsParMarqueParPdv) > $j)
                          $column_rowValue = $participationsParMarqueParPdv[$j]['atteints'];
                        if(count($refusParMarqueParPdv) > $j)
                          $column_rowValue += $refusParMarqueParPdv[$j]['atteints'];
                    }
                    $j++;
                }
                $exportedRowArray[$i][$columnTitle] = $column_rowValue;
            }
        }


        $sourceIterator = new ArraySourceIterator($exportedRowArray);
        $filename = sprintf( 'export_global_%s.xls', date('Y_m_d_H_i_s', strtotime('now')) );
        /*
        $fields = array('ville');
        $sourceIterator = new DoctrineORMQuerySourceIterator($query, $fields);*/
        return $this->get('sonata.admin.exporter')->getResponse('xls', $filename, $sourceIterator);
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
                    return new RedirectResponse($this->generateUrl('sonata_admin_dashboard'));
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
                return new RedirectResponse($this->generateUrl('sonata_admin_dashboard'));
            }
        }

        if ($this->get('security.context')->isGranted('ROLE_ADMIN') == true)
            return new RedirectResponse($this->generateUrl('sonata_admin_dashboard'));
    }

}
