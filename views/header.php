<?php
include_once __DIR__ . '/../includes/lang.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Portfolio Zeno</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/app.js" defer></script>
    <link rel="icon" type="image/x-icon" href="/favicon.png">
    <link rel="shortcut icon" href="/favicon.png">
</head>
<body class="fade-in">
<header class="site-header">
    <div class="logo">
        <!-- Ersetze logo.png durch dein eigenes Logo -->
        <img src="/assets/img/logo.png" alt="Logo">
    </div>
    <div class="lang-switch">
        <a href="?lang=de">DE</a> | <a href="?lang=en">EN</a>
    </div>
</header>
<?php include 'navigation.php'; ?>
