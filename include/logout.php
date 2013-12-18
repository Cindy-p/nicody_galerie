<?php
    header('Content-Type: text/html; charset=utf-8');	// Encodage UTF-8 PHP
    if(session_id() == "") session_start(); // Vérification de l'existance de session
 
     unset($_SESSION["utilisateur"]);
     unset($_SESSION["idutilisateur"]);
     session_destroy();
     
     header('Location: ../index.php');  
 ?>