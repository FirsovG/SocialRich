<?php
    // Database connection
    require_once dirname(__FILE__) . "/db_conn.php";

    // Dateiname-Variable der davor auf der Hauptseite inizialisiert wurde
    if ($filename == "profile")
    {
        // Daten für den Profile
        $user_data = array(
            // Profile id wird mit den Link mitgegeben
            "id" => $_GET['id'],
            "email" => "",
            "username" => "",
            "firstname" => "",
            "surname" => "",
            // Im Fall wenn der Benutzer kein Profilbild hat wird das standart Bild verwendet
            "image" => "std_user.png",
            // Wenn die Sachen nicht eingetragen sind, erscheint "Not entred" nicht angegeben
            "birthday" => "Not entred",
            "status" => "Not entred",
            "employement" => "Not entred"
        );
        // SQL Befehl der die Haupt daten rauszieht und Beschäftigungs_id für spätere benutzung 
        $sql = "SELECT tbl_users.user_email, tbl_users.user_username, tbl_users.user_firstname, tbl_users.user_surname, tbl_users.user_image, tbl_users.user_birthday, tbl_users.user_status, tbl_users.user_employement_id
                From tbl_users Where user_id=?";
        // Inizialisiert ein Statment der ein Objekt zurückgibt der nötig für den mysqli_stmt_prepare
        $stmt = mysqli_stmt_init($connection);
        // Wenn die vorbereitung ein False zurückgibt wird man auf index.php mit MySql Fehlermeludng geschikt
        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("Location: /social_rich/index.php?error=coulnt_connect");
            exit();
        }
        else
        {
            // Bindet ein Parameter der in SQL Behfehl mit ? gekenzeichnet wurde
            mysqli_stmt_bind_param($stmt, "i", $user_data['id']);
            // Führt den Befehl aus
            mysqli_stmt_execute($stmt);
            // Speichert die rückgabe in einen speziellen MySQL-Result Objekt
            $username_result = mysqli_stmt_get_result($stmt);
            /* 
                mysqli_fetch_assoc nimmt aus den MySQL-Result Objekt eine Zeile(Row) und gibt ein assoziatives Array zurück
                Da ich wuste das es nur eine Zeile (user_id ist einmailig) in den MySQL-Result Objekt gibt,
                habe ich eine if Abfrage genommen die nur eine Zeiele nimmt
            */
            if ($row_result = mysqli_fetch_assoc($username_result))
            {
                // Speichert die Sachen die sicher da sind 
                // (In der Datenbank sind sie mit NOT NULL eingetragen und bei Regestrierung pflichtfelder)
                $user_data['email'] = $row_result['user_email'];
                $user_data['username'] = $row_result['user_username'];
                $user_data['firstname'] = $row_result['user_firstname'];
                $user_data['surname'] = $row_result['user_surname'];
                // Fragt ab ob der Benutzer sein Geburtstag eingetragen hat
                if ($row_result['user_birthday'] != null)
                {
                    // Wenn ja speichert es in format Monat.Tag.Jahr z.B 05.25.2000
                    $user_data['birthday'] = date("m.d.Y",strtotime($row_result['user_birthday']));
                }
                // Fragt ab ob der Benutzer ein Status hat
                if ($row_result['user_status'] != null)
                {
                    // Wenn ja speichert es
                    $user_data['status'] = $row_result['user_status'];
                }
                // Fragt ab ob der Benutzer ein eigenes Profilbild hat
                if ($row_result['user_image'] != null)
                {
                    // Wenn ja dann speciert er den Namen der Datei
                    // Sonst ist in haupt Array standart Bild eingetragen
                    $user_data['image'] = "user" . $row_result['user_image'];
                }
                // Fragt ab ob der Benutzer sein Beschäftigung eingetragen hat
                if ($row_result['user_employement_id'] != null)
                {
                    // Wenn ja wird eine Abfrage erstellt 
                    // die den Namen von der Beschäftigung in den haupt Array specihert
                    $sql = "SELECT employement_name From tbl_employements Where employement_id=?";
                    $stmt = mysqli_stmt_init($connection);
                    if (!mysqli_stmt_prepare($stmt, $sql))
                    {
                        header("Location: /social_rich/index.php?error=coulnt_connect");
                        exit();
                    }
                    else
                    {
                        mysqli_stmt_bind_param($stmt, "i", $row_result['user_employement_id']);
                        mysqli_stmt_execute($stmt);
                        $employement_result = mysqli_stmt_get_result($stmt);
                        if ($row_result = mysqli_fetch_assoc($employement_result))
                        {
                            $user_data['employement'] = $row_result['employement_name'];
                        }
                    }
                }
            }
            // Wenn es kein Benutzer mit dieser id gibt wird man mit eine Fehlermeldung auf die Hauptseite transportiert
            else
            {
                header("Location: /social_rich/index.php?error=no_user");
                exit();
            }
        }
    }
    // Wenn der man den Script direckt anspricht wird man mit eine Fehlermeldung auf die Hauptseite transportiert
    else
    {
        header("Location: /social_rich/index.php?error=please_open_profile");
        exit();
    }

?>