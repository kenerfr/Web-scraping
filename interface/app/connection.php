
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO("mysql:host=rooftopmariadb.c0mrcyyivthm.us-east-1.rds.amazonaws.com;dbname=imoveis_de_leilao", "admin", "K4t1Oilm",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
 } catch (PDOException $erro) {
     echo "Erro na conexão:" . $erro->getMessage();
 }

 if (!$pdo) {
  echo "Error: Unable to connect to MySQL." . PHP_EOL;
  echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
  echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
  exit;
}else{
//echo 'conecatado';
}
