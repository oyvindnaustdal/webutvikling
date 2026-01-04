<?php
session_start();
session_destroy(); // Slett alle data om at brukaren er innlogga
header("Location: index.php"); // Send tilbake til framsida
exit();
?>