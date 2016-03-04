<?php

    include('_params.php');

    // ini_set('error_reporting', E_ALL);

    $sfoId              = $_REQUEST['sfoId'];
    $pdvId              = $_REQUEST['pdvId'];
    $longitude          = $_REQUEST['longitude'];
    $latitude           = $_REQUEST['latitude'];
    $licenceRemplacee   = $_REQUEST['licenceRemplacee'];
    $motif              = $_REQUEST['motif'];
    $dateCreation       = $_REQUEST['dateCreation'];

    // Get image string posted from Android App
    $base=$_REQUEST['image'];
    $imageBaseFileName = end(explode('/',$_REQUEST['imageFileName']));
    // Get file name posted from Android App
    $imageFileName = $sfoId.'_'. $pdvId.'_'.$imageBaseFileName;

    // Decode Image
    $binary=base64_decode($base);
    header('Content-Type: bitmap; charset=utf-8');
    // Images will be saved under 'www/imgupload/uplodedimages' folder
    $file = fopen('uploaded/'. $imageFileName, 'wb');
    // Create File
    fwrite($file, $binary);
    fclose($file);

    $con = mysql_connect($host,$uname,$pwd) or die("connection failed");
    mysql_select_db($db,$con) or die("db selection failed");

    $localisationId = 0;
    if($r=mysql_query("insert into localisation (imagefilename, longitude, latitude, sfo_id, pdv_id, date_creation)
                        values('$imageFileName', $longitude, $latitude, $sfoId, $pdvId, '$dateCreation') ",$con))
    {
        $flag['code']=1;
    }
    $localisationId = mysql_insert_id();

    // S'il y a une demande de changement de licence
    if(!empty($licenceRemplacee)){
       $r = mysql_query("insert into demandechangementlicence (licence, motif, localisation_id)
                        values($licenceRemplacee,'$motif',$localisationId) ",$con);

    }

    // print(json_encode($flag));
    // echo 'Les informations de localisation on été enregistrées !';
    echo $localisationId;

    mysql_close($con);
?>
