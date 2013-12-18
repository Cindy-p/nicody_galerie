<?php
	if(session_id() == "") session_start(); // Vérification de l'existance de session
	header('Content-Type: text/html; charset=utf-8');	// Encodage UTF-8 PHP
	include("include/connexion.php");
	
	// Récupération des variables
	$login = htmlentities($_POST["login"]);
    $password = htmlentities($_POST["password"]);

    // Vérification du contenu
    $pattern_login = '/^([0-9a-zA-Z])+$/';
    $pattern_password = '/^([0-9a-zA-Z])+$/';
    
    if( preg_match($pattern_login,$login,$matches,PREG_OFFSET_CAPTURE) ){
       if( preg_match($pattern_password,$password,$matches,PREG_OFFSET_CAPTURE) ){
            
            // Vérification de la correspondance avec la base
            $sql = 'SELECT COUNT(*),idutilisateur FROM utilisateur WHERE login = :login AND password = MD5(:password)';
            $stm = $pdo->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $stm->execute(array(':login' => $login, ':password' => $password));
            $res = $stm->fetch(PDO::FETCH_ASSOC);
            if ($res["COUNT(*)"] > 0 ){
                $_SESSION["utilisateur"] = $login;
                $_SESSION["idutilisateur"] = $res["idutilisateur"];
                $msg = "ok";
            } else {
                $msg = "Il y a une erreur pour le couple login/password !";
            }
            
        } else {
            $msg = "Le password n'a pas un format correcte !";
        }
    } else {
        $msg = "Le login n'a pas un format correcte !";
    }
    
    include("include/deconnexion.php");
    
    echo json_encode(array('msg' => $msg));
?>