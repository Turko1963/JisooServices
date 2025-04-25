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

    // Log the posted data for debugging (delete this line in production)
    file_put_contents('debug_log.txt', print_r($_POST, true));

    $response = ['success' => false];

    // Check if the username exists and password is valid
    if (isset($valid_credentials[$username]) &&
        password_verify($password, $valid_credentials[$username])) {

        session_regenerate_id(true); // Protect against session fixation
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

// Handle session check (GET request with ?check_auth=1)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['check_auth'])) {
    $response = [
        'isLoggedIn' => isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'],
        'username' => $_SESSION['username'] ?? null
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle logout (GET request with ?logout=1)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();

    header('Location: index.html');
    exit;
}
?>
