<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch ($action) {
    case 'register':
        $required = ['name', 'username', 'email', 'phone', 'password'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Field '$field' is required"]);
                exit;
            }
        }
        
        if ($input['password'] !== $input['confirm_password']) {
            http_response_code(400);
            echo json_encode(['error' => 'Passwords do not match']);
            exit;
        }
        
        if (createUser($input)) {
            echo json_encode(['success' => true, 'message' => 'Registration successful']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Registration failed']);
        }
        break;
        
    case 'login':
        if (empty($input['username']) || empty($input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Username and password required']);
            exit;
        }
        
        $user = authenticateUser($input['username'], $input['password']);
        if ($user) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }
        break;
        
    case 'logout':
        session_start();
        session_destroy();
        echo json_encode(['success' => true]);
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}
?>