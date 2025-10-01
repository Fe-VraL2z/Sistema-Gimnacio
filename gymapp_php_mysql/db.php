<?php
// db.php
$DB_HOST = 'localhost';
$DB_USER = 'root';    // ajusta si usas otro usuario
$DB_PASS = '';        // ajusta si tu MySQL tiene contraseña
$DB_NAME = 'gymdb_simple';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("Error conexión MySQL: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
?>