<?php
session_start();
if (!$_SESSION["doctor-login"]) {
    echo '<script type="text/javascript">window.location = "index.doctor.php"</script>';
    exit();
} else {
    require_once __DIR__ . "/../../../../vendor/autoload.php";
    require_once __DIR__ . "/../../Models/userState.model.php";

    $user = new User;
    $LoggedInUser = new loggedInUser;

    $doctorID =  $user->getUserID($_SESSION["doctorEmail"]);
    $communicatingPatientID = $_GET["user_id"];

    echo "<script>const doctorID = {$doctorID};</script>";
    echo "<script>const communicatingPatientID = {$communicatingPatientID};</script>";

    $LoggedInUser->updateLoggedInUserState("busy", $doctorID); //updates the doctor's state to 'busy' when consulting a patient

    if (isset($_POST["terminateComm"])) {
        echo $_GET["user_id"];
        $LoggedInUser->updateLoggedInUserState("available", $doctorID); //updates the doctor's state to available
        // $LoggedInUser->removeLoggedInUser($user->getUserID($_SESSION["doctorEmail"])); //remove the doctor's chat
        header("Location: dashboard.php");
        exit();
    }
}

// Preventing return to the previous page.
echo '<script type="text/javascript">

    var triggerReturnButton = false;

    function preventBack(){window.history.forward();
    triggerReturnButton = true;
    };
    setTimeout("preventBack()", 0);
    window.onunload = function(){null;}

