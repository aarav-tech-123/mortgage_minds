<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// Accept POST request only
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect POST values
    $first_name  = trim($_POST['first_name'] ?? '');
    $last_name   = trim($_POST['last_name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $what_is_your_mortgage_balance = trim($_POST['what_is_your_mortgage_balance'] ?? '');
    $what_is_your_property_value   = trim($_POST['what_is_your_property_value'] ?? '');
    $when_will_your_fix_rate_expire = trim($_POST['when_will_your_fix_rate_expire'] ?? '');
    $best_time_to_call             = trim($_POST['best_time_to_call'] ?? '');

    // ============================
    //  REQUIRED FIELD VALIDATION
    // ============================

    if (
        empty($first_name) ||
        empty($last_name) ||
        empty($email) ||
        empty($phone_number) ||
        empty($what_is_your_mortgage_balance) ||
        empty($what_is_your_property_value) ||
        empty($when_will_your_fix_rate_expire)
    ) {
        echo json_encode(["status" => "error", "message" => "Please fill all required fields."]);
        exit();
    }

    // ============================
    //  FORM FIELD VALIDATION
    // ============================

    // First name validation
    // if (!preg_match("/^[a-zA-Z ]+$/", $first_name)) {
    //     echo json_encode(["status" => "error", "message" => "First name must contain letters only."]);
    //     exit();
    // }

    // Last name validation
    // if (!preg_match("/^[a-zA-Z ]+$/", $last_name)) {
    //     echo json_encode(["status" => "error", "message" => "Last name must contain letters only."]);
    //     exit();
    // }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format."]);
        exit();
    }

    // Phone validation (digits only, minimum 7 digits)
    // if (!preg_match("/^[0-9]{7,}$/", $phone_number)) {
    //     echo json_encode(["status" => "error", "message" => "Phone number must be numeric and at least 7 digits."]);
    //     exit();
    // }

    // ============================
    //  INSERT INTO DATABASE
    // ============================

    $stmt = $conn->prepare("
        INSERT INTO refinance_applications 
        (first_name, last_name, email, phone_number, 
         what_is_your_mortgage_balance, what_is_your_property_value, 
         when_will_your_fix_rate_expire, best_time_to_call)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssssss",
        $first_name,
        $last_name,
        $email,
        $phone_number,
        $what_is_your_mortgage_balance,
        $what_is_your_property_value,
        $when_will_your_fix_rate_expire,
        $best_time_to_call
    );

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "redirect" => "thank-you.html"]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Database insert failed"]);
        exit();
    }
}

$conn->close();
?>
