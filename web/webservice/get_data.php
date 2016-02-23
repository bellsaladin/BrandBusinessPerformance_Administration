<?php
    include('_params.php');

    $con = mysql_connect($host,$uname,$pwd) or die("connection failed");
    mysql_select_db($db,$con) or die("db selection failed");

    $animateurId = $_REQUEST['animateurId'];

    //$r  = mysql_query("select * from marque where id='$id'",$con);

    // ****************************************************************************************************
    //                                      GET MARQUE
    // ****************************************************************************************************

    $r  = mysql_query("select * from marque where enabled = 1",$con);
    $marques = array();
    while($row=mysql_fetch_array($r))
    {
        // $response['libelle']=$row['libelle'];
        $marques[] = array('id'=>$row['id'],'libelle'=> utf8_encode($row['libelle']));
    }

    // ****************************************************************************************************
    //                                      GET RaisonAchat
    // ****************************************************************************************************

    $r  = mysql_query("select * from raisonachat where enabled = 1",$con);
    $raisonsAchat = array();
    while($row=mysql_fetch_array($r))
    {
        // $response['libelle']=$row['libelle'];
        $raisonsAchat[] = array('id'=>$row['id'],'libelle'=> utf8_encode($row['libelle']));
    }

    // ****************************************************************************************************
    //                                      GET RaisonRefu
    // ****************************************************************************************************

    $r  = mysql_query("select * from raisonrefus where enabled = 1",$con);
    $raisonsRefus = array();
    while($row=mysql_fetch_array($r))
    {
        // $response['libelle']=$row['libelle'];
        $raisonsRefus[] = array('id'=>$row['id'],'libelle'=> utf8_encode($row['libelle']));
    }

    // ****************************************************************************************************
    //                                      GET TrancheAge
    // ****************************************************************************************************

    $r  = mysql_query("select * from trancheage where enabled = 1",$con);
    $tranchesAge = array();
    while($row=mysql_fetch_array($r))
    {
        // $response['libelle']=$row['libelle'];
        $tranchesAge[] = array('id'=>$row['id'],'libelle'=> utf8_encode($row['libelle']));
    }

    // ****************************************************************************************************
    //                                      GET PDVs
    // ****************************************************************************************************
    //$nextMonday = date("Y-m-d",strtotime('monday next week'));
    //$r  = mysql_query("select distinct p.* from planning pl, planning_pdv pp, pdv p WHERE p.id = pp.pdv_id AND pp.planning_id = pl.id AND pl.animateur_id = $animateurId AND datedebut_semaine = '" . $nextMonday ."'",$con);
    $r  = mysql_query("select distinct p.* from pdv p order by p.ville ASC, p.secteur ASC, p.licence ASC",$con);
    $pdvs = array();
    while($row=mysql_fetch_array($r))
    {
        // $response['libelle']=$row['libelle'];
        $pdvs[] = array('id'=>$row['id'],'nom'=>$row['nom'],'licence'=> $row['licence'],'ville'=> $row['ville'],'secteur'=> $row['secteur']);
    }
    // ****************************************************************************************************
    //                                      GET CADEAUX
    // ****************************************************************************************************

    $r  = mysql_query("select * from cadeau where enabled = 1",$con);
    $cadeaux = array();
    while($row=mysql_fetch_array($r))
    {
        // $response['libelle']=$row['libelle'];
        $cadeaux[] = array('id'=>$row['id'],'libelle'=> utf8_encode($row['libelle']));
    }

    // ****************************************************************************************************
    //                                      GET CADEAUX
    // ****************************************************************************************************

    $r  = mysql_query("select * from superviseur",$con);

    $superviseurs = array();
    while($row=mysql_fetch_array($r))
    {
        $superviseurs[] = array('id'=>$row['id'], 'nom'=> utf8_encode($row['nom']),'prenom'=> utf8_encode($row['prenom']));
    }

    // ****************************************************************************************************
    //                                      SYNCRONIZATION
    // ****************************************************************************************************

    $r  = mysql_query("select * from parameters",$con);
    $parameters = array();
    while($row=mysql_fetch_array($r))
    {
        $parameters[] = array('key'=>$row['key'],'value'=>$row['value']);
    }

    // ****************************************************************************************************
    //                                      CREATING THE RESPONSE
    // ****************************************************************************************************

    $response = array('marques' => $marques, 'raisonsAchat' => $raisonsAchat , 'raisonsRefus' => $raisonsRefus, 'tranchesAge'=> $tranchesAge,
                      'pdvs' => $pdvs, 'cadeaux' => $cadeaux, 'superviseurs' => $superviseurs, 'parameters' => $parameters);

    print(json_encode($response));
    mysql_close($con);
?>
