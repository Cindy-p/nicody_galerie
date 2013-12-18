<?php
    header('Content-Type: text/html; charset=utf-8');	// Encodage UTF-8 PHP
    try
{
    $pdo = new PDO('mysql:host=localhost;dbname=nicody_galerie', 'root', '');
    //$pdo = new PDO('mysql:host=localhost;dbname=nicody_galerie', 'root', 'root');
	$pdo->query("SET NAMES UTF8");
}
catch(Exception $e)
{
    echo 'Echec de la connexion à la base de données';
     echo 'Erreur : '.$e->getMessage().'<br />';
    exit();
}

?>