<?php
include 'connection17.php'; // Include the database connection file

$user_id = $_POST['user_id'];
$message = $_POST['message'];
$sender = $_POST['sender'];

// Debug log
if (!$user_id || !$message || !$sender) {
    echo "Missing data: ";
    echo "user_id = $user_id, message = $message, sender = $sender";
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (user_id, sender, message) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $sender, $message);

if ($stmt->execute()) {
    echo "✅ Message sent!";
} else {
    echo "❌ Error: " . $stmt->error;
}
?>
