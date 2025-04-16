<?php
include 'connection17.php';
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die("❌ User ID not provided in URL.");
}

$user_id = $_GET['user_id'];
$user_stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_res = $user_stmt->get_result()->fetch_assoc();

$msg_stmt = $conn->prepare("SELECT sender, message, sent_at FROM messages WHERE user_id = ?");
$msg_stmt->bind_param("i", $user_id);
$msg_stmt->execute();
$messages = $msg_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat with <?= htmlspecialchars($user_res['name']) ?></title>
    <style>
        body { font-family: Arial; background: #eef2f5; }
        .container {
            width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
        }

        .msg {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            max-width: 80%;
        }

        .user-msg {
            background: #dcf8c6;
            align-self: flex-end;
        }

        .admin-msg {
            background: #e4e6eb;
            align-self: flex-start;
        }

        .chat-box {
            display: flex;
            flex-direction: column;
        }

        .time {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }

        .reply-box {
            margin-top: 20px;
        }

        .reply-box textarea {
            width: 100%;
            height: 60px;
            padding: 10px;
        }

        .reply-box button {
            padding: 10px 20px;
            background: #4a76a8;
            border: none;
            color: #fff;
            margin-top: 5px;
            cursor: pointer;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Chat with <?= htmlspecialchars($user_res['name']) ?></h2>

    <div class="chat-box">
    <p style="text-align:center;">
    <a href="dashboard2.php" style="text-decoration:none; font-weight:bold; color:#4a76a8;">⬅️ Back to Dashboard</a>
</p>

        <?php while($row = $messages->fetch_assoc()): ?>
            <div class="msg <?= $row['sender'] === 'user' ? 'user-msg' : 'admin-msg' ?>">
                <?= htmlspecialchars($row['message']) ?>
                <div class="time"><?= $row['sent_at'] ?></div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="reply-box">
        <form method="post" action="admin_send_message.php">
            <textarea name="message" placeholder="Type your reply..." required></textarea>
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <input type="hidden" name="sender" value="admin">
            <button type="submit">Send Reply</button>
        </form>
    </div>
</div>
</body>
</html>