</script>
';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat UI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"> </script>
    <style>
        #terminate-conversation {
            display: none;
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
        }

        .terminate-content {
            background-color: rgb(212, 205, 195);
            padding: 20px;
            border-radius: 9px;
            text-align: center;
            width: 300px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .hover-send {
            transition: all 0.5s ease-in-out;
        }

        .hover-send:hover {
            background-color: gray;
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px;
            padding: 5px 10px;
            background-color: #eee;
            border-radius: 20px;
            font-size: 14px;
            font-style: italic;
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen flex flex-col">
    <div id="main-cont" class="w-full max-w-md mx-auto bg-white shadow-lg rounded-lg flex flex-col h-full relative">

        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <button id="return-btn" class="text-gray-600 text-lg">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="flex flex-wrap flex-col">
                <h2 class="top-0 text-lg font-semibold">Doctor Chat</h2>
                <!-- Online Status -->
                <div id="status-indicator" class="top-10 flex items-center space-x-2">
                    <span id="status-circle" class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span id="status-text" class="text-sm font-bold text-black">Offline</span>
                </div>
            </div>
            <img src="../../../../public/images/user.png" alt="User Image" class="w-5 h-5 rounded-full">
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-box"></div>

        <!-- Typing Indicator -->
        <div id="typing" class="typing-indicator hidden p-4 text-gray-500 text-sm italic">typing... </div>

        <!-- Input Section -->
        <div class="p-4 border-t flex items-center">
            <input type="text" id="message-input" class="flex-1 p-2 border rounded-lg focus:outline-none" placeholder="Type a message..." autocomplete="off">
            <button id="send-btn" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover-send">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <!-- Termination Confirmation Modal -->
    <div id="terminate-conversation">
        <form method="post" class="terminate-content">
            <h4 class="font-bold">NOTE:</h4>
            <p class="italic">By clicking OK, all your conversation here will be deleted permanently.</p>
            <div class="flex justify-between mt-4">
                <button id="continue-btn" class="bg-gray-500 text-white px-3 py-2 rounded-lg hover-send">CANCEL</button>
                <button id="terminate-btn" class="bg-red-500 text-white px-5 py-2 rounded-lg hover-send" name="terminateComm">OK</button>
            </div>
        </form>
    </div>




    <script>
        // =============================
        // 🟢 WebSocket Chat Handling
        // =============================

        const conn = new WebSocket('ws://localhost:8080');
        const chatBox = document.getElementById("chat-box");
        const messageInput = document.getElementById("message-input");
        const sendButton = document.getElementById("send-btn");
        const typingMessage = document.getElementsByClassName("hidden")[0];
        const statusCircle = document.getElementById("status-circle");
        const statusText = document.getElementById("status-text");
        const terminateConversation = document.getElementById("terminate-conversation");
        const returnBtn = document.getElementById("return-btn");

        const continueConversation = document.getElementById("continue-btn");
        const terminateBtn = document.getElementById("terminate-btn");
        let typingTimeout;

        const userId = doctorID; // Doctor's ID
        const recipientId = communicatingPatientID; // Patient's ID

        console.log(userId, recipientId);

        // WebSocket Connection Opened
        conn.onopen = () => {
            console.log("Connected to WebSocket");
            statusCircle.classList.replace("bg-red-500", "bg-green-500");
            statusText.innerText = "Online";

            // Notify server of connection
            conn.send(JSON.stringify({
                type: 'connect',
                user_id: userId
            }));
        };

        // WebSocket Connection Closed
        conn.onclose = () => {
            statusCircle.classList.replace("bg-green-500", "bg-red-500");
            statusText.innerText = "Offline";
        };

        // Handle Incoming Messages
        conn.onmessage = (e) => {
            const data = JSON.parse(e.data);

            if (data.type === 'typing') {
                typingMessage.classList.remove("hidden");
            } else if (data.type === 'stop_typing') {
                typingMessage.classList.add("hidden");
            } else if (data.type === 'message') {
                displayMessage(data.message, data.sender_id === userId ? "right" : "left");
            }
        };

        // WebSocket Error Handling
        conn.onerror = (error) => {
            console.error("WebSocket Error:", error);
        };

        // Send a Message via WebSocket
        function sendMessage() {
            const message = messageInput.value.trim();
            if (message) {
                displayMessage(message, "right");

                // Send message via WebSocket
                conn.send(JSON.stringify({
                    type: 'message',
                    message: message,
                    sender_id: userId,
                    recipient_id: recipientId
                }));

                // Also send message to chatAPI.php (Database Backup)
                sendMessageToAPI(message);

                messageInput.value = "";
            }
        }

        // Display a Message in the Chat Box
        function displayMessage(message, side) {
            const newMessage = document.createElement("div");
            newMessage.className = `flex justify-${side === "right" ? "end" : "start"}`;
            newMessage.innerHTML = `<div class='${side === "right" ? "bg-blue-500 text-white" : "bg-gray-300 text-gray-800"} p-3 rounded-lg max-w-xs'>${message}</div>`;
            chatBox.appendChild(newMessage);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Typing Detection
        messageInput.addEventListener("input", () => {
            clearTimeout(typingTimeout);
            conn.send(JSON.stringify({
                type: "typing",
                user_id: userId,
                recipient_id: recipientId
            }));

            typingTimeout = setTimeout(() => {
                conn.send(JSON.stringify({
                    type: "stop_typing",
                    user_id: userId,
                    recipient_id: recipientId
                }));
            }, 1000);
        });

        // Send a Message when the Send Button is Clicked
        sendButton.addEventListener("click", sendMessage);

        // Send a Message on Pressing "Enter"
        messageInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                sendMessage();
            }
        });

        // Detect Typing Start
        messageInput.addEventListener("input", () => {
            conn.send(JSON.stringify({
                type: "typing"
            }));
        });

        // Detect Stop Typing (1 sec of inactivity)
        messageInput.addEventListener("keyup", () => {
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
                conn.send(JSON.stringify({
                    type: "stop_typing"
                }));
            }, 1000);
        });

        // Return Button Logic
        returnBtn.addEventListener("click", () => {
            terminateConversation.style.display = "flex";
        });

        continueConversation.addEventListener("click", () => {
            terminateConversation.style.display = "none";
        });

        terminateBtn.addEventListener("click", () => {
            window.location.href = "dashboard.php";
        });

        if (triggerReturnButton) {
            terminateConversation.style.display = "flex";
            console.log(triggerReturnButton);
        }

        terminateConversation.style.display = "none";

        // =============================
        // 🟢 AJAX Requests to chatAPI.php
        // =============================

        // Fetch Old Messages from chatAPI.php
        function fetchOldMessages() {
            fetch('chatAPI.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'fetch_messages',
                        user_id: userId,
                        recipient_id: recipientId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.messages.forEach(msg => {
                            displayMessage(msg.message, msg.sender_id == userId ? "right" : "left");
                        });
                    }
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        // Store Sent Messages in chatAPI.php
        function sendMessageToAPI(message) {
            fetch('chatAPI.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'send_message',
                        message: message,
                        sender_id: userId,
                        recipient_id: recipientId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Error saving message:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Call fetchOldMessages when the chat page loads
        fetchOldMessages();
    </script>

</body>

</html>