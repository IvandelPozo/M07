<?php
  //connexió dins block try-catch:
  //  prova d'executar el contingut del try
  //  si falla executa el catch
  try {
    $hostname = "localhost";
    $dbname = "gringottsdb";
    $username = "u_gringottsdb";
    $pw = "1234";
    $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
  } catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    exit;
  }
  
  //preparem i executem la consulta
  $query = $pdo->prepare("select * FROM goblins");
  $query->execute();
  
  foreach ($query as $row) {
	  echo $row['goblin_name']. " | " .$row['password']. " | " .$row['last_login']."<br/>";
  } 
  
  //eliminem els objectes per alliberar memòria 
  unset($pdo); 
  unset($query)
?>