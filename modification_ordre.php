<?php
	include("include/connexion.php");
	
	$listImage = $_GET["listImage"];
	
	try
	{
		// Début de la transaction
		$pdo->beginTransaction();
		for($i= 0; $i < count($listImage); ++$i ){
			
			$sql = "UPDATE image SET ordre = :ordre  WHERE idimage = :idimage ORDER BY ordre ASC";
			$stmImage = $pdo->prepare($sql);
			$stmImage->execute(array(":idimage" => $listImage[$i], ":ordre" => intval($i)+1));
			
		}
		// Validation de la dernière transaction
		$pdo->commit();
	
		$msg = "ok";
		
	}
	catch(Exception $e) //en cas d'erreur
	{
		// Annulation de la transaction
		$pdo->rollback();
	
		echo 'Erreur : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
		$msg =" Il y a eu un problème lors de l'insertion en base !";
	}
	
	echo json_encode(array('msg' => $msg));
?>