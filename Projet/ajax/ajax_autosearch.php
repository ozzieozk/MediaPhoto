<?php

session_start();

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

if(isset($_GET['chaine']) && $_GET['chaine'] != '')
{
  $chaine = $_GET['chaine'];
  $req = $db->prepare("
      SELECT nom
      FROM tag
      WHERE nom LIKE '".$chaine."%'
      ;
    ");
  $req->execute();
  if($req->rowCount())
  {
    $rep = $req->fetchAll();
    $list;
    for ($i=0; $i < count($rep); $i++)
    {
      $list[$i] = $rep[$i][0];
    }
    echo json_encode($list);
  }
}
else
{
  echo 'erreur';
}
