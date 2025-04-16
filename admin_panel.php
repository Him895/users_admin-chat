<?php
include 'connection17.php';

$result = $conn->query("SELECT id, name, email FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Live Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 15px 25px rgba(0,0,0,0.2);
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .user-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .user-card {
            background: #f9f9f9;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .user-card h3 {
            margin: 0 0 5px;
            color: #333;
        }

        .user-card p {
            margin: 0;
            color: #555;
        }

        .chat-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #667eea;
            color: #fff;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .chat-btn:hover {
            background: #556cd6;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>👨‍💻 Admin Panel - All Users</h1>
        <p style="text-align:center;">
    <a href="dashboard2.php" style="text-decoration:none; font-weight:bold; color:#4a76a8;">⬅️ Back to Dashboard</a>
</p>
        <div class="user-list">
        
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="user-card">
                    <h3>👤 <?= htmlspecialchars($row['name']) ?></h3>
                    <p>📧 <?= htmlspecialchars($row['email']) ?></p>
                    <a class="chat-btn" href="chat_with_user.php?user_id=<?= $row['id'] ?>">💬 Chat</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>
