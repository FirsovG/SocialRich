<?php
    // Inizialisierung von Verbindungsvariable 
    /*
        localhost      = Adresse von den MySql-Server
        root           = Benutzer von den MySql-Server
        ''             = Passwort den MySql-Server
        db_social_rich = Datebankname
        3306           = Port von den MySql-Server
    */
    $connection = mysqli_connect('localhost', 'root', '', 'db_social_rich', '3306');
    // Wenn es keine Verbindung gibt wird ein Fehler angezeigt, der für den ganzen MySql bereich gleich ist
    // Weil es den eingachen Benutzer nicht interessieren soll
    if (!$connection)
    {
        // Schickt auf eine /social_rich/index.php mit $_GET Parameter error=coulnt_connect der für MySql Fehler steht
        header("Location: /social_rich/index.php?error=coulnt_connect");
        // Beendet den Script
        exit();
    }

?>