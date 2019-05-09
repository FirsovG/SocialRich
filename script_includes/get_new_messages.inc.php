<?php

    // Startet die Session auf der Seite
    session_start();

    // Wenn der Benutzer nicht angemeldet wird man mit eine Fehlermeldung auf die Hauptseite transportiert
    if (!isset($_SESSION['user_id']))
    {
        header("Location: /social_rich/index.php?error=first_log_in");
        exit();
    }

    // Fügt die Datenbank connection ein
    require_once "db_conn.php";

    // SQL Befehl der abfragt wer an diesen Dialog beteildigt ist
    $sql = "SELECT chat_user_1, chat_user_2 FROM tbl_chats WHERE chat_id=?";
    // In den get_profile_data.inc.php wird es alles ausführlich beschrieben
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $sql))
    {   
        echo 'HERE';
        echo '<div class="row message clearfix server">
                <div class="col-0 col-md-2"></div>
                <div class="col-12 col-md-8">
                    <h2>Server</h2>
                    <p>Coudnt connect to the server</p>
                </div>
                <div class="col-0 col-md-2 image_container"></div>
              </div>';
        exit();
    }
    else
    {
        // Bindet den id von den Dailog
        mysqli_stmt_bind_param($stmt, "i", $_GET['chat_id']);
        mysqli_stmt_execute($stmt);
        $user_result = mysqli_stmt_get_result($stmt);
        if ($row_result = mysqli_fetch_assoc($user_result))
        {
            // Wenn der angemeldeter Benutzer zu dem Dialog gehört
            if ($row_result['chat_user_1'] == $_SESSION['user_id'] || $row_result['chat_user_2'] == $_SESSION['user_id'])
            {
                // SQL Befehlt der die Nachrichten und das id von den jenigen der die Nachricht abgeschikt hat abfragt
                $sql = "SELECT message_sender_id, message_text FROM tbl_messages WHERE chat_id=? ORDER BY message_id ASC";
                // In den get_profile_data.inc.php wird es alles ausführlich beschrieben
                $stmt = mysqli_stmt_init($connection);
                if (!mysqli_stmt_prepare($stmt, $sql))
                {
                    echo '<div class="row message clearfix server">
                            <div class="col-0 col-md-2"></div>
                            <div class="col-12 col-md-8">
                                <h2>Server</h2>
                                <p>Coudnt connect to the server</p>
                            </div>
                            <div class="col-0 col-md-2 image_container"></div>
                        </div>';
                    exit();
                }
                else
                {
                    // Bindet den Chat id
                    mysqli_stmt_bind_param($stmt, "i", $_GET['chat_id']);
                    mysqli_stmt_execute($stmt);
                    $messages_result = mysqli_stmt_get_result($stmt);
                    // Ich weiss nicht genau wie viele einträge zurückkomen deswegen benutze ich hier eine while Schleife
                    while ($row_result = mysqli_fetch_assoc($messages_result))
                    {
                        // Wenn es eine Nachricht von den angemeldeten Benutzer kommt wird die Struktur dafür genommen 
                        if ($row_result['message_sender_id'] == $_GET['your_id'])
                        {
                            ?>
                                <div class="row message clearfix you">
                                    <div class="col-0 col-md-2"></div>
                                    <div class="col-12 col-md-8">
                                        <h2>You</h2>
                                        <p><?php echo $row_result['message_text'] ; ?></p>
                                    </div>
                                    <div class="col-0 col-md-2 image_container">
                                        <div class="image_wrapper">
                                            <img src="/social_rich/content/img/<?php echo $_GET['your_image']; ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                            <?php 
                        }
                        // Sonst für den Dialogpartner
                        else
                        {
                            ?>
                                <div class="row message">
                                    <div class="col-0 col-md-2 image_container">
                                        <img src="/social_rich/content/img/<?php echo $_GET['friend_image']; ?>" alt="">
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <a href="/social_rich/html/profile.php?id=<?php echo $_GET['friend_id'] ; ?>"><h2 class=username><?php echo $_GET['friend_name'] ; ?></h2></a>
                                        <p class="text"><?php echo $row_result['message_text'] ; ?></p>
                                    </div>
                                    <div class="col-0 col-md-2"></div>
                                </div>
                            <?php 
                        }
                    }
                }
            }
            else
            {
                echo '<div class="row message clearfix server">
                        <div class="col-0 col-md-2"></div>
                        <div class="col-12 col-md-8">
                            <h2>Server</h2>
                            <p>Not your dialog</p>
                        </div>
                        <div class="col-0 col-md-2 image_container"></div>
                      </div>';
                exit();
            }
        }
    }

?>