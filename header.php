<div class="flex-header">


    <a href="./index.html">LOGO</a>

    <nav>
        <ul>
            <li <?php if ($_SERVER['PHP_SELF'] == '/index.php') echo 'class="active"'; ?>><a href="index.php">Home</a>
            </li>
            <li <?php if ($_SERVER['PHP_SELF'] == '/about.php') echo 'class="active"'; ?>><a href="about.php">About
                    me</a></li>
            <li <?php if ($_SERVER['PHP_SELF'] == '/photography.php') echo 'class="active"'; ?>><a
                    href="projects.php">Projects</a></li>
            <li <?php if ($_SERVER['PHP_SELF'] == '/contact.php') echo 'class="active"'; ?>><a
                    href="contact.php">Contact</a></li>
        </ul>
    </nav>


</div>