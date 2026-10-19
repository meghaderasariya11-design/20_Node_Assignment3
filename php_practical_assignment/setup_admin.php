<?php
require 'config.php';
$email='admin@example.com'; $password=password_hash('admin123',PASSWORD_DEFAULT);
$stmt=$pdo->prepare("INSERT INTO admins(email,password) VALUES(?,?) ON DUPLICATE KEY UPDATE password=VALUES(password)");
$stmt->execute([$email,$password]);
echo "Default admin created/updated.<br>Email: admin@example.com<br>Password: admin123<br><b>Delete setup_admin.php after running it.</b>";
?>