
<!-- Fügt eine Navbar ein -->

<nav>
    <!-- Logo -->
    <a href="/social_rich/index.php" class="logo">SoR</a>
    <!-- Eine versteckte checkbox die in der Mobilversion bestimmen wird ob die Navbar aktiv ist  -->
    <input type="checkbox" id="cbMenu">

    <!-- Ein Label für die Checkbox, wenn es angeklickt wird erscheint die Navbar bei mobilen Geräten von der Seite -->
    <label for="cbMenu" class="show_menu_btn">
        <i class="fas fa-bars"></i>
    </label>

    <!-- Menu die sich variiert je auf welche Seite man ist  -->
    <div class="menu">
        <?php 
            // Wenn man angemeldet ist werden andere Links angezeigt
            if (isset($_SESSION['user_id']))
            {
                // Wenn die Datei "profile" heißt und die den Benutzer gehört wird das Link für den Profile nicht angezeigt
                if ($filename == "profile" && $your_profile)
                {
                    ?>
                        <a href="/social_rich/html/find_friends.php"><i class="fas fa-search"></i>Find friends</a>
                        <a href="/social_rich/html/chats.php"><i class="far fa-comments"></i>Chats</a>
                        <a href="/social_rich/script_includes/logout.inc.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                    <?php
                }
                // Wenn die Datei "find_friends" heißt wird das Link für den Find_Friends nicht angezeigt
                else if ($filename == "find_friends")
                {
                    ?>
                        <a href="/social_rich/html/chats.php"><i class="far fa-comments"></i>Chats</a>
                        <a href="/social_rich/html/profile.php?id=<?php echo $_SESSION['user_id'] ; ?>"><i class="fas fa-user-tie"></i>Profile</a>
                        <a href="/social_rich/script_includes/logout.inc.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                    <?php
                }
                // Sonst wird der ganze Navbar für ein angemeldeten Benutzer angezeigt
                else
                {
                    ?>
                        <a href="/social_rich/html/find_friends.php"><i class="fas fa-search"></i>Find friends</a>
                        <a href="/social_rich/html/chats.php"><i class="far fa-comments"></i>Chats</a>
                        <a href="/social_rich/html/profile.php?id=<?php echo $_SESSION['user_id'] ; ?>"><i class="fas fa-user-tie"></i>Profile</a>
                        <a href="/social_rich/script_includes/logout.inc.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                    <?php
                }
            }
            // Wenn man nicht angemeldet wird das Link zum anmelden oder regestrieren
            else
            {
                // Wenn man auf Signup ist wird der Link zur Login führen
                if ($filename == "signup")
                {
                    ?>
                        <a href="/social_rich/html/login.php"><i class="fas fa-user-tie"></i>Signup/Login</a>
                    <?php
                }
                // Sonst weist der Link auf Signup
                else
                {
                    ?>
                        <a href="/social_rich/html/signup.php"><i class="fas fa-user-tie"></i>Signup/Login</a>
                    <?php
                }
            }
        ?>
        <!-- Ein Label für die Checkbox, wenn es angeklickt wird verteckt er die Navbar bei mobilen Geräten -->
        <label for="cbMenu" class="hide_menu_btn">
            <i class="fas fa-times"></i>
        </label>
    </div>
</nav>