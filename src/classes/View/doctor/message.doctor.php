<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat UI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .hover-send {
            transition: all 0.5s ease-in-out;
        }

        .hover-send:hover {
            background-color: gray;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen flex flex-col">
    <div class="w-full max-w-md mx-auto bg-white shadow-lg rounded-lg flex flex-col h-full relative">

        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <button class="text-gray-600 text-lg">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="flex flex-wrap flex-col">
                <h2 class="top-0 text-lg font-semibold">Dcotor Chat</h2>
                <!-- Online Status -->
                <div id="status-indicator" class="top-10 flex items-center space-x-2">
                    <span id="status-circle" class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span id="status-text" class="text-sm font-bold text-black">Offline</span>
                </div>
            </div>
            <img src="https://via.placeholder.com/40" alt="User Image" class="w-10 h-10 rounded-full">
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-box"></div>

        <!-- Typing Indicator -->
        <div id="typing-indicator" class="hidden p-4 text-gray-500 text-sm italic">Typing...</div>

        <!-- Input Section -->
        <div class="p-4 border-t flex items-center">
            <input type="text" id="message-input" class="flex-1 p-2 border rounded-lg focus:outline-none" placeholder="Type a message..." autocomplete="off">
            <button id="send-btn" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover-send">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script>
        const conn = new WebSocket('ws://localhost:8080');
        const chatBox = document.getElementById("chat-box");
        const messageInput = document.getElementById("message-input");
        const sendButton = document.getElementById("send-btn");
        const typingIndicator = document.getElementById("typing-indicator");
        const statusCircle = document.getElementById("status-circle");
        const statusText = document.getElementById("status-text");
        let typingTimeout;

        conn.onopen = () => {
            console.log("Connected to WebSocket");
            statusCircle.classList.replace("bg-red-500", "bg-green-500");
            statusText.innerText = "Online";
        };

        conn.onclose = () => {
            statusCircle.classList.replace("bg-green-500", "bg-red-500");
            statusText.innerText = "Offline";
        };

        conn.onmessage = (e) => {
            if (e.data === "typing...") {
                typingIndicator.classList.remove("hidden");
            } else if (e.data === "stop typing") {
                typingIndicator.classList.add("hidden");
            } else {
                typingIndicator.classList.add("hidden");
                displayMessage(e.data, "left");
            }
        };

        function sendMessage() {
            const message = messageInput.value.trim();
            if (message) {
                displayMessage(message, "right");
                conn.send(message);
                messageInput.value = "";
                conn.send("stop typing");
            }
        }

        function displayMessage(message, side) {
            const newMessage = document.createElement("div");
            newMessage.className = `flex justify-${side === "right" ? "end" : "start"}`;
            newMessage.innerHTML = `<div class='${side === "right" ? "bg-blue-500 text-white" : "bg-gray-300 text-gray-800"} p-3 rounded-lg max-w-xs'>${message}</div>`;
            chatBox.appendChild(newMessage);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        messageInput.addEventListener("input", () => {
            clearTimeout(typingTimeout);
            conn.send("typing...");
            typingTimeout = setTimeout(() => conn.send("stop typing"), 2000);
        });

        sendButton.addEventListener("click", sendMessage);

        messageInput.addEventListener("keydown", (e) => {
            if (window.innerWidth > 768 && e.key === "Enter") {
                e.preventDefault();
                sendMessage();
            }
        });
    </script>
</body>

</html>