<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ruet_medical";
//define("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", "your_sendgrid_api_key");

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
