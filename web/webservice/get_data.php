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
    //                                      GET CATEGORIES ( catégories des produits )
    // ****************************************************************************************************

    $r  = mysql_query("select id, parent_id, context, name from classification__category WHERE enabled = 1",$con);

    $categories = array();
    while($row=mysql_fetch_array($r))
    {
        $categories[] = array('id'=>$row['id'], 'parent_id'=>$row['parent_id'], 'context'=> utf8_encode($row['context']), 'nom'=> utf8_encode($row['name']));
    }

    // ****************************************************************************************************
    //                                      GET PRODUITS
    // ****************************************************************************************************

    $r  = mysql_query("select id, sku, libelle, categorie_id from produit WHERE enabled = 1",$con);

    $produits = array();
    while($row=mysql_fetch_array($r))
    {
        $produits[] = array('id'=>$row['id'], 'sku'=> utf8_encode($row['sku']), 'libelle'=> utf8_encode($row['libelle']), 'categorie_id'=> $row['categorie_id']);
    }

    // ****************************************************************************************************
    //                                      GET Marques_Categories
    // ****************************************************************************************************

    $r  = mysql_query("select marque_id, category_id from marque_category",$con);

    $marquesCategories = array();
    while($row=mysql_fetch_array($r))
    {
        $marquesCategories[] = array('marque_id'=>$row['marque_id'], 'categorie_id'=> $row['category_id']);
    }

    // ****************************************************************************************************
    //                                      GET POIs
    // ****************************************************************************************************
    $r  = mysql_query("select distinct p.* from poi p order by p.libelle ASC",$con);
    $pois = array();
    while($row=mysql_fetch_array($r))
    {
        // $response['libelle']=$row['libelle'];
        $pois[] = array('id'=>$row['id'],'libelle'=> $row['libelle']);
    }

    // ****************************************************************************************************
    //                                      GET Pdvs_Pois
    // ****************************************************************************************************

    $r  = mysql_query("select pdv_id, poi_id from pdv_poi",$con);

    $pdvsPois = array();
    while($row=mysql_fetch_array($r))
    {
        $pdvsPois[] = array('pdv_id'=>$row['pdv_id'], 'poi_id'=> $row['poi_id']);
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

    $response = array('marques' => $marques, 'raisonsAchat' => $raisonsAchat , 'raisonsRefus' => $raisonsRefus,
        'tranchesAge'=> $tranchesAge, 'pdvs' => $pdvs, 'cadeaux' => $cadeaux, 'superviseurs' => $superviseurs,
        'parameters' => $parameters, 'categories' => $categories, 'produits' => $produits,'marques_categories' =>$marquesCategories, 'pois' => $pois, 'pdvs_pois' =>$pdvsPois);

    print(json_encode($response));
    mysql_close($con);
?>
