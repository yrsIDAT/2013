<?php
$db = NULL;

try {
	$username = 'root';
	$password = 'root';
    $db = new PDO('mysql:host=localhost;dbname=iambored', $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

?>