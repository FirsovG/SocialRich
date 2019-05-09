<?php
    
    // Dateiname-Variable der davor auf der Hauptseite inizialisiert wurde
    if ($filename == "chat")
    {
        // Fügt die Datenbank connection hinzu
        require_once "db_conn.php";

        // Ein Array welches die Daten über den Dialog speichert
        $chat = array(
            'chat_id' => '',
            'your_id' => $_SESSION['user_id'],
            'your_image' => 'std_user.png',
            'friend_id' => $_GET['id'],
            'friend_name' => '',
            'friend_image' => 'std_user.png'
        );

        // SQL Befehl der die Daten von den Dialogparner abfragt
        $sql = "SELECT user_firstname, user_image FROM tbl_users WHERE user_id=?";
        // In den get_profile_data.inc.php wird es alles ausführlich beschrieben
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("Location: /social_rich/index.php?error=coulnt_connect");
            exit();
        }
        else
        {
            // Bindet den id von den Dailogpartner
            mysqli_stmt_bind_param($stmt, "i", $chat['friend_id']);
            mysqli_stmt_execute($stmt);
            $friend_name_result = mysqli_stmt_get_result($stmt);
            if ($row_result = mysqli_fetch_assoc($friend_name_result))
            {
                $chat['friend_name'] = $row_result['user_firstname'];
                // Wenn der user ein eigenes Bild hat wird das reingeladen
                if ($row_result['user_image'] != null)
                {
                    $chat['friend_image'] = 'user' . $row_result['user_image'];
                }
                // SQL Befehl der abragt ob es davor schon ein Dialog zwischen den Bunutzern gab
                $sql = "SELECT chat_id FROM tbl_chats WHERE (tbl_chats.chat_user_1 = ? or tbl_chats.chat_user_1 = ?) AND (tbl_chats.chat_user_2 = ? or tbl_chats.chat_user_2 = ?);";
                $stmt = mysqli_stmt_init($connection);
                if (!mysqli_stmt_prepare($stmt, $sql))
                {
                    header("Location: /social_rich/index.php?error=coulnt_connect");
                    exit();
                }
                else
                {
                    // Bindet die id's
                    mysqli_stmt_bind_param($stmt, "iiii", $chat['your_id'], $chat['friend_id'], $chat['your_id'], $chat['friend_id']);
                    mysqli_stmt_execute($stmt);
                    $chat_result = mysqli_stmt_get_result($stmt);
                    if ($row_result = mysqli_fetch_assoc($chat_result))
                    {
                        $chat['chat_id'] = $row_result['chat_id'];
                    }
                    else
                    {
                        // Wenn es keins gibt wird ein Neuer Dialog eingetragen
                        $sql = "INSERT INTO tbl_chats(chat_user_1, chat_user_2) VALUES (?,?)";
                        $stmt = mysqli_stmt_init($connection);
                        if (!mysqli_stmt_prepare($stmt, $sql))
                        {
                            header("Location: /social_rich/index.php?error=coulnt_connect");
                            exit();
                        }
                        else
                        {
                            // Fügt die beiden id in den SQL Befehl
                            mysqli_stmt_bind_param($stmt, "ii", $chat['your_id'], $chat['friend_id']);
                            mysqli_stmt_execute($stmt);
                            // Fragt die chat_id ab um es später in den Get-Array für Nachricht sendung einzufügen  
                            $sql = "SELECT chat_id FROM tbl_chats WHERE (tbl_chats.chat_user_1 = ? or tbl_chats.chat_user_1 = ?) AND (tbl_chats.chat_user_2 = ? or tbl_chats.chat_user_2 = ?);";
                            $stmt = mysqli_stmt_init($connection);
                            if (!mysqli_stmt_prepare($stmt, $sql))
                            {
                                header("Location: /social_rich/index.php?error=coulnt_connect");
                                exit();
                            }
                            else
                            {
                                // Bindet die id's
                                mysqli_stmt_bind_param($stmt, "iiii", $chat['your_id'], $chat['friend_id'], $chat['your_id'], $chat['friend_id']);
                                mysqli_stmt_execute($stmt);
                                $chat_result = mysqli_stmt_get_result($stmt);
                                if ($row_result = mysqli_fetch_assoc($chat_result))
                                {
                                    // Specihert die id von den chat ein
                                    $chat['chat_id'] = $row_result['chat_id'];
                                }
                            }
                        }
                    }
                    // SQL Befehl der die Daten von den angemeldeten Benutzer abfragt
                    $sql = "SELECT user_image FROM tbl_users WHERE user_id=?";
                    $stmt = mysqli_stmt_init($connection);
                    if (!mysqli_stmt_prepare($stmt, $sql))
                    {
                        header("Location: /social_rich/index.php?error=coulnt_connect");
                        exit();
                    }
                    else
                    {
                        // Bindet die id von den angemeldeten Benutzer
                        mysqli_stmt_bind_param($stmt, "i", $chat['your_id']);
                        mysqli_stmt_execute($stmt);
                        $your_name_result = mysqli_stmt_get_result($stmt);
                        if ($row_result = mysqli_fetch_assoc($your_name_result))
                        {
                            // Wenn der angemeldeten Benutzer ein eigenes Bild hat wird der Dateinname reingespeichert
                            if ($row_result['user_image'] != null) 
                            {
                                $chat['your_image'] = 'user' . $row_result['user_image'];
                            }
                        }
                    }
                }
            }
        }
    }

?>