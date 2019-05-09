<?php

    // Dateiname-Variable der davor auf der Hauptseite inizialisiert wurde
    if ($filename == "find_friends")
    {
        // Fügt die Datenbank connection ein
        require_once "db_conn.php";

        // Array von den Profilen
        $profiles = array();

        //assoziatives Array mit Profilinfo welches danach eingefügt wird
        $profile = array(
            'user_id' => '',
            'user_name' => '',
            'user_image' => 'std_user.png',
            'user_employement' => 'not set'
        );

        // SQL Befehl die alle Benutzer auswählt
        $sql = "SELECT user_id, user_firstname, user_surname, user_image, user_employement_id FROM tbl_users";
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
            $profiles_result = mysqli_stmt_get_result($stmt);
            // Ich weiss nicht genau wie viele einträge zurückkomen deswegen benutze ich hier eine while Schleife
            while ($row_result = mysqli_fetch_assoc($profiles_result))
            {
                // Wenn die id von den angemeldeten Benutzer mit den id aus der Datenbank übereinstimmt 
                // wird die Schleife für diese Zeile übersprungen, weil es kein Sinn macht sich selber anzuzeigen
                if ($row_result['user_id'] == $_SESSION['user_id'])
                {
                    continue;
                }
                else
                {
                    // Die id und der Name werden reingespeichert
                    $profile['user_id'] = $row_result['user_id'];
                    $profile['user_name'] = $row_result['user_firstname'] . " " . $row_result['user_surname'];
                    // Wenn der Benutzer ein eigenes Bild hat
                    if ($row_result['user_image'] != null)
                    {
                        // Wird der Dateiname eingetragen
                        $profile['user_image'] = 'user' . $row_result['user_image'];
                    }
                    // Wenn der Benutzer eine Beschäftigung eingetragen hat
                    if ($row_result['user_employement_id'] != null)
                    {
                        // Wird der Name von der Beschäftigung rausgefunden und eingespeichert
                        $sql = "SELECT employement_name FROM tbl_employements WHERE employement_id=?";
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
                            $emloyement_result = mysqli_stmt_get_result($stmt);
                            if ($row_emloyement_result = mysqli_fetch_assoc($emloyement_result))
                            {
                                $profile['user_employement'] = $row_emloyement_result['employement_name'];
                            }
                        }
                    }
                }
                array_push($profiles, $profile);
                $profile = array(
                    'user_id' => '',
                    'user_name' => '',
                    'user_image' => 'std_user.png',
                    'user_employement' => 'not set'
                );
            }
        }
    }
?>