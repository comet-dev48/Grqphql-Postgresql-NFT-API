<?php
/*$host= 'localhost';
$db = 'local';
$user = 'postgres';
$password = 'Codicesegreto1'; // change to your password
//if(strpos($_SERVER['HTTP_HOST'], 'localhost') === false){
if($supabase){
$host= 'db.rupvyjmpxzoqjajuhhyy.supabase.co';
$db = 'postgres';
$user = 'postgres';
$password = 'Codicesegreto1'; 
}
try {
$dsn = "pgsql:host=$host;port=6543;dbname=$db;";

// make a database connection
$pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
die($e->getMessage());
}*/

$host = 'localhost';
// $db = 'rarespot_blockchain';
$db = 'postgres';
$user = 'postgres';
// $password = 'Ore7nhpXLulzplnLnS1T';
$password = 'president';

try {
    $dsn = "pgsql:host=$host;port=5432;dbname=$db;";
    // make a database connection
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    if ($pdo) {
        var_dump("Connected to the $db database successfully!");
    }
} catch (PDOException $e) {
    die($e->getMessage());
}