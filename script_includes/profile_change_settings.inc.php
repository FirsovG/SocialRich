<?php

// Startet die Session
session_start();

// Wenn es kein Benutzer indezifiziert ist wird man mit eine Fehlermeldung auf die Hauptseite transportiert
if (!isset($_SESSION['user_id']))
{
    header("Location: /social_rich/index.php?error=first_log_in");
    exit();
}
    // Wenn der man auf den Script direkt draufzugreift wird man mit eine Fehlermeldung auf die Profilechangeseite transportiert
    if (isset($_POST['settings_submit']))
    {
        // Fügt Datenbank connection ein
        require dirname(__FILE__) . "/db_conn.php";

        // Ein temporäres assoziatives Array welcher das Info von den Benutzer aus den Post Array rauszieht
        $new_user_data = array(
            "firstname" => $_POST['firstname'],
            "surname" => $_POST['surname'],
            "birthday" => $_POST['birthday'],
            "status" => $_POST['status'],
            "employement" => $_POST['employement']
        );
        // SQL Befehl der einträge neu in die Datenbank speichert
        // Ich konnte es einzeln abfragen welchen genauen Feld der Benutzer verändert hat
        // Aber bei der menge für es den Script viel langsemer machen
        // Weil ich alles nochmal abfragen soll und alles Vergleichen
        $sql = "UPDATE tbl_users SET user_firstname=?, user_surname=?, 
                user_birthday=?, user_status=?, 
                user_employement_id=? Where user_id=?";
        // In den get_profile_data.inc.php wird es alles ausführlich beschrieben
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("Location: /social_rich/index.php?error=coulnt_connect");
            exit();
        }
        else
        {
            // Füht alle Felder ein und die id von den Benutzer
            mysqli_stmt_bind_param($stmt, "sssssi", $new_user_data['firstname'], $new_user_data['surname'], 
                                                    $new_user_data['birthday'], $new_user_data['status'], 
                                                    $new_user_data['employement'], $_SESSION['user_id']);
            mysqli_stmt_execute($stmt);
            header("Location: /social_rich/html/profile.php?successful=changes_saved&id=" . $_SESSION['user_id']);
            exit();
        }
    }
    else
    {
        header("Location: /social_rich/html/profile_change_settings.php?error=fill_the_form&id=$session_id");
        exit();
    }
?>