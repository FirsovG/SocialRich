
<?php 

// Startet die Session
session_start();
// Dateiname wird eingespeichert
$filename = basename(__FILE__, '.php');

// Wenn der Benutzer nicht angemeldet wird man mit eine Fehlermeldung auf die Hauptseite transportiert
if (!isset($_SESSION['user_id']))
{
    header("Location: /social_rich/index.php?error=first_log_in");
    exit();
}

// Es werden alle Profile auser eigenen reingeladen
require "../script_includes/get_profiles.inc.php";

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
    <link rel="stylesheet" href="/social_rich/css/find_friends.css">
    <!-- Icon für deises Projekt -->
    <link rel="shortcut icon" href="/social_rich/content/logo.ico">

    <title>Friends</title>
</head>
<body>

    <?php include dirname(__FILE__) . "/../grafik_includes/navbar.php"; ?>

    <div class="container">
        <div class="users">
            <div class="user_wrapper">
                <div class="row">
                    <?php
                    // Es gehören 2 einträge in eine Zeile
                    $rowCount = 0;
                    // Alle Profile gehen nacheinander durch
                    foreach ($profiles as $profile)
                    {
                    ?>  
                        <div class="col-12 col-md-6">
                            <!-- Wenn man draufclickt kommt man auf den Profile von den ausgewählten Benutzer -->
                            <a class="profile_link" href="/social_rich/html/profile.php?id=<?php echo $profile['user_id'] ; ?>">
                                <div class="row user">
                                    <div class="col-0 col-md-4">
                                        <div class="image_wrapper">
                                            <img src="/social_rich/content/img/<?php echo $profile['user_image'] ; ?>" alt="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <h2><?php echo $profile['user_name']?></h2>
                                        <p>Employement: <?php echo $profile['user_employement']?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                        $rowCount++;
                        // Auf jeden zweiten eintrag wird eine neue Zeile erstellt
                        if($rowCount % 2 == 0)
                        {
                            echo '</div><div class="row">';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>