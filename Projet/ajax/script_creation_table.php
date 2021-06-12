<?php

$serveur = "localhost";
$base = "mediaphoto";
$user = "root";
$pass = "";
$params = array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);

$dsn = "mysql:host=$serveur;dbname=$base";

try {
  $db = new \PDO($dsn, $user, $pass, $params);
} catch(\PDOException $e) {
  echo "Connection error: $dsn" . $e->getMessage();
  exit;
}

$qualite = false;
for ($i=5; $i < 40; $i++) {
  $stmt = $db->prepare("INSERT INTO photo (id, titre, chemin, id_utilisateur, id_galerie, qualite, type, taille) VALUES (:id, :titre, :chemin, :id_utilisateur, :id_galerie, :qualite, :type, :taille)");
  $stmt->bindParam(':id', $i);
  $stmt->bindParam(':titre', $titreReq);
  $stmt->bindParam(':chemin', $cheminReq);
  $stmt->bindParam(':id_utilisateur', $id_userReq);
  $stmt->bindParam(':id_galerie', $id_galerieReq);
  $stmt->bindParam(':qualite', $qualiteReq);
  $stmt->bindParam(':type', $typeReq);
  $stmt->bindParam(':taille', $tailleReq);

  if($qualite) $qualite = false;
  else $qualite = true;

  $titreReq = 'Voici ma '.$i.'ème photo !';
  $cheminReq = "/html/images/".$i.".jpg";
  $id_userReq = 11;
  $id_galerieReq = 54;
  $qualiteReq = ($qualite) ? 'FULL HD' : 'HD';
  $typeReq = 'jpg';
  $tailleReq = $i * 7.3;

  $stmt->execute();
}

echo "ok";
