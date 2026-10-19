<?php
$folder=__DIR__.'/xml_students';
$files=scandir($folder);
?>
<!doctype html><html><head><title>List files</title><link rel="stylesheet" href="style.css"></head><body>
<h1>Files in xml_students directory</h1><ul>
<?php foreach($files as $file): if($file!=='.' && $file!=='..' && is_file($folder.'/'.$file)): ?>
<li><?=htmlspecialchars($file)?></li>
<?php endif; endforeach; ?></ul><a href="index.php">Home</a></body></html>