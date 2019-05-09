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
// Wenn man probiert die einstellungen von ein anderen Benutzer zu ändern
if ($_SESSION['user_id'] != $_GET['id'])
{
    header("Location: /social_rich/html/profile.php?error=not_your_profile&id=$session_id");
    exit();
}
// Fügt die Datenbank connection ein
require_once dirname(__FILE__) . "/../script_includes/db_conn.php";
require "../script_includes/get_user_settings.inc.php";

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
    <link rel="stylesheet" href="/social_rich/css/profile_change_settings.css">
    <!-- Icon für deises Projekt -->
    <link rel="shortcut icon" href="/social_rich/content/logo.ico">

    <title>Settings</title>
</head>
<body>
    <!-- Addet Navbar -->
    <?php include dirname(__FILE__) . "/../grafik_includes/navbar.php" ; ?>
    <!-- Form um die Benutzer einstellungen zu speichern -->
    <form action="/social_rich/script_includes/profile_change_settings.inc.php" method="post">
        <h1>Change Settings</h1>
        <!-- Wenn es voreinstellungen gab werden die als values eingesetzt -->
        <input name="firstname" placeholder="Firstname" value="<?php echo $user_data['firstname'] ?>" type="text" id="firstname">
        <input name="surname" placeholder="Surname" value="<?php echo $user_data['surname'] ?>" type="text" id="surname">
        <label for="birthday">Birthday</label>
        <input name="birthday" type="date" value="<?php echo $user_data['birthday'] ?>" id="birthday">
        <label for="status">Status</label>
        <textarea name="status" id="status"><?php echo $user_data['status'] ?></textarea>
        <!-- Es werden alle Beschäftigungen aufgelisstet -->
        <select name="employement" id="employement">
            <?php 
                // Fragt alle Beschäftigungen inklusiv id ab
                $sql = "SELECT employement_id, employement_name From tbl_employements ORDER BY employement_id ASC";
                // In den get_profile_data.inc.php wird es alles ausführlich beschrieben
                $stmt = mysqli_stmt_init($connection);
                if (!mysqli_stmt_prepare($stmt, $sql))
                {
                    header("Location: /social_rich/index.php?error=coulnt_connect");
                    exit();
                }
                else
                {
                    mysqli_stmt_execute($stmt);
                    $employement_result = mysqli_stmt_get_result($stmt);
                    while ($employement_row_result = mysqli_fetch_assoc($employement_result))
                    {
                    ?>
                        <option <?php echo /* Wenn der Benutzer eine Beschäftigung hat wird es mit der Beschäftigung aus der Liste verglichen */
                                           (($employement_row_result['employement_id'] == $user_data['employement']) ? "selected" : "" )  ?> value="<?php echo $employement_row_result['employement_id'] ; ?>"><?php echo strtoupper($employement_row_result['employement_name']) ?></option>
                    <?php
                    }
                }
            ?>
        </select>
        <button name="settings_submit" type="submit">Save changes</button>
    </form>
    
    <!-- Script von Sweetallert (Fügt pop-up nachrichten) -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
        // Fehlerhandler
        if (isset($_GET['error']))
        {
            $message = "";
            // Wenn einer Probiert auf den Script der die Daten in die Datenbank speichert zuzugreifen
            if ($_GET['error'] ==  "fill_the_form")
            {
                $message = "Please fill the form";
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