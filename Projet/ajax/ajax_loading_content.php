<?php

$nbElemToLoad = (int) $_POST['nbElemToLoad'];
$actualOffset = (int) $_POST['actualOffset'];
$id_galerie = $_POST['idGalerie'];

//RECUPERER LES VARIABLES SESSION QUI CONTIENNE LES AUTOISATION
//ET FAIRE UNE REQUETE POUR SAVOIR SI l'utilisateur a bien le droit
//au cas ou si un utilisateur change manuelement le contenu de la variabkle

/* Les informations de connexion */
$serveur = "localhost";
$base = "mediaphoto";
$user = "root";
$pass = "root";
$params = array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);

$dsn = "mysql:host=$serveur;dbname=$base";

try {
  $db = new \PDO($dsn, $user, $pass, $params);
} catch(\PDOException $e) {
  echo "Connection error: $dsn" . $e->getMessage();
  exit;
}

$html = '';

$requete = "SELECT id, titre, chemin FROM `photo` WHERE id_galerie = :galerieID ORDER BY id ASC LIMIT :elemToLoad OFFSET :actualOffset;";
$requete_prep = $db->prepare($requete);
$requete_prep->bindParam(':galerieID', $id_galerie);
$requete_prep->bindParam(':elemToLoad', $nbElemToLoad, PDO::PARAM_INT);
$requete_prep->bindParam(':actualOffset', $actualOffset, PDO::PARAM_INT);
$res = $requete_prep->execute();

if($res){

  while($ligne = $requete_prep->fetch(PDO::FETCH_OBJ)){
    $html .= <<< EOT
    <a href="../../index.php/photo/?id=$ligne->id">
      <img src="../..$ligne->chemin" alt="$ligne->titre"/>
      <div class="card-footer">
        <p>$ligne->titre</p>
      </div>
    </a>
EOT;
  }
  echo $html;
}
else{
  echo "Une erreur est survenue au niveau de la requête.";
}
