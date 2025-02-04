const userId = 123; // Replace with the actual logged-in user ID
const recipientId = 456; // Replace with the doctor's or patient's ID

const conn = new WebSocket('ws://localhost:8080');

conn.onopen = () => {
    console.log("Connected to WebSocket");

    // Send user ID upon connection
    conn.send(JSON.stringify({
        type: 'connect',
        user_id: userId
    }));
};

conn.onmessage = (e) => {
    const data = JSON.parse(e.data);
    if (data.type === 'message') {
        displayMessage(data.message, data.sender_id === userId ? "right" : "left");
    }
};

function sendMessage() {
    const message = messageInput.value.trim();
    if (message) {
        displayMessage(message, "right");

        conn.send(JSON.stringify({
            type: 'message',
            message: message,
            sender_id: userId,
            recipient_id: recipientId
        }));

        messageInput.value = "";
    }
}
