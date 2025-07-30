<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine current language
$lang = 'de';
if (isset($_GET['lang'])) {
    $langParam = strtolower($_GET['lang']);
    if (in_array($langParam, ['de', 'en'])) {
        $lang = $langParam;
        $_SESSION['lang'] = $lang;
    }
} elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
}

$translations = [
    'de' => [
        'home' => 'Home',
        'about' => 'Über mich',
        'projects' => 'Projekte',
        'contact' => 'Kontakt',
        'login' => 'Login',
        'logout' => 'Logout',
        'welcome_title' => 'Willkommen zu meinem Portfolio',
        'welcome_text' => 'Ich bin [ Dein Name ], ein [ Dein Beruf ]. Hier findest du eine Auswahl meiner besten Projekte.',
        'read_more' => 'Mehr über mich',
        'about_intro' => 'Ich bin [Dein Name], ein leidenschaftlicher [Berufsbezeichnung].',
        'contact_intro' => 'Interessiert an einer Zusammenarbeit? Kontaktieren Sie mich per E-Mail:',
        'projects_title' => 'Meine Projekte',
        'no_projects' => 'Keine Projekte verfügbar.',
        'more_info' => 'Mehr erfahren'
    ],
    'en' => [
        'home' => 'Home',
        'about' => 'About me',
        'projects' => 'Work',
        'contact' => 'Contact',
        'login' => 'Login',
        'logout' => 'Logout',
        'welcome_title' => 'Welcome to my portfolio',
        'welcome_text' => 'I am [Your Name], a [Your Profession]. Here you can find a selection of my best projects.',
        'read_more' => 'Read more',
        'about_intro' => 'I am [Your Name], a passionate [Profession].',
        'contact_intro' => 'Interested in collaborating? Contact me via email:',
        'projects_title' => 'My Projects',
        'no_projects' => 'No projects available.',
        'more_info' => 'Learn more'
    ]
];

function t(string $key): string {
    global $translations, $lang;
    return $translations[$lang][$key] ?? $key;
}
?>
