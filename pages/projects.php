<?php
include '../includes/config.php';
session_start();

$role = $_SESSION["role"] ?? "public";

$stmt = $pdo->prepare("SELECT * FROM projects WHERE visibility = 'public' OR visibility = ?");
$stmt->execute([$role]);
$projects = $stmt->fetchAll();

include '../views/header.php';
?>
<h1>Meine Projekte</h1>
<ul>
    <?php foreach ($projects as $project): ?>
        <li>
            <h2><?= htmlspecialchars($project["title"]); ?></h2>
            <p><?= nl2br(htmlspecialchars($project["description"])); ?></p>
            
            <?php if (!empty($project["media_url"])): ?>
                <img src="<?= htmlspecialchars($project["media_url"]); ?>" alt="Projektbild" style="max-width: 100%; height: auto;">
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php include '../views/footer.php'; ?>
