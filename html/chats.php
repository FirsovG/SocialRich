
<?php 
// Startet die Session
session_start();
// Macht eine Varible mit den Dateiname
$filename = basename(__FILE__, '.php');

// Wenn der Benutzer nicht angemeldet wird man mit eine Fehlermeldung auf die Hauptseite transportiert
if (!isset($_SESSION['user_id']))
{
    header("Location: /social_rich/index.php?error=first_log_in");
    exit();
}

// Ruft die Daten von allen Dialogen auf die der angemeldeter Benutzer hat
require "../script_includes/get_chats_info.inc.php";

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
    <link rel="stylesheet" href="/social_rich/css/chats.css">
    <!-- Icon für deises Projekt -->
    <link rel="shortcut icon" href="/social_rich/content/logo.ico">

    <title>Chats</title>
</head>
<body>
    <!-- Addet Navbar -->
    <?php include dirname(__FILE__) . "/../grafik_includes/navbar.php"; ?>

    <div class="container">
        <div class="chats">
            <div class="chat_wrapper">
                <?php
                    // Es werden alle Dialoge die der angemeldeter Benutzer hat nacheinander angezeigt
                    foreach ($chats as $chat)
                    {
                        ?>
                            <!-- Wenn man draufcklikt wird man auf den chat mit den jenigen weitertransportiert -->
                            <a class="chat_link" href="/social_rich/html/chat.php?id=<?php echo $chat['friend_id'] ; ?>">
                                <div class="row chat">
                                    <div class="col-0 col-md-2">
                                        <div class="image_wrapper">
                                            <img src="/social_rich/content/img/<?php echo $chat['friend_image'] ; ?>" alt="">
                                        </div>
                                    </div>
                                    <div class="col-0 col-md-10">
                                        <h2><?php echo $chat['friend_firstname'] . " " .$chat['friend_surname'] ; ?></h2>
                                        <?php
                                            if (isset($chat['last_message_sender_name']))
                                            {
                                            ?>
                                                <p><?php echo $chat['last_message_sender_name'] ; ?>: <?php echo $chat['last_message'] ; ?></p>
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                                <p>No messages</p>
                                            <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </a>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>