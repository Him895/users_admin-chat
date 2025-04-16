<?php
include 'connection17.php';

$name = $_POST['name'];
$email = $_POST['email'];

// Check if user already exists by email
$check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, return existing user_id
    $row = $result->fetch_assoc();
    echo $row['id'];
} else {
    // New user, insert into database
    $insert_stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $insert_stmt->bind_param("ss", $name, $email);
    if ($insert_stmt->execute()) {
        echo $insert_stmt->insert_id; // return new user_id
    } else {
        echo "error";
    }
}
?>
