<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Chat Support</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .chat-container {
            width: 400px;
            height: 600px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }
        
        .chat-header {
            background: rgba(74, 118, 168, 0.8);
            color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
        }
        
        .chat-box {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            scrollbar-width: thin;
        }
        
        .message {
            max-width: 70%;
            padding: 12px 18px;
            margin: 6px 0;
            border-radius: 20px;
            font-size: 14px;
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .user {
            align-self: flex-end;
            background: #a0e7a0;
            border-bottom-right-radius: 5px;
        }
        
        .admin {
            align-self: flex-start;
            background: #f3f3f3;
            border-bottom-left-radius: 5px;
        }
        
        .chat-input {
            display: flex;
            padding: 12px;
            background: rgba(255, 255, 255, 0.2);
            border-top: 1px solid rgba(255,255,255,0.3);
        }
        
        .chat-input input {
            flex: 1;
            padding: 10px 15px;
            border: none;
            border-radius: 30px;
            font-size: 14px;
            background: #fff;
            outline: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .chat-input button {
            margin-left: 10px;
            background: #4a76a8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        
        .chat-input button:hover {
            background: #355c87;
        }

        body.dark-mode {
    background: #1e1e1e;
}

body.dark-mode .chat-container {
    background: rgba(40, 40, 40, 0.9);
}

body.dark-mode .chat-header {
    background: #333;
}

body.dark-mode .chat-input input {
    background: #333;
    color: #fff;
}

body.dark-mode .chat-input button {
    background: #555;
}

body.dark-mode .message.admin {
    background: #555;
    color: #fff;
}

        </style>
        
    </style>
</head>
<body>
    <form id="start-chat-form">
        <input type="text" id="name" placeholder="Your name" required>
        <input type="email" id="email" placeholder="Your email" required>
        <button type="submit">Start Chat</button>
    </form>
    
    <div class="chat-container" id="chat-section" style="display:none;">
        <p style="text-align:center;">
            <a href="dashboard2.php" style="text-decoration:none; font-weight:bold; color:#4a76a8;">⬅️ Back to Dashboard</a>
        </p>
        <div class="chat-header">Support Chat</div>
       
        
        
        <div class="chat-box" id="chat-box">
            <div id="typing-status" style="font-size:12px; color: #555; margin: 5px 10px; display: none;">User is typing...</div>

        </div>
        <div class="chat-input">
            <input type="text" id="msg" placeholder="Type a message...">
            <button onclick="sendMessage()">Send</button>
            <button onclick="toggleDarkMode()" style="position:absolute;top:20px;right:20px;">🌓</button>

        </div>
    </div>
    

    <script>
        let user_id = null;
        
        document.getElementById("start-chat-form").addEventListener("submit", function (e) {
            e.preventDefault();
        
            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;
        
            fetch("start_chat.php", {
                method: "POST",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}`
            })
            .then(res => res.text())
            .then(id => {
    user_id = id.trim();
    
    if (user_id === "error") {
        alert("⚠️ Something went wrong. Please try again.");
        return;
    }

    console.log("User ID:", user_id);
    document.getElementById("start-chat-form").style.display = "none";
    document.getElementById("chat-section").style.display = "flex";
    loadMessages();
});

        });
        let typingTimeout;
const typingStatus = document.getElementById("typing-status");

document.getElementById("msg").addEventListener("input", () => {
    showTypingStatus();
});
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
}


function showTypingStatus() {
    typingStatus.style.display = "block";
    
    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        typingStatus.style.display = "none";
    }, 2000);
}

        
let lastMessageCount = 0;

function loadMessages() {
    if (!user_id) return;

    fetch("get_messages.php?user_id=" + user_id)
        .then(res => res.json())
        .then(data => {
            let chatBox = document.getElementById("chat-box");
            
            // Play sound if new message
            if (data.length > lastMessageCount) {
                document.getElementById("newMessageSound").play();
            }
            lastMessageCount = data.length;

            chatBox.innerHTML = "";

            data.forEach(msg => {
                let div = document.createElement("div");
                div.classList.add("message", msg.sender === 'user' ? 'user' : 'admin');
                div.innerHTML = `
                    <div>${msg.message}</div>
                    <small style="font-size:10px;color:#777;margin-top:4px;">${msg.sent_at}</small>
                `;
                chatBox.appendChild(div);
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        });
}

        
        function sendMessage() {
            let message = document.getElementById("msg").value;
            if (message.trim() === "") return;
        
            fetch("send_messages.php", {
                method: "POST",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `user_id=${user_id}&sender=user&message=${encodeURIComponent(message)}`
            }).then(() => {
                document.getElementById("msg").value = "";
                loadMessages();
            });
        }
        
        setInterval(loadMessages, 2000);
        </script>
        <audio id="newMessageSound" src="notification.mp3" preload="auto"></audio>

</body>
</html>
        