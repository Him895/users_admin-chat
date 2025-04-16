<?php
include 'connection17.php';

$user_id = $_POST['user_id'];
$sender = $_POST['sender']; // 'admin'
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO messages (user_id, sender, message) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $sender, $message);

if ($stmt->execute()) {
    header("Location: chat_with_user.php?user_id=$user_id");
    exit;
} else {
    echo "Error sending message: " . $stmt->error;
}
?>
