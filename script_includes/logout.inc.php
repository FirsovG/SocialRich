<?php

// Startet die Session
session_start();
// Setzt alle eingetragene Variblen die sich in $_SESSION befindet
session_unset();
// Die Session wird zerstört
session_destroy();
// Man wird mit eine Erfolgmeldung auf die Hauptseite transportiert
header("Location: /social_rich/index.php?successful=logout");

?>