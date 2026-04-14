<?php

$DB_NAME ='AM_SPORT';
$DB_USER = 'root';
$DB_PASS = '';

try
{
    $PDO =new PDO('','','');
    echo "connexion réussie";

}
catch(PDOException $pe)
{
    echo'ERREUR : '.$pe->getMessage() ;
}

?>