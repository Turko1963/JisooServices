// backend.php - This file would be on your server
<?php
session_start();

// This should be stored securely, ideally in a database
$valid_credentials = [
    'admin' => password_hash('admin123', PASSWORD_DEFAULT) // Store hashed passwords!
];

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $response = ['success' => false];
    
    // Check if username exists and password is correct
    if (isset($valid_credentials[$username]) && 
        password_verify($password, $valid_credentials[$username])) {
        
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['username'] = $username;
        $response['success'] = true;
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Check if user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['check_auth'])) {
    $response = [
        'isLoggedIn' => isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'],
        'username' => $_SESSION['username'] ?? null
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Logout
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.html');
    exit;
}
?>
