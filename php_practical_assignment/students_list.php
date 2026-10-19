<?php require 'config.php'; $rows=$pdo->query("SELECT * FROM students ORDER BY id")->fetchAll(PDO::FETCH_ASSOC); ?>
<!doctype html><html><head><title>Students</title><link rel="stylesheet" href="style.css"></head><body>
<h1>Students table</h1><a href="import_xml.php">Import XML</a> | <a href="export_xml.php">Export XML</a> | <a href="index.php">Home</a>
<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Age</th></tr>
<?php foreach($rows as $r): ?><tr><td><?=$r['id']?></td><td><?=htmlspecialchars($r['name'])?></td><td><?=htmlspecialchars($r['email'])?></td><td><?=$r['age']?></td></tr><?php endforeach; ?></table></body></html>