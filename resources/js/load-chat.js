// Function to load chat for a user
    function loadChatForUser(userId) {
        const chatAreaContainer = document.getElementById('chat-area-container');
        const userListSidebar = document.getElementById('user-list-sidebar');

        // On mobile, hide user list and show chat area
        if (window.innerWidth < 768) {
            userListSidebar.classList.add('hidden');
            chatAreaContainer.classList.remove('hidden');
        }

        // Hide placeholder immediately to avoid flash
        const placeholder = document.getElementById('chat-placeholder');
        if (placeholder) {
            placeholder.classList.add('hidden');
            placeholder.classList.remove('flex');
        }

        fetch(`/chat/${userId}`)
            .then(response => response.text())
            .then(html => {
                // Placeholder already hidden above using Tailwind classes
                
                // Create a wrapper div for the chat content
                let chatContent = chatAreaContainer.querySelector('#chat-content-wrapper');
                if (!chatContent) {
                    chatContent = document.createElement('div');
                    chatContent.id = 'chat-content-wrapper';
                    chatContent.className = 'flex-1 flex flex-col hidden';
                    chatAreaContainer.appendChild(chatContent);
                }
                
                // Load chat area
                chatContent.innerHTML = html;
                
                // Ensure chatConfig is set because injected <script> tags won't execute when using innerHTML
                const avatar =
                    chatContent.querySelector('.border-b img')?.src ||
                    chatContent.querySelector('#chat-messages img')?.src ||
                    '';
                window.chatConfig = {
                    currentUserId: window.currentUserId,
                    otherUserId: userId,
                    otherUserAvatar: avatar,
                };
                chatAreaContainer.classList.remove('hidden');

                // Add a back button for mobile
                const chatHeader = chatContent.querySelector('.border-b');
                if (chatHeader) {
                    // Remove existing back button if any
                    const existingBackButton = chatHeader.querySelector('.back-button-mobile');
                    if (existingBackButton) {
                        existingBackButton.remove();
                    }
                    
                    const backButton = document.createElement('button');
                    backButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>`;
                    backButton.className = 'back-button-mobile md:hidden mr-4';
                    backButton.onclick = () => {
                        userListSidebar.classList.remove('hidden');
                        chatAreaContainer.classList.add('hidden');
                        // Hide chat content and show placeholder again when going back
                        if (chatContent) {
                            chatContent.classList.add('hidden');
                        }
                        const ph2 = document.getElementById('chat-placeholder');
                        if (ph2) {
                            ph2.classList.remove('hidden');
                            ph2.classList.add('flex');
                        }
                    };
                    chatHeader.prepend(backButton);
                }
                
                // Show chat content
                if (chatContent) {
                    chatContent.classList.remove('hidden');
                }

                // Hide unread badge for this user in sidebar immediately
                try {
                    const userButton = document.querySelector(`.user-button[data-user-id="${userId}"]`);
                    if (userButton) {
                        const badge = userButton.querySelector('.bg-blue-500.text-white.text-xs.font-semibold');
                        if (badge) {
                            badge.remove();
                        }
                    }
                } catch (_) {}
                
                // Scroll to bottom after loading messages
                setTimeout(() => {
                    const chatMessages = document.getElementById('chat-messages');
                    if (chatMessages) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                }, 100);

                // Initialize real-time receiving on the injected chat (after chatConfig is set)
                try { window.initChatMessages(); } catch (_) {}

                // Initialize chat interactions to prevent page reload on send
                initChatInteractions();
                
                // Initialize file upload functionality
                try { window.initFileUpload(); } catch (_) {}
            })
            .catch(error => {
                console.error('Error loading chat:', error);
                // Show placeholder again on error
                const phErr = document.getElementById('chat-placeholder');
                if (phErr) {
                    phErr.classList.remove('hidden');
                    phErr.classList.add('flex');
                }
                const chatContent = chatAreaContainer.querySelector('#chat-content-wrapper');
                if (chatContent) {
                    chatContent.classList.add('hidden');
                }
            });
    }

    // Initialize chat area interactions after content is injected
    function initChatInteractions() {
        const chatMessages = document.getElementById('chat-messages');
        const messageForm = document.getElementById('message-form');
        if (!chatMessages || !messageForm) return;

        const displayedMessageIds = getDisplayedMessageIds();
        document.querySelectorAll('#chat-messages > div').forEach(msg => {
            const msgId = msg.getAttribute('data-message-id');
            if (msgId) displayedMessageIds.add(msgId.toString());
        });

        const messageInput = messageForm.querySelector('input[name="message"]');
        const submitButton = messageForm.querySelector('button[type="submit"]');

        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const text = (messageInput?.value || '').trim();
            if (!text) return;

            if (submitButton) submitButton.disabled = true;
            const formData = new FormData(messageForm);

            fetch(messageForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data?.success && data?.message) {
                    const msg = data.message;
                    const idStr = String(msg.id);
                    if (!displayedMessageIds.has(idStr)) {
                        appendOwnMessage(idStr, String(msg.message || ''), String(msg.created_at || ''), msg.file_path || null);
                        displayedMessageIds.add(idStr);
                    }
                    if (messageInput) messageInput.value = '';
                    // Clear file input and preview
                    const fileInput = document.getElementById('chat-file-input');
                    if (fileInput) fileInput.value = '';
                    const clearFileBtn = document.getElementById('clear-file-btn');
                    if (clearFileBtn) clearFileBtn.style.display = 'none';
                    const fileNameDisplay = document.getElementById('file-name-display');
                    if (fileNameDisplay) fileNameDisplay.textContent = '';
                    const imagePreviewContainer = document.getElementById('image-preview-container');
                    if (imagePreviewContainer) imagePreviewContainer.classList.add('hidden');
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            })
            .catch(err => {
                console.error('Error sending message:', err);
            })
            .finally(() => {
                if (submitButton) submitButton.disabled = false;
            });
        });

        function appendOwnMessage(id, messageText, createdAt, filePath) {
            const messageDiv = document.createElement('div');
            messageDiv.setAttribute('data-message-id', id);

            const timeString = formatTime(createdAt);
            const text = escapeHtml(String(messageText || '').trim());
            
            let fileHtml = '';
            if (filePath) {
                const fileExtension = filePath.split('.').pop().toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension);
                const fileName = filePath.split('/').pop();
                
                if (isImage) {
                    fileHtml = `<img src="${escapeHtml(filePath)}" alt="Attached image" class="max-w-full h-auto rounded-lg mb-2" style="max-height: 300px;">`;
                } else {
                    fileHtml = `<a href="${escapeHtml(filePath)}" download class="flex items-center gap-2 text-white hover:underline mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>${escapeHtml(fileName)}</span>
                    </a>`;
                }
            }

            messageDiv.className = 'flex items-end gap-2 justify-end';
            messageDiv.innerHTML = `
                <div>
                    <div class="bg-blue-600 text-white px-4 py-2 rounded-2xl rounded-br-sm max-w-xs">
                        ${fileHtml}
                        ${text ? `<p>${text}</p>` : ''}
                    </div>
                    <span class="text-xs text-gray-400 flex justify-end mr-1">${timeString}</span>
                </div>
            `;
            chatMessages.appendChild(messageDiv);
        }

        function formatTime(dateStr) {
            try {
                if (dateStr.includes('T')) {
                    const d = new Date(dateStr);
                    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                } else if (dateStr.includes(' ')) {
                    const [datePart, timePart] = dateStr.split(' ');
                    const [y, m, d] = datePart.split('-');
                    const [hh, mm, ss] = timePart.split(':');
                    const dt = new Date(y, m - 1, d, hh, mm, ss);
                    return dt.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                }
            } catch (_) {}
            return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
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
    }

    document.addEventListener('DOMContentLoaded', function () {
        const userButtons = document.querySelectorAll('.user-button');
        const userButtonsMobile = document.querySelectorAll('.user-button-mobile');
        const chatAreaContainer = document.getElementById('chat-area-container');
        const userListSidebar = document.getElementById('user-list-sidebar');

        // Listen for user status updates via WebSocket
        if (window.Echo) {
            window.Echo.channel('user-status')
                .listen('.user.status.updated', (e) => {
                    const userId = e.user_id;
                    const isOnline = e.is_online;
                    
                    // Update status indicators in sidebar
                    const userButtons = document.querySelectorAll(`[data-user-id="${userId}"]`);
                    userButtons.forEach(button => {
                        // Update status dot
                        const statusDot = button.querySelector('.absolute.bottom-0.right-0');
                        if (statusDot) {
                            statusDot.className = `absolute bottom-0 right-0 w-3 h-3 ${isOnline ? 'bg-green-400' : 'bg-gray-400'} border-2 border-[#0a0f24] rounded-full`;
                        }
                        
                        // Update status text
                        const statusText = button.querySelector('.text-xs.truncate');
                        if (statusText) {
                            statusText.className = `text-xs ${isOnline ? 'text-green-400' : 'text-gray-400'} truncate`;
                            statusText.textContent = isOnline ? 'Online' : 'Offline';
                        }
                    });
                    
                    // Update status in chat area if this user is currently selected
                    const chatHeader = document.querySelector('#chat-content-wrapper .border-b');
                    if (chatHeader && chatHeader.dataset.userId === userId) {
                        const statusInChat = document.getElementById(`user-status-${userId}`);
                        if (statusInChat) {
                            statusInChat.className = `text-sm ${isOnline ? 'text-green-400' : 'text-gray-400'}`;
                            statusInChat.textContent = isOnline ? 'Online' : 'Offline';
                        }
                    }
                });
        }

        // Handle clicks on desktop user buttons (in messages list)
        userButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.userId;
                loadChatForUser(userId);
            });
        });

        // Handle clicks on mobile user buttons (in top friends list)
        userButtonsMobile.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.userId;
                loadChatForUser(userId);
            });
        });

        // Listen for incoming messages to update unread badges when chat is not open
        if (window.Echo && window.currentUserId) {
            try {
                const channelName = `chat.${window.currentUserId}`;
                window.Echo.private(channelName)
                    .listen('.message.sent', (e) => {
                        if (!e || !e.sender_id || !e.receiver_id) return;
                        if (String(e.receiver_id) !== String(window.currentUserId)) return;

                        const activeChat = document.getElementById('chat-messages');
                        const activeUserId = activeChat?.dataset?.userId;

                        // Only update badges if chat is not open with the sender
                        if (!activeUserId || String(activeUserId) !== String(e.sender_id)) {
                            updateUnreadBadge(e.sender_id);
                        }
                    });
            } catch (_) {}
        }

        function updateUnreadBadge(userId) {
            const selectors = [`.user-button[data-user-id="${userId}"]`, `.user-button-mobile[data-user-id="${userId}"]`];
            selectors.forEach(sel => {
                const container = document.querySelector(sel);
                if (!container) return;
                const header = container.querySelector('.flex.items-center.justify-between');
                // Desktop row has this header; mobile might not, so fallback to container
                const target = header || container;
                let badge = target.querySelector('.bg-blue-500.text-white.text-xs.font-semibold');
                if (badge) {
                    const current = parseInt(badge.textContent.trim(), 10);
                    const next = isNaN(current) ? 1 : current + 1;
                    badge.textContent = String(next);
                } else {
                    const span = document.createElement('span');
                    span.className = 'bg-blue-500 text-white text-xs font-semibold px-1.5 rounded-full h-5 flex items-center justify-center';
                    span.textContent = '1';
                    // For desktop, append to header's right side; for mobile, append next to name
                    if (header) {
                        header.appendChild(span);
                    } else {
                        container.appendChild(span);
                    }
                }
            });
        }
    });
