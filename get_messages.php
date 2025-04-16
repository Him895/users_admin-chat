<?php
include 'connection17.php'; // Include the database connection file

$user_id = $_GET['user_id'];
if (!$user_id) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT sender, message, sent_at FROM messages  WHERE user_id = ? ORDER BY sent_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = array();
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messages);
?>
