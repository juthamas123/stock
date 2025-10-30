<?php
session_start();
session_destroy();
header("Location: chackout.php");
exit();
?>

