<?php

    include('_params.php');

    $typeQuestionnaire          = $_REQUEST['type'];
    $localisationId             = $_REQUEST['localisationId'];
    $nbrLignesTraitees          = $_REQUEST['nbrLignesTraitees'];
    $tempsRemplissage           = $_REQUEST['tempsRemplissage'];

    $quantitiesData             = $_REQUEST['quantitiesData'];
    $dateCreation               = utf8_decode($_REQUEST['dateCreation']);

    $con = mysql_connect($host,$uname,$pwd) or die("connection failed");
    mysql_select_db($db,$con) or die("db selection failed");

    if($typeQuestionnaire == 'SHELFSHARE'){
        $request = "insert into questionnaireshelfshare (localisation_id, date_creation, nbrLignesTraitees, tempsRemplissage) values ( $localisationId, '$dateCreation', $nbrLignesTraitees, $tempsRemplissage)";

        if($r=mysql_query($request, $con))
        {
            $flag['code']=1;
        }
        $questionnaireId = mysql_insert_id();
        // insert of cadeaux
        $quantitiesArray = explode('||',$quantitiesData);
        foreach($quantitiesArray as $quantity){
            $quantityArray = explode(';',$quantity);
            $poiId = $quantityArray[0];
            $categorieProduitsId = $quantityArray[1];
            $segmentId = $quantityArray[2];
            $marqueId = $quantityArray[3];
            $qte = $quantityArray[4];
            $request = "insert into questionnaireshelfshare_marque values($questionnaireId, $categorieProduitsId, $marqueId, $segmentId, $poiId, $qte)";
            mysql_query($request, $con);
        }
    }

    if($typeQuestionnaire == 'DISPONIBILITE'){
        $request = "insert into questionnairedisponibilite (localisation_id, date_creation, nbrLignesTraitees, tempsRemplissage) values ( $localisationId, '$dateCreation', $nbrLignesTraitees, $tempsRemplissage) ";

        if($r=mysql_query($request, $con))
        {
            $flag['code']=1;
        }
        $questionnaireId = mysql_insert_id();
        // insert of cadeaux
        $quantitiesArray = explode('||',$quantitiesData);
        foreach($quantitiesArray as $quantity){
            $quantityArray = explode(';', $quantity);
            $newProduit = $quantityArray[0];
            $type = $quantityArray[1];
            $poiId = $quantityArray[2];
            //$categorieProduitsId = $quantityArray[3];
            $produitId = $quantityArray[3];
            $qte = $quantityArray[4];

            if($newProduit == 1){
                $produit = null;
                $sku = $produitId;
                $result  = mysql_query("SELECT * FROM produit WHERE sku = '$sku'",$con);
                $produit = mysql_fetch_array($result);
                if(!$produit){
                  $request = "insert into produit (entityType, sku, libelle) values('$type','$sku', '$sku')";
                  mysql_query($request, $con);
                  $produitId = mysql_insert_id();
                }else{
                  $produitId = $produit['sku'];
                }
            }
            // check if the product exists
            $request = "insert into questionnairedisponibilite_produit values($questionnaireId, $produitId, $poiId, $qte)";
            mysql_query($request, $con);
        }
    }

    $thisMonday = date("Y-m-d",strtotime('monday this week'));

    // set corresponding visite status to done
    $request = "UPDATE visite v JOIN planning p ON v.planning_id = p.id JOIN localisation l ON v.pdv_id = l.pdv_id  SET v.done = 1 WHERE l.id = $localisationId AND p.datedebut_semaine = '$thisMonday' ";
    mysql_query($request, $con);


    //$flag['code']=0;

    // print(json_encode($flag));
    // echo $request;
    echo 'Le questionnaire a bien été enregistré !'. $nbrLignesTraitees;
    mysql_close($con);
?>
