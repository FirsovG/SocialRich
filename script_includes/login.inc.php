<?php

// Wenn man probiert direckt auf diesen Script draufzugreifen wird man mit eine Fehlermeldung auf die Loginformularseite transportiert
if(isset($_POST['login_submit']))
{
    // Fügt die Datenbank connection
    require dirname(__FILE__) . "/db_conn.php";

    // Speichert die Werte aus den $_Post array in temporäre Variablen
    $mail_username = $_POST['mail_username'];
    $password = $_POST['password'];

    // Wenn man ein von den Feldern leer gelassen hat (oder beide)
    if (empty($mail_username) || empty($password))
    {
        // Man wird mit eine Fehlermeldung zurückgeschikt
        header("Location: /social_rich/html/login.php?error=empty_field&mail_username=" . $mail_username);
        exit();
    }
    else
    {   
        // Ein SQL Befehl der abfragt welchen Benutzer id, Passwort dieser Benutzername hat
        $sql = "SELECT user_id, user_password FROM tbl_users WHERE user_username = ? OR user_email = ?";
        // In den get_profile_data.inc.php wird es alles ausführlich beschrieben
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("Location: /social_rich/index.php?error=coulnt_connect");
            exit();
        }
        else
        {
            // Bindet das eingetragene Benutzername in Feld username und email, weil es beides sein kann
            mysqli_stmt_bind_param($stmt, "ss", $mail_username, $mail_username);
            mysqli_stmt_execute($stmt);
            $mail_username_result = mysqli_stmt_get_result($stmt);
            if ($row_result = mysqli_fetch_assoc($mail_username_result))
            {
                // Guckt ob die Passwörter gleich sind
                $passwordCheck = password_verify($password, $row_result['user_password']);
                // Wenn nicht
                if ($passwordCheck == false)
                {
                    // Man wird mit eine Fehlermeldung zurückgeschikt 
                    header("Location: /social_rich/html/login.php?error=wrong_password&mail_username=" . $mail_username);
                    exit();
                }
                else
                {
                    // Man startet die Session und speichert den Benutzer id in den $_Session Array
                    session_start();
                    $_SESSION['user_id'] = $row_result['user_id'];
                    // Man wird mit eine Erfolgmeldung auf die hauptseite transportiert
                    header("Location: /social_rich/index.php?successful=login");
                    exit();
                }
            }
            else
            {
                // Man wird mit eine Fehlermeldung zurückgeschikt 
                header("Location: /social_rich/html/login.php?error=no_user");
                exit();
            }
        }
    }
}
else
{
    header("Location: /social_rich/html/login.php?error=first_fill_the_form");
    exit();
}

?>