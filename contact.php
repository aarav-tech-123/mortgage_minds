<?php
header('Content-Type: application/json');

// Database Configuration
$servername = "localhost";
$username   = "u464227444_mortgage_minds";
$password   = "Mortgage@1234#";
$dbname     = "u464227444_mortgage_minds";

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed."
    ]);
    exit();
}

// Only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request."
    ]);
    exit();
}

// Collect Form Data
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$service  = trim($_POST['service'] ?? '');
$comments = trim($_POST['comments'] ?? '');
$terms    = isset($_POST['inlineRadioOptions']) ? 1 : 0;

// Validation
if (
    empty($name) ||
    empty($email) ||
    empty($service) ||
    empty($comments)
) {
    echo json_encode([
        "status" => "error",
        "message" => "Please fill all required fields."
    ]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email address."
    ]);
    exit();
}

if (!$terms) {
    echo json_encode([
        "status" => "error",
        "message" => "Please accept the Terms & Conditions."
    ]);
    exit();
}

// Insert Data
$stmt = $conn->prepare("
    INSERT INTO contacts
    (
        name,
        email,
        phone,
        service,
        comments,
        terms_accepted
    )
    VALUES
    (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssssi",
    $name,
    $email,
    $phone,
    $service,
    $comments,
    $terms
);

if ($stmt->execute()) {
    header("Location: thank-you.html");
    exit();
} else {
    echo "Unable to save your enquiry. Please try again.";
}

$stmt->close();
$conn->close();
?>