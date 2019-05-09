<?php

    // Dateiname-Variable der davor auf der Hauptseite inizialisiert wurde
    if ($filename == "chats")
    {
        // Fügt die Datenbank connection hinzu
        require_once "db_conn.php";

        // Array von den Dialogen
        $chats = array();

        // assoziatives Array mit Dialoginfo welches danach eingefügt wird
        $chat = array(
            'chat_id' => '',
            'friend_id' => '',
            'friend_firstname' => '',
            'friend_surname' => '',
            'friend_image' => 'std_user.png',
            'last_message_sender_name' => '',
            'last_message' => ''
        );

        // SQL Befehl alle chats die der angemeldeter Benutzer abfragt
        // Wir wissen nicht genau an welche das id von den angemeldeten Benutzer ist 
        // deswegen kann der chat_user_1 oder chat_user_2 sein
        $sql = "SELECT chat_id, chat_user_1, chat_user_2 FROM tbl_chats WHERE chat_user_1=? OR chat_user_2=?";
        // In den get_profile_data.inc.php wird es alles ausführlich beschrieben
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("Location: /social_rich/index.php?error=coulnt_connect");
            exit();
        }
        else
        {
            // Die id von den angemeldeten Benutzer wird für chat_user_1 und chat_user_2 eingetragen
            mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user_id'], $_SESSION['user_id']);
            mysqli_stmt_execute($stmt);
            $chats_result = mysqli_stmt_get_result($stmt);
            // Ich weiss nicht genau wie viele einträge zurückkomen deswegen benutze ich hier eine while Schleife
            while ($row_result = mysqli_fetch_assoc($chats_result))
            {
                // Speichert den Dialog-Id in den Array
                $chat['chat_id'] = $row_result['chat_id'];
                // Wenn der chat_user_1 äquivalent den id von den angemeldeten Benutzer
                if ($row_result['chat_user_1'] == $_SESSION['user_id'])
                {
                    // Dann ist die id von den Dialogpartner die andere
                    $chat['friend_id'] = $row_result['chat_user_2'];
                }
                // Sonst
                else
                {
                    // Ist chat_user_1 der Dialogpartner
                    $chat['friend_id'] = $row_result['chat_user_1'];
                }
                // SQL Befehl der Information über den Dialogpartner abruft
                $sql = "SELECT user_firstname, user_surname, user_image FROM tbl_users WHERE user_id=?";
                $stmt = mysqli_stmt_init($connection);
                if (!mysqli_stmt_prepare($stmt, $sql))
                {
                    header("Location: /social_rich/index.php?error=coulnt_connect");
                    exit();
                }
                else
                {
                    // Bindet die id von den Dialogpartner
                    mysqli_stmt_bind_param($stmt, "i", $chat['friend_id']);
                    mysqli_stmt_execute($stmt);
                    $friend_info_result = mysqli_stmt_get_result($stmt);
                    if ($row_friend_info_result = mysqli_fetch_assoc($friend_info_result))
                    {
                        // Speichert die sachen die nicht null sein dürfen
                        $chat['friend_firstname'] = $row_friend_info_result['user_firstname'];
                        $chat['friend_surname'] = $row_friend_info_result['user_surname'];
                        // Wenn der Benutzer ein eigenes Bild hat
                        if ($row_friend_info_result['user_image'] != null)
                        {
                            // Wird der Dateiname eingetragen
                            $chat['friend_image'] = 'user' . $row_friend_info_result['user_image'];
                        }
                        // SQL Befehl der letzten Nachricht und der jenige der es geschikt hat
                        $sql = "SELECT message_sender_id, message_text FROM tbl_messages WHERE chat_id=? ORDER BY message_id DESC LIMIT 1";
                        $stmt = mysqli_stmt_init($connection);
                        if (!mysqli_stmt_prepare($stmt, $sql))
                        {
                            header("Location: /social_rich/index.php?error=coulnt_connect");
                            exit();
                        }
                        else
                        {
                            // Bindet den Dialog-Id
                            mysqli_stmt_bind_param($stmt, "i", $chat['chat_id']);
                            mysqli_stmt_execute($stmt);
                            $last_message_result = mysqli_stmt_get_result($stmt);
                            if ($row_last_message_result = mysqli_fetch_assoc($last_message_result))
                            {
                                // Wenn es überhaupt eine Nachricht jemals geschikt wurde
                                if ($row_last_message_result['message_sender_id'] != null)
                                {
                                    // Text von der letzten Nachricht
                                    $chat['last_message'] = $row_last_message_result['message_text'];
                                    // Wenn die letzte Nachricht von den Dialogpartner kommt
                                    if ($row_last_message_result['message_sender_id'] == $chat['friend_id'])
                                    {
                                        // Wird sein name eingetragen
                                        $chat['last_message_sender_name'] = $chat['friend_firstname'];
                                    }
                                    // Sonst ist es der angemeldeter Benutzer
                                    else
                                    {
                                        $chat['last_message_sender_name'] = "You";
                                    }
                                }
                            }
                        }
                    }
                }
                array_push($chats, $chat);
                $chat = array(
                    'chat_id' => '',
                    'friend_id' => '',
                    'friend_firstname' => '',
                    'friend_surname' => '',
                    'friend_image' => 'std_user.png',
                    'last_message' => ''
                );
            }
        }
    }

?>