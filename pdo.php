<?php
$pdo = new PDO('mysql:host=0.tcp.ap.ngrok.io:17797;port=3306;dbname=cbse', 'root','');
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>