<?php

    // Startet die Session
    session_start();
    // Speichert den Dateinamen
    $filename = basename(__FILE__, '.php');

    // Wenn der Benutzer schon angemeldet ist wird der mit eine Fehlermeldung auf die Hauptseite transportiert
    if (isset($_SESSION['user_id']))
    {
        header("Location: /social_rich/index.php?error=always_logged_in");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Grid von Bootstrap -->
    <link rel="stylesheet" href="/social_rich/css/template/b_grid.css">
    <!-- Iconspack von Fontawsome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <!-- Schrift von google -->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:400,700" rel="stylesheet">  
    <!-- Standart .css File um Navbar und etc. Sachen die überall gleich sind zu gestallten -->
    <link rel="stylesheet" href="/social_rich/css/style.css">
    <!-- Spezialle .css für diese Seite -->
    <link rel="stylesheet" href="/social_rich/css/signup.css">
    <!-- Icon für deises Projekt -->
    <link rel="shortcut icon" href="/social_rich/content/logo.ico">

    <title>Signup</title>
</head>
<body>
    <!-- Addet den Navbar -->
    <?php 

    include dirname(__FILE__) . "/../grafik_includes/navbar.php";

    // Inizialisiert die temporären Variablen 
    // die dazu da sind um wenn es in den Get Array was dazu eingetragen ist 
    // die Werte in den Formular einfügt
    $username = "";
    $email = "";
    $firstname = "";
    $surname = "";

    if (isset($_GET['username']))
    {
        $username = $_GET['username'];
    }
    if (isset($_GET['email']))
    {
        $email = $_GET['email'];
    }
    if (isset($_GET['firstname']))
    {
        $firstname = $_GET['firstname'];
    }
    if (isset($_GET['surname']))
    {
        $surname = $_GET['surname'];
    }

    // Fehlerhandler
    if (isset($_GET['error']))
    {
        $error_message = "";
        // Leere Felder/LeeresFeld
        if ($_GET['error'] == "empty_field")
        {
            $error_message = "Empty field";
        }
        // In den namen wurde nicht nur der Alpfabet gefunden
        else if ($_GET['error'] == "name_number")
        {
            $error_message = "Write your real name";
        }
        // In den Benutzernamen wurde nicht nut die Alpfabet und Zahlen gefunden
        else if ($_GET['error'] == "invalid_username")
        {
            $error_message = "Alphanumeric characters only";
        }
        // Eingetragene Passwörter stimmen nicht überein
        else if ($_GET['error'] == "password_do_not_match")
        {
            $error_message = "Password do not match";
        }
        // Der Name ist schon besetzt
        else if ($_GET['error'] == "username_taken")
        {
            $error_message = "Username always taken";
        }
        // Die Email ist schon besetzt
        else if ($_GET['error'] == "email_taken")
        {
            $error_message = "Email always taken";
        }
        // Man hat probiert auf den Regestrierskript direkt draufzugreifen
        else if ($_GET['error'] == "first_fill_the_form")
        {
            $error_message = "First fill the form";
        }
        ?>
            <!-- Script von Sweetallert (Fügt pop-up nachrichten) -->
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script>
                // Auf der Index Seite ist es ausführlich beschrieben
                swal({
                    button: "Ok",
                    text: "<?php echo $error_message ; ?>",
                    icon: "error",
                    timer: 4000,
                    });
            </script>
        <?php
    }
    ?>
    <!-- Eine Regestrier Form -->
    <form action="/social_rich/script_includes/signup.inc.php" method="post">
        <h1 class="slogan">SIGNUP</h1>
        <!-- Vorname -->
        <input id="first_input" type="text"  value="<?php echo $firstname?>" autofocus name="firstname" placeholder="Firstname">
        <!-- Nachname -->
        <input type="text"  value="<?php echo $surname?>" name="surname" placeholder="Surname">
        <!-- Benutzername -->
        <input type="text"  value="<?php echo $username?>" name="username" placeholder="Username">
        <!-- Email -->
        <input type="email" value="<?php echo $email?>" name="email" placeholder="Email">
        <!-- Passwort -->
        <input type="password" name="password" placeholder="Password">
        <!-- Wiederholung von den Passwort -->
        <input type="password" name="re_password" placeholder="Repeat">
        <!-- Submit button -->
        <button type="submit" name="signup_submit">SIGNUP</button>
        <!-- Wenn man schon ein Account hat kann man draufclicken dann wird man auf den Anmeld Formular transportiert -->
        <a class="login_link" href="/social_rich/html/login.php">Already have an account</a>
    </form>
</body>
</html>