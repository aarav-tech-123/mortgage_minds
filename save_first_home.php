<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "mortgage_minds";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// Check method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect POST data
    $first_name  = trim($_POST['first_name'] ?? '');
    $last_name   = trim($_POST['last_name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $where_are_you_located         = trim($_POST['where_are_you_located'] ?? '');
    $what_describe_you_the_most    = trim($_POST['what_describe_you_the_most'] ?? '');
    $what_is_your_household_income = trim($_POST['what_is_your_household_income'] ?? '');
    $what_deposit_do_you_have      = trim($_POST['what_deposit_do_you_have'] ?? '');
    $best_time_to_call             = trim($_POST['best_time_to_call'] ?? '');

    // ============================
    //  SERVER-SIDE VALIDATION
    // ============================

    // Required field validation
    if (
        empty($first_name) ||
        empty($last_name) ||
        empty($email) ||
        empty($phone) ||
        empty($where_are_you_located) ||
        empty($what_describe_you_the_most) ||
        empty($what_is_your_household_income) ||
        empty($what_deposit_do_you_have)
    ) {
        echo json_encode(["status" => "error", "message" => "All required fields must be filled."]);
        exit();
    }

    
    // Email validation
    // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //     echo json_encode(["status" => "error", "message" => "Invalid email address."]);
    //     exit();
    // }

    // Phone validation (digits only, min 7 digits)
    // if (!preg_match("/^[0-9]{7,}$/", $phone)) {
    //     echo json_encode(["status" => "error", "message" => "Phone number must be numeric and at least 7 digits."]);
    //     exit();
    // }

    // ========================================
    //  INSERT INTO DATABASE
    // ========================================

    $stmt = $conn->prepare("
        INSERT INTO first_home_applications 
        (first_name, last_name, email, phone, where_are_you_located, 
         what_describe_you_the_most, what_is_your_household_income, 
         what_deposit_do_you_have, best_time_to_call)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssssssss",
        $first_name,
        $last_name,
        $email,
        $phone,
        $where_are_you_located,
        $what_describe_you_the_most,
        $what_is_your_household_income,
        $what_deposit_do_you_have,
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
