<?php

//Startet die Session
session_start();

// Wenn der Benutzer nicht angemeldet wird man mit eine Fehlermeldung auf die Hauptseite transportiert
if (!isset($_SESSION['user_id']))
{
    header("Location: /social_rich/index.php?error=first_log_in");
    exit();
}

// Temporäre Variable die das Id von den Benutzer speichert
$id = $_SESSION['user_id'];

// Wenn man probiert direckt auf diesen Script draufzugreifen wird man mit eine Fehlermeldung auf die Loginformularseite transportiert
if (isset($_POST['submit']))
{

    // Temporäre Variblen die das Info über die eingespeicherte Datei beinhalten
    // Datei name
    $fileName = $_FILES['image']['name'];
    // Dateipfad aus ihren PC
    $filePath = $_FILES['image']['tmp_name'];
    // Größe von den File in bytes
    $fileSize = $_FILES['image']['size'];
    // Deteityp
    $fileType = $_FILES['image']['type'];

    // Fehler die Beim reinladen aufgetreten sind
    $fileError = $_FILES['image']['error'];
    // Extesion von der Datei (.png, .jpg, ...)
    $fileExt = explode('.', $fileName);
    // Erste 5 Zeichen von den emaligen $fileType (beim Bildern wird die "Image" sein)
    $fileType = substr($fileType, 0, 5);

    // Eine Funtion die mehr Info über das Bild liefern wird
    list($width, $height, $type, $attr) = getimagesize($filePath);

    // Wenn es sich um ein Bild Handelt
    if ($fileType == "image")
    {
        // Wenn die Höhe äquivallent der Breite ist
        if ($width == $height)
        {
            // Wenn es keine Fehler beim reinladen gab
            if ($fileError === 0)
            {
                // Wenn die Datei kleiner als 10mb ist
                if ($fileSize < 1048576)
                {
                    // Erstellt ein id der random durch die Zeit ausgerechnet wird und addet die Extension
                    $unique_id_and_file_extension = uniqid("",false) . "." . end($fileExt);
                    // Fügt die Datenbank ein
                    require_once dirname(__FILE__) . "/db_conn.php";
                    // Dateipfad der neue Datei
                    $newFilePath = "../content/img/user" . $unique_id_and_file_extension;
                    // SQL Befehl der die id + Extension in die Datenbank reinschreibt
                    $sql = "UPDATE tbl_users SET user_image=? Where user_id=?";
                    $stmt = mysqli_stmt_init($connection);
                    if (!mysqli_stmt_prepare($stmt, $sql))
                    {
                        header("Location: /social_rich/index.php?error=coulnt_connect");
                        exit();
                    }
                    else
                    {
                        // Bindet id + Extension und fügt den Parameter mit den Benutzer-id
                        mysqli_stmt_bind_param($stmt, "si", $unique_id_and_file_extension, $id);
                        mysqli_stmt_execute($stmt);
                    }
                    // Die Datei wird aus ihrem Rechner in den neuen Dateipfadorner reinkopiert 
                    move_uploaded_file($filePath, $newFilePath);
                    header("Location: /social_rich/html/profile.php?successful=upload_image&id=$id");
                    exit();
                }
                // Fehler sind in profile.php beschrieben
                else
                {
                    header("Location: /social_rich/html/profile.php?error=to_large_file&id=$id");
                    exit();
                }
            }
            // Fehler sind in profile.php beschrieben
            else
            {
                header("Location: /social_rich/html/profile.php?error=error_while_uploading&id=$id");
                exit();
            }
        }
        // Fehler sind in profile.php beschrieben
        else
        {
            header("Location: /social_rich/html/profile.php?error=width_doent_equal_height&id=$id");
            exit();
        }
    }
    // Fehler sind in profile.php beschrieben
    else
    {
        header("Location: /social_rich/html/profile.php?error=bad_format&id=$id");
        exit();
    }
}

?>