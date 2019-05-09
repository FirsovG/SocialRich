<?php 

    // Dateiname-Variable der davor auf der Hauptseite inizialisiert wurde
    if ($filename == "profile_change_settings")
    {
        // Database connection
        require_once dirname(__FILE__) . "/db_conn.php";

        // Array der die Daten über den Benutzer speichert
        $user_data = array(
            "id" => $_GET['id'],
            "firstname" => "",
            "surname" => "",
            "birthday" => "2000-01-01",
            "status" => "",
            "employement" => 0
        );

        // SQL Befehl der die Information über den Benutzer abfragt
        $sql = "SELECT tbl_users.user_firstname, tbl_users.user_surname, tbl_users.user_birthday, tbl_users.user_status, tbl_users.user_employement_id
                From tbl_users Where user_id=?";
        // In den get_profile_data.inc.php wird es alles ausführlich beschrieben
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("Location: /social_rich/index.php?error=coulnt_connect");
            exit();
        }
        else
        {
            // Bindet die Id von den Benutzer
            mysqli_stmt_bind_param($stmt, "i", $user_data['id']);
            mysqli_stmt_execute($stmt);
            $username_result = mysqli_stmt_get_result($stmt);
            if ($row_result = mysqli_fetch_assoc($username_result))
            {
                // Speichert die Daten die nicht null sein dürfen
                $user_data['firstname'] = $row_result['user_firstname'];
                $user_data['surname'] = $row_result['user_surname'];
                // Wenn der Benutzer sein Geburtsdatum eingetragen hat wird es eingetragen
                if ($row_result['user_birthday'] != null)
                {
                    $user_data['birthday'] = $row_result['user_birthday'];
                }
                // Wenn der Benutzer sein Status eingetragen hat wird es eingetragen
                if ($row_result['user_status'] != null)
                {
                    $user_data['status'] = $row_result['user_status'];
                }
                // Fragt ab ob der User seine Beschäftigung eingetragen hat, wenn ja dann speichert der die Id
                if ($row_result['user_employement_id'] != null)
                {
                    $user_data['employement'] = $row_result['user_employement_id'];
                }
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