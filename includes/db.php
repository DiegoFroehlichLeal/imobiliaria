<?php
$host = "srv1660.hstgr.io"; // Ou o nome do servidor MySQL, se diferente
$user = "u773098555_user_imob";
$pass = "@Vigia77895677";
$db = "u773098555_imobiliaria_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
