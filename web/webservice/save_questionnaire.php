<?php

    include('_params.php');
    
    $typeQuestionnaire          = $_REQUEST['type'];
    $localisationId             = $_REQUEST['localisationId'];
    $quantitiesData             = $_REQUEST['quantitiesData'];
    $dateCreation               = utf8_decode($_REQUEST['dateCreation']);

    $con = mysql_connect($host,$uname,$pwd) or die("connection failed");
    mysql_select_db($db,$con) or die("db selection failed");

    if($typeQuestionnaire == 'SHELFSHARE'){
        $request = "insert into questionnaireshelfshare (localisation_id, date_creation)
                                    values ( $localisationId, '$dateCreation') ";

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
        $request = "insert into questionnairedisponibilite (localisation_id, date_creation)
                                    values ( $localisationId, '$dateCreation') ";

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
            $produitId = $quantityArray[2];
            $qte = $quantityArray[3];
            $request = "insert into questionnairedisponibilite_produit values($questionnaireId, $categorieProduitsId, $produitId, $poiId, $qte)";
            mysql_query($request, $con);
        }
    }


    //$flag['code']=0;

    // print(json_encode($flag));
    // echo $request;
    echo 'Le questionnaire a bien été enregistré !';
    mysql_close($con);
?>
