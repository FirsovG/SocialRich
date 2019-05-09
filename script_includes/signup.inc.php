<?php 

    // Wenn man probiert direckt auf diesen Script draufzugreifen wird man mit eine Fehlermeldung auf die Regestierungsseite transportiert
    if (isset($_POST['signup_submit']))
    {
        // Fügt die Datenbankconnection hinzu
        require dirname(__FILE__) . "/db_conn.php";

        // Temporäre Variablen die die Werte aus den Post-Array speichert
        $username    = $_POST['username'];
        $email       = $_POST['email'];
        $password    = $_POST['password'];
        $re_password = $_POST['re_password'];

        $firstname   = $_POST['firstname'];
        $surname  = $_POST['surname'];

        // Es sind alles Pflichtfelder und wenn ein oder mehrere davon leersind wird man mit eine Fehlermeldung auf die Regestierungsseite transportiert
        if (empty($firstname) || empty($surname) || empty($username) || empty($email) || empty($password) || empty($re_password))
        {
            header("Location: /social_rich/html/signup.php?error=empty_field".
                              "&username=" . $username . "&email=" . $email . "&firstname=" . $firstname . "&surname=" . $surname);
            exit();
        }
        // Wenn im namen nicht nur der Alphabet drin ist
        else if (!preg_match("/^[a-zA-Z]*$/", $firstname) || !preg_match("/^[a-zA-Z]*$/", $surname))
        {
            header("Location: /social_rich/html/signup.php?error=name_number&username=" . $username . "&email=" . $email);
            exit();
        }
        // Wenn der username nicht nur aus Zahlen und Alphabet besteht
        else if (!preg_match("/^[a-zA-Z0-9]*$/", $username))
        {
            header("Location: /social_rich/html/signup.php?error=invalid_username&email=" . $email . "&firstname=" . $firstname . "&surname=" . $surname);
            exit();
        }
        // Wenn die eingetragene passörter nicht übereinstimmen
        else if ($password != $re_password)
        {
            header("Location: /social_rich/html/signup.php?error=password_do_not_match&username=" . $username . "&email=" . $email . "&firstname=" . $firstname . "&surname=" . $surname);
            exit();
        }
        else 
        {
            // Sql Abfrage um rauszufinden ob es ein Benutzer mit den gleichen username gibt
            $sql = "Select user_username From tbl_users Where user_username=?";

            $stmt = mysqli_stmt_init($connection);
            if (!mysqli_stmt_prepare($stmt, $sql))
            {
                header("Location: /social_rich/index.php?error=coulnt_connect");
                exit();
            }
            else
            {
                // Einbundung von den Benutzernamen
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $username_count_result = mysqli_stmt_num_rows($stmt);
                // Wenn es mehr als 0 einträge gibt dann wird man auf die Regestrierungsseite transportiert
                if ($username_count_result > 0)
                {
                    header("Location: /social_rich/html/signup.php?error=username_taken&email=" . $email . "&firstname=" . $firstname . "&surname=" . $surname);
                    exit();
                }
                else
                {
                    // Sql Abfrage um rauszufinden ob es ein Benutzer mit den gleiche email gibt
                    $sql = "Select user_email From tbl_users Where user_email=?";
                    $stmt = mysqli_stmt_init($connection);
                    if (!mysqli_stmt_prepare($stmt, $sql))
                    {
                        header("Location: /social_rich/index.php?error=coulnt_connect");
                        exit();
                    }
                    else
                    {
                        // Einbindung von den email variable
                        mysqli_stmt_bind_param($stmt, "s", $email);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                        $email_count_result = mysqli_stmt_num_rows($stmt);
                        // Wenn es mehr als 0 einträge gibt dann wird man auf die Regestrierungsseite transportiert
                        if ($email_count_result > 0)
                        {
                            header("Location: /social_rich/html/signup.php?error=email_taken&username=" . $username . "&firstname=" . $firstname . "&surname=" . $surname);
                            exit();
                        }
                        // Sonst fügt der die Daten in die Datenbank
                        else
                        {
                            $sql = "INSERT INTO tbl_users(user_email, user_username, user_password, user_firstname, user_surname) VALUES (?, ?, ?, ?, ?)";
                            $stmt = mysqli_stmt_init($connection);
                            if (!mysqli_stmt_prepare($stmt, $sql))
                            {
                                header("Location: /social_rich/index.php?error=coulnt_connect");
                                exit();
                            }
                            else
                            {
                                // Verschlüsselt den Password
                                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                                // Bindet die Daten in den Query ein
                                mysqli_stmt_bind_param($stmt, "sssss", $email, $username, $hashed_password, $firstname, $surname);
                                mysqli_stmt_execute($stmt);
                                // Man wird mit eine Erfolgmeldung auf die Hauptseite transportiert
                                header("Location: /social_rich/index.php?successful=signup");
                            }
                        }
                    }
                }
            }
        }
        // Schliest den Statment
        mysqli_stmt_close($stmt);
        // Schliest die Datenbankverbindung
        mysqli_close($connection);
    }
    else
    {
        header("Location: /social_rich/html/signup.php?error=first_fill_the_form");
        exit();
    }
?>