<?php
require 'config.php';
$rows=$pdo->query("SELECT name,email,age FROM students ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
$xml=new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><students></students>');
foreach($rows as $r) {
  $s=$xml->addChild('student');
  $s->addChild('name',htmlspecialchars($r['name'],ENT_XML1,'UTF-8'));
  $s->addChild('email',htmlspecialchars($r['email'],ENT_XML1,'UTF-8'));
  $s->addChild('age',(string)$r['age']);
}
$file=__DIR__.'/xml_students/exported_students.xml';
$xml->asXML($file);
echo "Exported ".count($rows)." students to <a href='xml_students/exported_students.xml'>exported_students.xml</a>";
?>