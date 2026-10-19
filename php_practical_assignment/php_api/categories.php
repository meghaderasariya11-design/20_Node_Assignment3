<?php
// Simple PHP REST-style API: returns categories as JSON.
require '../config.php';
header('Content-Type: application/json; charset=utf-8');
$rows=$pdo->query("SELECT id,name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
?>