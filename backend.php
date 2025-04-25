<?php
session_start();

// Pre-generated password hash for "admin123"
$valid_credentials = [
    'admin' => '$2y$10$Tp6DREomFS26chUSdBzyKuRuDwRCFtG2DYdi1RkL93SDuD7lK/mES'
];

// Handle login (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $response = ['success' => false];

    // Check credentials
    if (isset($valid_credentials[$username]) &&
        password_verify($password, $valid_credentials[$username])) {

        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['username'] = $username;

        $response['success'] = true;
    } else {
        http_response_code(401); // Unauthorized
        $response['message'] = 'Invalid username or password.';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle session status check
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['check_auth'])) {
    $response = [
        'isLoggedIn' => isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'],
        'username' => $_SESSION['username'] ?? null
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();

    header('Location: index.html');
    exit;
}
?>

