<?php
session_name("GoWilds_Session");
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>
