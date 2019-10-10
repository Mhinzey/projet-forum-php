<?php
include 'connect.php';
include 'header.php';
session_destroy();
echo 'You have been logged out. <a href="../index.php">Go back</a>';
include'footer.php';
?>