<?php
include '../includes/config.php';
include '../includes/lang.php';

$role = $_SESSION["role"] ?? "public";

include '../views/header.php';

if ($role === "admin") {
    $sql = "SELECT DISTINCT p.* FROM projects p";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
} else {
    $sql = "SELECT DISTINCT p.* FROM projects p
            LEFT JOIN project_visibility pv ON p.id = pv.project_id
            WHERE pv.access_level = 'public' OR pv.access_level = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$role]);
}

$projects = $stmt->fetchAll();
?>

<main>
    <h1><?= t('projects_title') ?></h1>
    <ul>
        <?php if (empty($projects)): ?>
            <li><?= t('no_projects') ?></li>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
                <li style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <h2><?= htmlspecialchars($project['title']) ?></h2>
                    <p><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>

                    <?php
                    $stmt = $pdo->prepare("SELECT access_level FROM project_visibility WHERE project_id = ?");
                    $stmt->execute([$project['id']]);
                    $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    ?>

                    <?php if ($role !== "admin" && in_array("private", $roles)): ?>
                        <!-- Private Projekte sind für andere nicht sichtbar -->
                        <?php continue; ?>
                    <?php endif; ?>

                    <p><strong>Sichtbar für:</strong> <?= implode(", ", $roles) ?></p>

                    <a href="project_detail.php?id=<?= $project['id'] ?>">🔍 <?= t('more_info') ?></a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <a href="../index.php">⬅ <?= t('home') ?></a>
</main>
<?php include '../views/footer.php'; ?>
