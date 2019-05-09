<?php 
    //Startet die Session
    session_start(); 
    // Speichert den namen der Datei für die Nabvar
    $filename = basename(__FILE__, '.php'); 
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
    <link rel="stylesheet" href="/social_rich/css/index.css">
    <!-- Icon für deises Projekt -->
    <link rel="shortcut icon" href="/social_rich/content/logo.ico">

    <title>SoR</title>
</head>
<body>
    <!-- Addet den Navbar -->
    <?php include dirname(__FILE__) . "/grafik_includes/navbar.php" ?>
    <header>
        <!-- Fügt ein Video ein, startet automatisch, ist lautlos, spielt durchgehend  -->
        <video id="enter_video" autoplay muted loop src="/social_rich/content/video/people.mp4"></video>
        <!-- Eine Maske die haufen Kleine Punkte einfügt und eine graue Schicht dazufügt -->
        <div class="mask"></div>
        <!-- Text in der Mitte der Seite -->
        <div class="text_wrapper">
            <p class="text">Social Media for <span>You</span></p>
        </div>
        <?php
            // Entscheidet welcher Text und Link in den <a> Tag drin sein wird
            // Wenn der Benutzer angemeldet ist wird er seinem Chats geschickt
            // Wenn nicht wird er zum Registrierungsformular geschickt
            $do_link = "";
            $do_text = "";
            if (isset($_SESSION['user_id']))
            {
                $do_link = "chats.php";
                $do_text = "Chat";
            }
            else
            {
                $do_link = "signup.php";
                $do_text = "Signup/Login";
            }
        ?>
        <!-- Tag der über die Seite weiterführt (Logik in den php-Tag) -->
        <a class="btnDo" href="/social_rich/html/<?php echo $do_link ; ?>"><?php echo $do_text ; ?></a>
    </header>
    <!-- Fügt jQuery ein der eine JavaScript größere Funktionalität gibt -->
    <script src="/social_rich/js/templates/jquery.js"></script>
    <!-- Spezieller Script für diese Seite -->
    <script src="/social_rich/js/index.js"></script>
    <!-- Script von Sweetallert (Fügt pop-up nachrichten) -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <?php 
    // Erfolghandler
    if (isset($_GET['successful']))
    {
        $message = "successful";
        // Für einlogen
        if($_GET['successful'] ==  "login")
        {
            $message = "Login " . $message;
        }
        // Für auslogen
        else if($_GET['successful'] ==  "logout")
        {
            $message = "Logout " . $message;
        }
        // Für regestrierung
        else
        {
            $message = "Signup " . $message;
        }
        ?>

            <script>
                // Ein pop-up Nachricht mit der Meldung
                swal({
                    // Keine Taste
                    button: false,
                    // Text aus den php-Tag
                    text: "<?php echo $message ; ?>",
                    // Grünes Hacken 
                    icon: "success",
                    // Nach 1,8 Sekunden verschwindet die Nachricht
                    timer: 1800,
                    });
            </script>

        <?php
    }
    // Fehlerhandler
    if (isset($_GET['error']))
    {
        $message = "";
        // Wenn einer Probiert auf die Inhalte zuzugreifen ohne sich anzumelden
        if ($_GET['error'] ==  "first_log_in")
        {
            $message = "You need to be logged in";
        }
        // Wenn einer schon angemeldet ist, aber probiert auf den Loginformular zuzugreifen;
        else if($_GET['error'] ==  "always_logged_in")
        {
            $message = "You are always logged in";
        }
        // Wenn einer auf get_profile_data.inc.php direckt zugreifen möchte
        else if ($_GET['error'] == "please_open_profile")
        {
            $message = "Please open an profile";
        }
        // Wenn die Seite sich nicht mit MySql-Server verbinden kann
        // (Benutzer soll nicht wissen das die Skripte auf MySql draufgreifen aus sicherheitsgründen)
        else if ($_GET['error'] == "coulnt_connect")
        {
            $message = "Couln't connect";
        }
        // Wenn der Benutzer in chat.php als id seine eigene eingibt
        else if ($_GET['error'] == "cant_chat_with_yourself")
        {
            $message = "You can't chat with yourself";
        }
        // Kein Benutzer mit diese id (profile.php)
        else if ($_GET['error'] == "no_user")
        {
            $message = "No user with this id";
        }
        ?>

            <script>
                // Ein pop-up Nachricht mit der Meldung
                swal({
                    // Taste mit den Text "Ok"
                    button: "Ok",
                    // Text aus den php-Tag
                    text: "<?php echo $message ; ?>",
                    // Ein rotes Kreuz
                    icon: "error",
                    });
            </script>

        <?php
    }
    ?>
</body>
</html>