<nav>
    <a href="../index.php"><?= t('home') ?></a>
    <a href="../pages/about.php"><?= t('about') ?></a>
    <a href="../pages/projects.php"><?= t('projects') ?></a>
    <a href="../pages/contact.php"><?= t('contact') ?></a>

    <?php if (isset($_SESSION["user_id"])): ?>
        <?php if ($_SESSION["role"] == 'admin'): ?>
            <a href="../admin/dashboard.php">Admin</a>
            <a href="../pages/recruiter.php">Recruiter-Bereich</a>
            <a href="../pages/client.php">Kunden-Bereich</a>
            <a href="../pages/familyandfriends.php">Familie & Freunde</a>
        <?php elseif ($_SESSION["role"] == 'recruiter'): ?>
            <a href="../pages/recruiter.php">Recruiter-Bereich</a>
        <?php elseif ($_SESSION["role"] == 'client'): ?>
            <a href="../pages/client.php">Kunden-Bereich</a>
        <?php elseif ($_SESSION["role"] == 'familyandfriends'): ?>
            <a href="../pages/familyandfriends.php">Familie & Freunde</a>
        <?php endif; ?>

        <a href="../auth/logout.php"><?= t('logout') ?></a>
    <?php else: ?>
        <a href="../auth/login.php"><?= t('login') ?></a>
    <?php endif; ?>
</nav>
