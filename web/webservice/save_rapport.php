<?php

    include('_params.php');
    
    $localisationId              = $_REQUEST['localisationId'];

    $achete                = $_REQUEST['achete'];
    $trancheAgeId                = $_REQUEST['trancheAgeId'];
    $sexe             = $_REQUEST['sexe'];
    $raisonAchatId             = $_REQUEST['raisonAchatId'];
    $raisonRefusId             = $_REQUEST['raisonRefusId'];
    $fidelite             = utf8_decode($_REQUEST['fidelite']);
    $marqueHabituelleId             = $_REQUEST['marqueHabituelleId'];
    $marqueHabituelleQte             = $_REQUEST['marqueHabituelleQte'];
    $marqueAcheteeId     = $_REQUEST['marqueAcheteeId'];
    $marqueAcheteeQte      = $_REQUEST['marqueAcheteeQte'];
    $cadeauxIds             = (!empty($_REQUEST['cadeauxIds']))?$_REQUEST['cadeauxIds']:'NULL';
    $tombola             = $_REQUEST['tombola'];
    $commentaire              = $_REQUEST['commentaire'];
    $dateCreation              = utf8_decode($_REQUEST['dateCreation']);

    $con = mysql_connect($host,$uname,$pwd) or die("connection failed");
    mysql_select_db($db,$con) or die("db selection failed");

    if($achete == 1){
        $request = "insert into rapport (achete, trancheage_id, sexe, fidelite, raisonachat_id , marquehabituelle_id, marquehabituelle_qte, marqueachetee_id, marqueachetee_qte, tombola, localisation_id, date_creation)
                                    values ($achete, $trancheAgeId, '$sexe', '$fidelite', $raisonAchatId, $marqueHabituelleId, $marqueHabituelleQte, $marqueAcheteeId, $marqueAcheteeQte, $tombola, $localisationId, '$dateCreation') ";

        if($r=mysql_query($request, $con))
        {
            $flag['code']=1;
        }
        $rapportId = mysql_insert_id();
        // insert of cadeaux
        $cadeauxIdsArray = explode(',',$cadeauxIds);
        foreach($cadeauxIdsArray as $cadeauId){
            $request = "insert into rapport_cadeau values($rapportId, $cadeauId)";
            mysql_query($request, $con);
        }
    }else{
        $request = "insert into rapport (achete, trancheage_id, sexe, raisonrefus_id, marquehabituelle_id, marquehabituelle_qte, commentaire, localisation_id, date_creation)
                                    values ($achete, '$trancheAgeId', '$sexe', $raisonRefusId, $marqueHabituelleId, $marqueHabituelleQte, '$commentaire', $localisationId, '$dateCreation') ";
        if($r=mysql_query($request, $con))
        {
            $flag['code']=1;
        }
    }

    //$flag['code']=0;

    // print(json_encode($flag));
    // echo $request;
    echo 'Le rapport a bien été enregistré !';
    mysql_close($con);
?>
