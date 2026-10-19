<?php
require 'config.php';
$file=__DIR__.'/xml_students/students.xml';
if(!file_exists($file)) die('students.xml not found.');
$xml=simplexml_load_file($file);
if($xml===false) die('Invalid XML file.');
$stmt=$pdo->prepare("INSERT INTO students(name,email,age) VALUES(?,?,?)");
$count=0;
foreach($xml->student as $s) {
  $stmt->execute([(string)$s->name,(string)$s->email,(int)$s->age]); $count++;
}
echo "Imported $count students from XML into MySQL.<br><a href='students_list.php'>View students</a>";
?>