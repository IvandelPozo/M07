<?php
  //connexió dins block try-catch:
  //  prova d'executar el contingut del try
  //  si falla executa el catch
  try {
    $hostname = "localhost";
    $dbname = "acces_dades";
    $username = "u_acces_dades";
    $pw = "i";
    $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
  } catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    exit;
  }
  
  //preparem i executem la consulta
  $query = $pdo->prepare("select i, a FROM prova");
  $query->execute();
  
  foreach ($query as $row) {
	  echo $row['i']." - " . $row['a']. "<br/>";
  } 
  
  //eliminem els objectes per alliberar memòria 
  unset($pdo); 
  unset($query)
?>