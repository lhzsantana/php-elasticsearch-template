<?php

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

$phones = $_POST["phones"];

$output = fopen("php://output", "w");
fwrite($output, $phones);
fclose($output);

?>