<?php
// Example: call the Express GET API running on port 3000.
$url='http://localhost:3000/api/products';
$ch=curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_TIMEOUT,5);
$response=curl_exec($ch);
if($response===false) { echo 'cURL error: '.htmlspecialchars(curl_error($ch)); }
else {
  $status=curl_getinfo($ch,CURLINFO_HTTP_CODE);
  echo '<h1>Response from Express API (HTTP '.$status.')</h1><pre>'.htmlspecialchars($response).'</pre>';
}
curl_close($ch);
?>