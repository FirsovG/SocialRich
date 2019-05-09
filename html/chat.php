<?php 

// Startet die Session auf der Seite
session_start();
// Speichert den Dateinamen
$filename = basename(__FILE__, '.php');

// Wenn der Benutzer nicht angemeldet wird man mit eine Fehlermeldung auf die Hauptseite transportiert
if (!isset($_SESSION['user_id']))
{
    header("Location: /social_rich/index.php?error=first_log_in");
    exit();
}

// Wenn der id von Benutzer gleich ist wie bei den id von den Dialogpartner wird der mit eine Fehlermeldung auf die Hauptseite transportiert
if ($_SESSION['user_id'] == $_GET['id'])
{
    header("Location: /social_rich/index.php?error=cant_chat_with_yourself");
    exit();
}

// Lädt das Info über den Dialog
include "../script_includes/get_chat_info.inc.php";
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
    <link rel="stylesheet" href="/social_rich/css/chat.css">
    <!-- Icon für deises Projekt -->
    <link rel="shortcut icon" href="/social_rich/content/logo.ico">

    <!-- Der name von den Dialogpartner wird angezeigt -->
    <title><?php echo $chat['friend_name']?> </title>
</head>
<body>
    <!-- Addet Navbar -->
    <?php include dirname(__FILE__) . "/../grafik_includes/navbar.php"; ?>

    <main>
        <!-- Ein Container der die Breite verkürtzt -->
        <div class="container">
            <div class="row get_message">
                <!-- Hierhin werden die Nachrichten mit hilfe von jQuery reingeladen -->
                <div class="message_wrapper"></div>
            </div>
            <!-- Eine Form die Nachrichten abschikt -->
            <form action="/social_rich/script_includes/send_message.inc.php?chat_id=<?php echo $chat['chat_id'] ; ?>&friend_id=<?php echo $chat['friend_id'] ; ?>" method="post">
                <div class="row send_message">
                    <!-- Text von der Nachricht -->
                    <textarea name="send_messge_text" id="send_messge_text" placeholder="enter your message here"></textarea>
                    <!-- Absendtaste -->
                    <button name="message_submit" type="submit" id="send_message_btn"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </main>
    <!-- Lädt jQuery rein -->
    <script src="/social_rich/js/templates/jquery.js"></script>
    <script>  
        // Die get_new_messages.inc.php wird am Anfang direkt geladen
        $('.message_wrapper').load('../script_includes/get_new_messages.inc.php?chat_id=<?php echo $chat['chat_id'] ; ?>&your_id=<?php echo $chat['your_id'] ; ?>&your_image=<?php echo $chat['your_image'] ; ?>&friend_id=<?php echo $chat['friend_id'] ; ?>&friend_name=<?php echo $chat['friend_name'] ; ?>&friend_image=<?php echo $chat['friend_image'] ; ?>');
        // Die Funtion lädt jede 5 Sekunden den get_new_messages.inc.php
        setInterval(function() {
            $('.message_wrapper').load('../script_includes/get_new_messages.inc.php?chat_id=<?php echo $chat['chat_id'] ; ?>&your_id=<?php echo $chat['your_id'] ; ?>&your_image=<?php echo $chat['your_image'] ; ?>&friend_id=<?php echo $chat['friend_id'] ; ?>&friend_name=<?php echo $chat['friend_name'] ; ?>&friend_image=<?php echo $chat['friend_image'] ; ?>');
        }, 5000);
    </script>
</body>
</html>