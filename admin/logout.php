<?php
require $_SERVER['DOCUMENT_ROOT'].'/project/core/init.php';
unset($_SESSION['HBUser']);
header('Location: login.php');
?>
