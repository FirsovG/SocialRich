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
    <link rel="stylesheet" href="/social_rich/css/login.css">
    <!-- Icon für deises Projekt -->
    <link rel="shortcut icon" href="/social_rich/content/logo.ico">

    <title>Login</title>
</head>
<body>
    <?php 
    // Addet Navbar
    include dirname(__FILE__) . "/../grafik_includes/navbar.php";

    // Inizialisiert die temporäre Variable
    // die dazu da ist um wenn es in den Get Array was dazu eingetragen ist 
    // die Werte in den Formular einfügt
    $mail_username = "";

    if (isset($_GET['mail_username']))
    {
        $mail_username = $_GET['mail_username'];
    }


    // Fehlerhandler
    if (isset($_GET['error']))
    {
        $error_message = "";
        // Leeres Feld
        if ($_GET['error'] == "empty_field")
        {
            $error_message = "Empty field";
        }
        // Falsches Password
        else if ($_GET['error'] == "wrong_password")
        {
            $error_message = "Wrong password";
        }
        // Kein Benutzer mit disen Email oder Benutzernamen
        else if ($_GET['error'] == "no_user")
        {
            $error_message = "No user";
        }
        // Man hat probiert auf den Script direkt draufzugreifen
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
    <!-- Eine Login Form -->
    <form action="/social_rich/script_includes/login.inc.php" method="post">
        <i class="fas fa-user-tie"></i>
        <h1 class="slogan">LOGIN</h1>
        <!-- Email/Benutzername -->
        <input id="first_input" type="text" name="mail_username" value="<?php echo $mail_username?>" placeholder="EMAIL/USERNAME">
        <!-- Passwort -->
        <input type="password" name="password" placeholder="PASSWORD">
        <!-- Submit button -->
        <button type="submit" name="login_submit">LOGIN</button>
        <!-- Ein Link wenn man kein Account hat -->
        <a class="signup_link" href="/social_rich/html/signup.php">New here?</a>
    </form>
</body>
</html>