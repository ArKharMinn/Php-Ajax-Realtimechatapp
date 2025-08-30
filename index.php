<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Real-Time Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-teal-400 to-blue-400 flex items-center justify-center h-screen">

    <div class="bg-white w-full max-w-md h-[500px] rounded-xl shadow-lg flex flex-col p-6">
        <h2 class="text-center text-2xl font-semibold mb-4 text-gray-800">Real-Time Chat</h2>

        <div id="chat-box" class="flex-1 bg-gray-100 rounded-xl p-4 overflow-y-auto space-y-3">
        </div>

        <div class="flex gap-3 mt-4">
            <input type="text" id="username" placeholder="Your name"
                class="flex-1 rounded-full border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <input type="text" id="message" placeholder="Type a message"
                class="flex-2 rounded-full border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button id="send-btn"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full font-semibold">
                Send
            </button>
        </div>
    </div>

    <script>
        let last_id = 0;
        const chatBox = document.getElementById('chat-box');
        const sendBtn = document.getElementById('send-btn');
        const usernameInput = document.getElementById('username');
        const messageInput = document.getElementById('message');

        function fetchMessages() {
            fetch('fetch_messages.php?last_id=' + last_id)
                .then(res => res.json())
                .then(data => {
                    data.forEach(msg => {
                        const div = document.createElement('div');
                        div.classList.add('p-3', 'rounded-xl', 'max-w-[75%]', 'break-words');
                        if (msg.username === usernameInput.value || usernameInput.value === '') {
                            div.classList.add('bg-blue-200', 'self-end');
                        } else {
                            div.classList.add('bg-green-200', 'self-start');
                        }
                        div.innerHTML = `<strong class="block font-semibold mb-1">${msg.username}</strong>${msg.message}`;
                        chatBox.appendChild(div);
                        last_id = msg.id;
                    });
                    chatBox.scrollTop = chatBox.scrollHeight;
                })
                .catch(err => console.error('Fetch error:', err));
        }

        setInterval(fetchMessages, 1000);
        fetchMessages();

        sendBtn.addEventListener('click', () => {
            const username = usernameInput.value || 'Anonymous';
            const message = messageInput.value;
            if (!message.trim()) return;

            const formData = new FormData();
            formData.append('username', username);
            formData.append('message', message);

            fetch('send_message.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') messageInput.value = '';
                })
                .catch(err => console.error('Send error:', err));
        });

        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') sendBtn.click();
        });
    </script>

</body>

</html>