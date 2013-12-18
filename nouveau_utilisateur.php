<?php
	if(session_id() == "") session_start(); // Vérification de l'existance de session
	header('Content-Type: text/html; charset=utf-8');	// Encodage UTF-8 PHP
	include("include/connexion.php");
	
	// Récupération des variables
	$login = htmlentities($_POST["login"]);
    //$email = htmlentities($_POST["email"]);
    $password = htmlentities($_POST["password"]);

    // Vérification du contenu
    $pattern_login = '/^([0-9a-zA-Z])+$/';
  
    $pattern_password = '/^([0-9a-zA-Z])+$/';
    
    if( preg_match($pattern_login,$login,$matches,PREG_OFFSET_CAPTURE) ){
       // if( preg_match($pattern_email,$email,$matches,PREG_OFFSET_CAPTURE) ){
        if( 1 == 1 ){
         
            if( preg_match($pattern_password,$password,$matches,PREG_OFFSET_CAPTURE) ){
            
                // Insertion dans la base
                $sql = "INSERT INTO utilisateur (login,password) VALUES (:login,MD5(:password))";
                try
                {
                    // Début de la transaction
                    $pdo->beginTransaction();
                    $stm = $pdo->prepare($sql);
                    $stm->execute(array(":login" => $login, ":password" => $password ));
                    
                    // Validation de la transaction
                    $pdo->commit();                    
                    
                    // Création de la session utilisateur
                    $_SESSION["utilisateur"] = $login;
                    $_SESSION["idutilisateur"] = $pdo->lastInsertId();
                    if ( !mkdir(dirname(__FILE__)."/utilisateurs/".$login,0700)){
                    	$msg = "Le dossier ne s'est pas créé !";
                    } else {
                    	$msg = "ok";
                    }
                }
                catch(Exception $e) //en cas d'erreur
                {
                    // Annulation de la transaction
                    $pdo->rollback();
                
                    echo 'Erreur : '.$e->getMessage().'<br />';
                    echo 'N° : '.$e->getCode();
                    $msg =" Il y a eu un problème lors de l'insertion en base !";
                }
           } else {
               $msg = "Le password n'a pas un format correct !";
           }
        } else {
            $msg = "L'email n'a pas un format correct !";
        }
    } else {
        $msg = "Le login n'a pas un format correct !";
    }
    
    include("include/deconnexion.php");
    
    echo json_encode(array('msg' => $msg));
?>