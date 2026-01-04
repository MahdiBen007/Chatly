window.initChatMessages = function() {
    const chatMessages = document.getElementById('chat-messages');
    if (!chatMessages) return;

    const { currentUserId, otherUserId, otherUserAvatar, roomKey } = window.chatConfig || {};
    const isRoomChat = Boolean(roomKey);
    if (!currentUserId) return;

    const displayedMessageIds = getDisplayedMessageIds();
    document.querySelectorAll('#chat-messages > div').forEach(msg => {
        const msgId = msg.getAttribute('data-message-id');
        if (msgId) displayedMessageIds.add(msgId.toString());
    });

    if (window.Echo) {
        const channelName = isRoomChat ? `room.${roomKey}` : `chat.${currentUserId}`;
        if (window.currentChatChannel) {
            try { window.Echo.leave(window.currentChatChannel); } catch (_) {}
        }
        const channel = window.Echo.private(channelName);
        window.currentChatChannel = channelName;

        channel.listen('.message.sent', (e) => {
            if (!e || !e.id) return;

            if (isRoomChat) {
                if (e.room_key && e.room_key !== roomKey) return;

                if (!displayedMessageIds.has(e.id.toString())) {
                    const messageData = {
                        id: e.id,
                        sender_id: e.sender_id,
                        receiver_id: e.receiver_id,
                        room_key: e.room_key || null,
                        sender_name: e.sender_name || null,
                        sender_avatar: e.sender_avatar || null,
                        message: e.message || '',
                        file_path: e.file_path || null,
                        created_at: e.created_at
                    };

                    const isOwnMessage = e.sender_id == currentUserId;
                    addMessageToChat(messageData, isOwnMessage, true);
                }
                return;
            }

            const isInConversation =
                (e.receiver_id == currentUserId && e.sender_id == otherUserId) ||
                (e.sender_id == currentUserId && e.receiver_id == otherUserId);

            if (isInConversation && !displayedMessageIds.has(e.id.toString())) {
                const messageData = {
                    id: e.id,
                    sender_id: e.sender_id,
                    receiver_id: e.receiver_id,
                    room_key: e.room_key || null,
                    message: e.message,
                    file_path: e.file_path || null,
                    created_at: e.created_at
                };

                const isOwnMessage = e.sender_id == currentUserId;
                addMessageToChat(messageData, isOwnMessage, false);
            }
        });
    }

    function addMessageToChat(messageData, isOwnMessage, isRoomMessage) {
        if (!messageData || !messageData.id) return;
        if (!messageData.message && !messageData.file_path) return;
        if (displayedMessageIds.has(messageData.id.toString())) return;

        const messageDiv = document.createElement('div');
        messageDiv.setAttribute('data-message-id', messageData.id);

        let timeString = '';
        try {
            const dateStr = String(messageData.created_at || '');
            if (dateStr.includes('T')) {
                const createdAt = new Date(dateStr);
                timeString = createdAt.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            } else if (dateStr.includes(' ')) {
                const [datePart, timePart] = dateStr.split(' ');
                const [year, month, day] = datePart.split('-');
                const [hour, minute, second] = timePart.split(':');
                const createdAt = new Date(year, month - 1, day, hour, minute, second);
                timeString = createdAt.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            } else {
                timeString = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        } catch (_) {
            timeString = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        let messageText = String(messageData.message || '').trim();
        if (messageText.startsWith('{') && messageText.endsWith('}')) {
            try {
                const parsed = JSON.parse(messageText);
                if (parsed.message && typeof parsed.message === 'string') {
                    messageText = parsed.message;
                } else if (typeof parsed === 'string') {
                    messageText = parsed;
                }
            } catch (_) {}
        }

        // Handle file display
        let fileHtml = '';
        if (messageData.file_path) {
            const filePath = String(messageData.file_path);
            const fileExtension = filePath.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension);
            const fileName = filePath.split('/').pop();
            
            if (isImage) {
                fileHtml = `<img src="${escapeHtml(filePath)}" alt="Attached image" class="max-w-full h-auto rounded-lg mb-2" style="max-height: 300px;">`;
            } else {
                fileHtml = `<a href="${escapeHtml(filePath)}" download class="flex items-center gap-2 ${isOwnMessage ? 'text-white' : 'text-gray-900 dark:text-gray-100'} hover:underline mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>${escapeHtml(fileName)}</span>
                </a>`;
            }
        }

        if (isRoomMessage && !isOwnMessage) {
            const senderName = String(messageData.sender_name || 'User');
            const fallbackAvatar = window.defaultAvatarUrl || '';
            const senderAvatar = messageData.sender_avatar || fallbackAvatar || otherUserAvatar || '';

            messageDiv.className = 'flex items-end gap-2';
            messageDiv.innerHTML = `
                <img src="${escapeHtml(senderAvatar)}" class="w-8 h-8 rounded-full object-cover" alt="avatar">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">${escapeHtml(senderName)}</p>
                    <div class="bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 rounded-2xl rounded-bl-sm max-w-xs">
                        ${fileHtml}
                        ${messageText ? `<p>${escapeHtml(messageText)}</p>` : ''}
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">${timeString}</span>
                </div>
            `;
        } else if (isOwnMessage || messageData.sender_id == currentUserId) {
            messageDiv.className = 'flex items-end gap-2 justify-end';
            messageDiv.innerHTML = `
                <div>
                    <div class="bg-blue-600 text-white px-4 py-2 rounded-2xl rounded-br-sm max-w-xs">
                        ${fileHtml}
                        ${messageText ? `<p>${escapeHtml(messageText)}</p>` : ''}
                    </div>
                    <span class="text-xs text-gray-400 flex justify-end mr-1">${timeString}</span>
                </div>
            `;
        } else {
            messageDiv.className = 'flex items-end gap-2';
            messageDiv.innerHTML = `
                <img src="${otherUserAvatar}" class="w-8 h-8 rounded-full object-cover" alt="avatar">
                <div>
                    <div class="bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 rounded-2xl rounded-bl-sm max-w-xs">
                        ${fileHtml}
                        ${messageText ? `<p>${escapeHtml(messageText)}</p>` : ''}
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">${timeString}</span>
                </div>
            `;
        }

        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        displayedMessageIds.add(messageData.id.toString());
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function getDisplayedMessageIds() {
        if (!window.displayedMessageIds) {
            window.displayedMessageIds = new Set();
        }
        return window.displayedMessageIds;
    }
};

try { window.initChatMessages(); } catch (_) {}
