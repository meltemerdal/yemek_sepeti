<?php
/**
 * Yemeksepeti - Kullanıcı İşlemleri API
 * Tarih: 24 Kasım 2025
 */

// Mock data kullan

$useMockData = false;

if (!$useMockData) {
    require_once 'config.php';
}

// Mock kullanıcı veritabanı (JSON dosyası simülasyonu)
$usersFile = __DIR__ . '/mock_users.json';
if (!file_exists($usersFile)) {
    file_put_contents($usersFile, json_encode([]));
}

// İşlem türünü al
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'register':
        if ($useMockData) {
            registerUserMock();
        } else {
            registerUser($conn);
        }
        break;
    case 'login':
        if ($useMockData) {
            loginUserMock();
        } else {
            loginUser($conn);
        }
        break;
    case 'addresses':
        getUserAddresses($conn);
        break;
    case 'add_address':
        addAddress($conn);
        break;
    default:
        echo json_encode(array("error" => "Geçersiz işlem"));
        break;
}

/**
 * Yeni kullanıcı kaydı oluştur
 */
function registerUser($conn) {
    // POST verilerini al
    $fullName = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($fullName) || empty($email) || empty($phone) || empty($password)) {
        echo json_encode(array("error" => "Tüm alanları doldurun"));
        return;
    }
    
    // Şifreyi hash'le (basit örnek için MD5, production'da bcrypt kullanın)
    $hashedPassword = md5($password);
    
    // Stored Procedure çağır
    $sql = "{CALL SP_CreateUser(?, ?, ?, ?)}";
    $params = array($fullName, $email, $phone, $hashedPassword);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        $sqlErrors = sqlsrv_errors();
        $errorMsg = "Kullanıcı oluşturulamadı";
        if ($sqlErrors) {
            foreach ($sqlErrors as $err) {
                $errorMsg .= " | SQLSTATE: " . $err['SQLSTATE'] . " | Code: " . $err['code'] . " | Message: " . $err['message'];
            }
        }
        echo json_encode(array("error" => $errorMsg));
        return;
    }
    
    sqlsrv_free_stmt($stmt);
    echo json_encode(array("success" => true, "message" => "Kayıt başarılı"));
}

/**
 * Kullanıcı girişi
 */
function loginUser($conn) {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        echo json_encode(array("error" => "Email ve şifre gerekli"));
        return;
    }

    $hashedPassword = md5($password);

    // DEBUG: Girişte kullanılan değerleri ekrana yazdır
    error_log("DEBUG LOGIN - Email: $email | Password: $password | Hashed: $hashedPassword");

    $sql = "SELECT UserID, FullName, Email, Phone FROM Users 
            WHERE Email = ? AND Password = ? AND IsActive = 1";
    $params = array($email, $hashedPassword);

    // DEBUG: SQL ve parametreleri ekrana yazdır
    error_log("DEBUG LOGIN - SQL: $sql | Params: " . print_r($params, true));

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo json_encode(array("error" => "Giriş yapılamadı", "details" => sqlsrv_errors()));
        return;
    }

    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);

    if ($user) {
        echo json_encode(array("success" => true, "user" => $user));
    } else {
        echo json_encode(array("error" => "Email veya şifre hatalı", "debug" => $params));
    }
}

/**
 * Kullanıcının adreslerini getir
 */
function getUserAddresses($conn) {
    $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    
    if ($userId <= 0) {
        echo json_encode(array("error" => "Geçersiz kullanıcı ID"));
        return;
    }
    
    $sql = "SELECT AddressID, Title, AddressText, District, City, IsDefault 
            FROM Addresses WHERE UserID = ? ORDER BY IsDefault DESC";
    $params = array($userId);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        echo json_encode(array("error" => "Adresler yüklenemedi", "details" => sqlsrv_errors()));
        return;
    }
    
    $addresses = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $addresses[] = $row;
    }
    
    sqlsrv_free_stmt($stmt);
    echo json_encode($addresses);
}

/**
 * Yeni adres ekle
 */
function addAddress($conn) {
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $addressText = isset($_POST['address']) ? $_POST['address'] : '';
    $district = isset($_POST['district']) ? $_POST['district'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $isDefault = isset($_POST['is_default']) ? intval($_POST['is_default']) : 0;
    
    if ($userId <= 0 || empty($addressText)) {
        echo json_encode(array("error" => "Geçersiz adres bilgileri"));
        return;
    }
    
    $sql = "INSERT INTO Addresses (UserID, Title, AddressText, District, City, IsDefault) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $params = array($userId, $title, $addressText, $district, $city, $isDefault);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        echo json_encode(array("error" => "Adres eklenemedi", "details" => sqlsrv_errors()));
        return;
    }
    
    sqlsrv_free_stmt($stmt);
    echo json_encode(array("success" => true, "message" => "Adres eklendi"));
}

/**
 * Mock - Kullanıcı Kaydı
 */
function registerUserMock() {
    global $usersFile;
    
    $fullName = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($fullName) || empty($email) || empty($phone) || empty($password)) {
        echo json_encode(array("error" => "Tüm alanları doldurun"));
        return;
    }
    
    // Dosya okuma kontrolü
    if (!file_exists($usersFile)) {
        if (!file_put_contents($usersFile, json_encode([]))) {
            echo json_encode(array("error" => "Kullanıcı dosyası oluşturulamadı"));
            return;
        }
    }
    
    $usersContent = file_get_contents($usersFile);
    $users = json_decode($usersContent, true);
    
    if (!is_array($users)) {
        $users = [];
    }
    
    // Email kontrolü
    foreach ($users as $user) {
        if (isset($user['Email']) && $user['Email'] === $email) {
            echo json_encode(array("error" => "Bu e-posta adresi zaten kayıtlı"));
            return;
        }
    }
    
    // Yeni kullanıcı ekle
    $newUser = [
        'UserID' => count($users) + 1,
        'FullName' => $fullName,
        'Email' => $email,
        'Phone' => $phone,
        'Password' => md5($password)
    ];
    
    $users[] = $newUser;
    
    if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT)) === false) {
        echo json_encode(array("error" => "Kullanıcı kaydedilemedi"));
        return;
    }
    
    echo json_encode(array("success" => true, "message" => "Kayıt başarılı"));
}

/**
 * Mock - Kullanıcı Girişi
 */
function loginUserMock() {
    global $usersFile;
    
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(array("error" => "Email ve şifre gerekli"));
        return;
    }
    
    $users = json_decode(file_get_contents($usersFile), true);
    $hashedPassword = md5($password);
    
    foreach ($users as $user) {
        if ($user['Email'] === $email && $user['Password'] === $hashedPassword) {
            unset($user['Password']); // Şifreyi gönderme
            echo json_encode(array("success" => true, "user" => $user));
            return;
        }
    }
    
    echo json_encode(array("error" => "Email veya şifre hatalı"));
}

if (!$useMockData && isset($conn)) {
    closeConnection($conn);
}
?>
