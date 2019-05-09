<?php
    // Wenn ein Mensch probiert auf das Link zuzugreifen wird man mit eine Fehlermeldung auf die Hauptseite transportiert
    if (isset($_POST['message_submit']))
    {
        // Einfügen von Datanbank connection
        require_once dirname(__FILE__) . "/db_conn.php";
        // Startet die Session
        session_start();
        // Id von den Dialogpartner der in den Get-Array mitgegeben wurde
        $friend_id = $_GET['friend_id'];
        // Wenn die Nachricht nicht leer ist
        if ($_POST['send_messge_text'] != "")
        {
            // Es werden Daten die nötig sind in ein Array gesamelt
            $message = array(
                'chat_id' => $_GET['chat_id'],
                'sender_id' => $_SESSION['user_id'],
                'message' => $_POST['send_messge_text']
            );
            // SQL Befehl der die Werte aus dein Array einfügt
            $sql = "INSERT INTO tbl_messages(chat_id, message_sender_id, message_text) VALUES (?,?,?)";
            $stmt = mysqli_stmt_init($connection);
            if (!mysqli_stmt_prepare($stmt, $sql))
            {
                header("Location: /social_rich/index.php?error=coulnt_connect");
                exit();
            }
            else
            {
                mysqli_stmt_bind_param($stmt, "iis", $message['chat_id'], $message['sender_id'], $message['message']);
                mysqli_stmt_execute($stmt);
                // Man wird auf die Chatseite transporiert
                header("Location: /social_rich/html/chat.php?id=$friend_id");
                exit();
            }
        }
        // Sonst wird man mit eine Fehlermeldung auf den Chat zurück transportiert
        else
        {
            header("Location: /social_rich/html/chat.php?id=$friend_id&error=please_enter_some_text");
            exit();
        }
    }
    else
    {
        header("Location: /social_rich/index.php?error=login_first");
        exit();
    }
?>