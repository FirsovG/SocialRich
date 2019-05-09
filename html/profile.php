<?php

// Startet sie Session
session_start();
// Erstellt eine Variable mit der Dateiname
$filename = basename(__FILE__, '.php');

// Wenn der Benutzer nicht angemeldet wird man mit eine Fehlermeldung auf die Hauptseite transportiert
if (!isset($_SESSION['user_id']))
{
    header("Location: /social_rich/index.php?error=first_log_in");
    exit();
}

// Erstellt eine Varible die Sperichert ob das die Seite von den angemeldeten Benutzer ist 
$your_profile = false;

// Wenn die id's von den angemeldeten Benutzer und aus den get Array übereinstimmen
if ($_SESSION['user_id'] == $_GET['id'])
{
    // wird die Variable auf true gesetzt
    $your_profile = true;
}

// Führt ein Script aus der die Daten von den Benutzer rauszieht
require_once dirname(__FILE__) . "/../script_includes/get_profile_data.inc.php";
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
    <link rel="stylesheet" href="/social_rich/css/profile.css">
    <!-- Icon für deises Projekt -->
    <link rel="shortcut icon" href="/social_rich/content/logo.ico">

    <!-- Zeigt den Namen von den User im Title -->
    <title><?php echo $user_data['firstname'] . " " . $user_data['surname']; ?></title>
</head>
<body>
    <!-- Addet den Navbar -->
    <?php include dirname(__FILE__) . "/../grafik_includes/navbar.php"; ?>
    <!-- Ein Container der die Breite verkürtzt -->
    <div class="container">
        <!-- Eine Zeile -->
        <div class="row">
            <!-- Hauptinfo die die hälfte von den Container aufnimmt -->
            <div class="col-12 col-md-6 main_info">
                <img src="/social_rich/content/img/<?php echo $user_data['image']; ?>" alt="">
                <?php 
                // Wenn der Profile den Benutzer gehört kann der jenige sein Bild Tauschen
                if ($your_profile)
                {
                ?>
                    <div class="wrapper_form">
                        <form action="/social_rich/script_includes/upload_img.inc.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="image" accept=".png, .jpg, .jpeg"/>
                            <input type="submit" name="submit" value="UPLOAD"/>
                        </form>
                    </div>
                <?php 
                }
                ?>
                <h1 class="name"><?php echo $user_data['firstname'] . " " . $user_data['surname']; ?></h1>
            </div>
            <!-- Nebeninfo die die andere hälfte von den Container aufnimmt -->
            <div class="col-12 col-md-6 extended_info">
                <ul>
                    <li><h2>Birthday: <?php echo $user_data['birthday'] ; ?></h2></li>
                    <li><h2>Status: <?php echo $user_data['status'] ; ?></h2></li>
                    <li><h2>Employement: <?php echo $user_data['employement'] ; ?></h2></li>
                </ul>
                <?php 
                // Wenn der Profile den Benutzer gehört kann der jenige seine einstellungen ändern
                if ($your_profile)
                {
                ?>
                    <a href="/social_rich/html/profile_change_settings.php?id=<?php echo $_GET['id'] ; ?>">Change Settings</a>
                <?php 
                }
                // Sonst kann der angemeldeter Benutzer mit den jenigen chaten
                else
                {
                ?>
                    <a href="/social_rich/html/chat.php?id=<?php echo $_GET['id'] ; ?>">Send message</a>
                <?php 
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Script von Sweetallert (Fügt pop-up nachrichten) -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php 
        // Auf der Index Seite ist es ausführlich beschrieben
        if (isset($_GET['successful']))
        {
            $message = "";
            // Die änderungen die beim erweiterten Profileinstellungen wurden vorgenommen
            if($_GET['successful'] ==  "changes_saved")
            {
                $message = "Changes saved";
            }
            // Das neu Bild wurde hochgeladen und gespichert
            else if($_GET['successful'] ==  "upload_image")
            {
                $message = "Image uploaded";
            }
            ?>
                
                <script>
                    // Auf der Index Seite ist es ausführlich beschrieben
                    swal({
                        button: false,
                        text: "<?php echo $message ; ?>",
                        icon: "success",
                        timer: 1800,
                        });
                </script>

            <?php
        }

    ?>
    <?php
        // Fehlerhandler
        if (isset($_GET['error']))
        {
            $message = "";
            // Wenn einer probiert den Profil von den anderen zu verändern
            if ($_GET['error'] ==  "not_your_profile")
            {
                $message = "You can't change other profiles";
            }
            // Wenn es in den Input(Hochladen von den Bild) sich nicht um ein Bild handelt
            else if ($_GET['error'] == "bad_format")
            {
                $message = "Please upload an image(.jpg, .png, ...)";
            }
            // Die verhälnisse zwischen der Höhe und der Breite ungleich sind
            else if ($_GET['error'] == "width_doent_equal_height")
            {
                $message = "The height must equal the widht (200px-200px, 420px-420px, ...)";
            }
            // Wenn es in den $_File array ein fehler gibt
            else if ($_GET['error'] == "error_while_uploading")
            {
                $message = "Unknown error while uploading";
            }
            // Wenn die Datei größer als 10mb ist
            else if ($_GET['error'] == "to_large_file")
            {
                $message = "To large file(max 10mb)";
            }
            ?>

                <script>
                    // Auf der Index Seite ist es ausführlich beschrieben
                    swal({
                        button: "Ok",
                        text: "<?php echo $message ; ?>",
                        icon: "error",
                        });
                </script>

            <?php
        }
    ?>
</body>
</html>