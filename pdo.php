<?php
$pdo = new PDO('mysql:host=0.tcp.ap.ngrok.io:16194;port=3307;dbname=cbse', 'root','');
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>