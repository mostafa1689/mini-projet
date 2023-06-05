<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = 'root';
$pass = "root";
$host = 'localhost';
$dbname = "nitrocol";
try {
    $dbh = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $user, $pass);

    // Permet de gérer le retour des données en UTF8
    $dbh->exec("SET NAMES 'utf8'");

    // Gestion des erreurs
    // Par defaut, PDO est configure en mode silencieux; il ne rapportera pas les erreurs. Il existe trois modes d'erreurs :
    // PDO::ERRMODE_SILENT - ne rapporte pas d'erreur (mais assignera les codes d'erreurs) ;
    // PDO::ERRMODE_WARNING - emet un warning ;
    // PDO::ERRMODE_EXCEPTION - lance une exception.
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
    die($error);
}
