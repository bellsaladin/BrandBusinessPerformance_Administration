<?php
    include('_params.php');
    
    $username        = $_REQUEST['username'];    
    $password        = $_REQUEST['password'];

    $con = mysql_connect($host,$uname,$pwd) or die("connection failed");
    mysql_select_db($db,$con) or die("db selection failed");
         
     
    $r  = mysql_query("SELECT id FROM sfo WHERE nom_utilisateur='$username' AND mot_de_passe = '$password' LIMIT 1",$con);

    if($row=mysql_fetch_array($r)){
        echo $row['id'];
    }else{
        echo 0;
    }    

    //print(json_encode($response));
    mysql_close($con);
?>