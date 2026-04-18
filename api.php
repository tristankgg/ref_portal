<?php
// Signature Referrals Portal - PHP API Backend
// Location: tgnet.com.au/database/api.php
// Database folder: tgnet.com.au/database/

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Allow preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuration
$DATABASE_DIR = __DIR__ . '/data/';
$REFERRALS_FILE = $DATABASE_DIR . 'referrals.json';
$USERS_FILE = $DATABASE_DIR . 'users.json';

// Ensure database directory exists
if (!is_dir($DATABASE_DIR)) {
    mkdir($DATABASE_DIR, 0755, true);
}

// Initialize files if they don't exist
if (!file_exists($REFERRALS_FILE)) {
    file_put_contents($REFERRALS_FILE, json_encode([]));
}
if (!file_exists($USERS_FILE)) {
    file_put_contents($USERS_FILE, json_encode([]));
}

// Get request method and action
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : null);

// Handle requests
try {
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = isset($input['action']) ? $input['action'] : $action;
        
        switch ($action) {
            case 'addReferral':
                echo json_encode(addReferral($input));
                break;
            case 'updateReferral':
                echo json_encode(updateReferral($input));
                break;
            case 'deleteReferral':
                echo json_encode(deleteReferral($input));
                break;
            case 'saveUser':
                echo json_encode(saveUser($input));
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
        }
    } elseif ($method === 'GET') {
        switch ($action) {
            case 'getReferrals':
                echo json_encode(getReferrals());
                break;
            case 'getUsers':
                echo json_encode(getUsers());
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// ==================== REFERRALS FUNCTIONS ====================

function addReferral($params) {
    global $REFERRALS_FILE;
    
    $referrals = json_decode(file_get_contents($REFERRALS_FILE), true) ?? [];
    
    $newReferral = [
        'id' => uniqid(),
        'name' => $params['name'] ?? '',
        'phone' => $params['phone'] ?? '',
        'business' => $params['business'] ?? '',
        'email' => $params['email'] ?? '',
        'notes' => $params['notes'] ?? '',
        'status' => $params['status'] ?? 'pending',
        'createdAt' => date('c'),
        'lastUpdated' => date('c')
    ];
    
    $referrals[] = $newReferral;
    file_put_contents($REFERRALS_FILE, json_encode($referrals, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    
    return ['success' => true, 'message' => 'Referral added successfully', 'data' => $newReferral];
}

function updateReferral($params) {
    global $REFERRALS_FILE;
    
    $referrals = json_decode(file_get_contents($REFERRALS_FILE), true) ?? [];
    $found = false;
    
    foreach ($referrals as &$ref) {
        if ($ref['name'] === $params['name'] && $ref['phone'] === $params['phone']) {
            $ref['business'] = $params['business'] ?? $ref['business'];
            $ref['email'] = $params['email'] ?? $ref['email'];
            $ref['notes'] = $params['notes'] ?? $ref['notes'];
            $ref['status'] = $params['status'] ?? $ref['status'];
            $ref['lastUpdated'] = date('c');
            $found = true;
            break;
        }
    }
    
    if ($found) {
        file_put_contents($REFERRALS_FILE, json_encode($referrals, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return ['success' => true, 'message' => 'Referral updated successfully'];
    } else {
        return ['success' => false, 'message' => 'Referral not found'];
    }
}

function deleteReferral($params) {
    global $REFERRALS_FILE;
    
    $referrals = json_decode(file_get_contents($REFERRALS_FILE), true) ?? [];
    $found = false;
    
    foreach ($referrals as &$ref) {
        if ($ref['name'] === $params['name'] && $ref['phone'] === $params['phone']) {
            $ref['status'] = 'trash';
            $ref['lastUpdated'] = date('c');
            $found = true;
            break;
        }
    }
    
    if ($found) {
        file_put_contents($REFERRALS_FILE, json_encode($referrals, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return ['success' => true, 'message' => 'Referral marked as trash'];
    } else {
        return ['success' => false, 'message' => 'Referral not found'];
    }
}

function getReferrals() {
    global $REFERRALS_FILE;
    
    $referrals = json_decode(file_get_contents($REFERRALS_FILE), true) ?? [];
    
    // Filter out trash
    $referrals = array_filter($referrals, function($ref) {
        return $ref['status'] !== 'trash';
    });
    
    return ['success' => true, 'message' => 'Referrals retrieved', 'data' => array_values($referrals)];
}

// ==================== USERS FUNCTIONS ====================

function saveUser($params) {
    global $USERS_FILE;
    
    $users = json_decode(file_get_contents($USERS_FILE), true) ?? [];
    $userEmail = $params['email'] ?? null;
    
    if (!$userEmail) {
        return ['success' => false, 'message' => 'Email is required'];
    }
    
    $found = false;
    
    // Check if user exists
    foreach ($users as &$user) {
        if ($user['email'] === $userEmail) {
            // Update existing user
            $user['name'] = $params['name'] ?? $user['name'];
            $user['password'] = $params['password'] ?? $user['password'];
            $user['phone'] = $params['phone'] ?? $user['phone'];
            $user['business'] = $params['business'] ?? $user['business'];
            $user['abn'] = $params['abn'] ?? $user['abn'];
            $user['notes'] = $params['notes'] ?? $user['notes'];
            $user['access'] = $params['access'] ?? $user['access'];
            $user['lastUpdated'] = date('c');
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        // Create new user
        $users[] = [
            'name' => $params['name'] ?? '',
            'email' => $userEmail,
            'password' => $params['password'] ?? '',
            'phone' => $params['phone'] ?? '',
            'business' => $params['business'] ?? '',
            'abn' => $params['abn'] ?? '',
            'notes' => $params['notes'] ?? '',
            'access' => $params['access'] ?? 'Client',
            'lastUpdated' => date('c')
        ];
    }
    
    file_put_contents($USERS_FILE, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    
    return ['success' => true, 'message' => 'User profile saved successfully'];
}

function getUsers() {
    global $USERS_FILE;
    
    $users = json_decode(file_get_contents($USERS_FILE), true) ?? [];
    
    return ['success' => true, 'message' => 'Users retrieved', 'data' => $users];
}

?>
