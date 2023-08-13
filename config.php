<?php
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "primary_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>