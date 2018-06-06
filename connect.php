<?php

if (! file_exists(__DIR__ . '/config.ini')) {
   echo 'You must copy config.ini.dist to config.ini and fill it with your information';
}

$ini = parse_ini_file('config.ini');
try {
    $bdd = new PDO('mysql:host=' . $ini['db_address'] . ';dbname=' . $ini['db_name'] . ';charset=utf8', $ini['db_user'], $ini['db_password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>