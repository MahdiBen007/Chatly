<div
    class="w-full h-screen flex flex-col 
           bg-[radial-gradient(circle_at_top_left,#f9fafb,#f3f4f6,#e5e7eb)] 
           dark:bg-[radial-gradient(circle_at_top_left,var(--bg-1),var(--bg-2),var(--bg-3))] 
           text-gray-800 dark:text-[var(--text)]">

    <!-- Header -->
    <div class="flex items-center justify-between border-b border-gray-300 dark:border-gray-700 bg-white dark:bg-transparent px-4 py-3" data-user-id="{{ $user->id }}">
        <div class="flex items-center gap-3">
            @if ($user->profile_image)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->profile_image) }}"
                    class="w-10 h-10 rounded-full object-cover" alt="avatar">
            @else
                <img src="{{ asset('images/user.png') }}" class="w-10 h-10 rounded-full object-cover" alt="default avatar">
            @endif

            <div>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                <p id="user-status-{{ $user->id }}"
                    class="text-sm {{ $user->isOnline() ? 'text-green-500' : 'text-gray-500 dark:text-gray-400' }}">
                    {{ $user->isOnline() ? 'Online' : 'Offline' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300 font-bold">
            <button class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 11.19 18a19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 3.33 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72A12.84 12.84 0 0 0 9.8 6.53a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                </svg>
            </button>
            <button class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <rect x="2" y="6" width="14" height="12" rx="2" />
                    <path d="M16 13l5 3V8l-5 3z" />
                </svg>
            </button>
            <button class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4M12 8h.01" />
                </svg>
            </button>
            <button class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <circle cx="12" cy="5" r="1" />
                    <circle cx="12" cy="12" r="1" />
                    <circle cx="12" cy="19" r="1" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Chat messages -->
    <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 no-scrollbar bg-gray-50 dark:bg-transparent" data-user-id="{{ $user->id }}">
        @foreach ($messages as $message)
            @if ($message->sender_id === auth()->id())
                <div class="flex items-end gap-2 justify-end" data-message-id="{{ $message->id }}">
                    <div>
                        <div class="bg-blue-600 text-white px-4 py-2 rounded-2xl rounded-br-sm shadow-sm max-w-xs">
                            @if ($message->file_path)
                                @php
                                    $fileUrl = \Illuminate\Support\Facades\Storage::url($message->file_path);
                                    $fileExtension = strtolower(pathinfo($message->file_path, PATHINFO_EXTENSION));
                                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                @if ($isImage)
                                    <img src="{{ $fileUrl }}" alt="Attached image" class="max-w-full h-auto rounded-lg mb-2" style="max-height: 300px;">
                                @else
                                    <a href="{{ $fileUrl }}" download class="flex items-center gap-2 text-white hover:underline mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>{{ basename($message->file_path) }}</span>
                                    </a>
                                @endif
                            @endif
                            @if ($message->message)
                                <p>{{ $message->message }}</p>
                            @endif
                        </div>
                        <span
                            class="text-xs text-gray-400 flex justify-end mr-1">{{ $message->created_at->format('h:i') }}</span>
                    </div>
                </div>
            @else
                <div class="flex items-end gap-2" data-message-id="{{ $message->id }}">
                    @if ($message->sender && $message->sender->profile_image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($message->sender->profile_image) }}"
                            class="w-8 h-8 rounded-full object-cover" alt="{{ $message->sender->name ?? 'avatar' }}">
                    @else
                        <img src="{{ asset('images/user.png') }}" class="w-8 h-8 rounded-full object-cover" alt="default avatar">
                    @endif
                    <div>
                        <div class="bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 rounded-2xl rounded-bl-sm shadow-sm max-w-xs">
                            @if ($message->file_path)
                                @php
                                    $fileUrl = \Illuminate\Support\Facades\Storage::url($message->file_path);
                                    $fileExtension = strtolower(pathinfo($message->file_path, PATHINFO_EXTENSION));
                                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                @if ($isImage)
                                    <img src="{{ $fileUrl }}" alt="Attached image" class="max-w-full h-auto rounded-lg mb-2" style="max-height: 300px;">
                                @else
                                    <a href="{{ $fileUrl }}" download class="flex items-center gap-2 text-gray-900 dark:text-gray-100 hover:underline mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>{{ basename($message->file_path) }}</span>
                                    </a>
                                @endif
                            @endif
                            @if ($message->message)
                                <p>{{ $message->message }}</p>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">{{ $message->created_at->format('h:i') }}</span>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Input -->
<div class="border-t border-gray-300 dark:border-gray-700 bg-white dark:bg-transparent p-4">
    <!-- Image preview container -->
    <div id="image-preview-container" class="mb-2 hidden">
        <div class="relative inline-block">
            <img id="image-preview" src="" alt="Preview" class="max-w-xs max-h-48 rounded-lg object-cover">
            <button type="button" id="remove-preview-btn" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Error message container -->
    <div id="file-error-message" class="mb-2 text-red-500 text-sm hidden"></div>

    <form id="message-form" action="{{ route('message.store', $user) }}" method="POST" 
          class="flex items-center gap-2" enctype="multipart/form-data">
        @csrf

        <!-- Hidden file input -->
        <input type="file" name="file" id="chat-file-input" class="sr-only"
               accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt">

        <!-- File selection button -->
        <button type="button" id="select-file-btn" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M21.44 11.05l-8.49 8.49a5 5 0 0 1-7.07 0 5 5 0 0 1 0-7.07l8.49-8.49a3.5 3.5 0 0 1 4.95 4.95l-8.49 8.49a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l7.78-7.78" />
            </svg>
        </button>

        <!-- Text input for message -->
        <input type="text" name="message" id="message-input" placeholder="Type a message..."
               class="flex-1 rounded-2xl bg-gray-100 dark:bg-gray-800 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100">

        <!-- Display file name -->
        <span id="file-name-display" class="text-gray-600 dark:text-gray-300 truncate max-w-xs"></span>

        <!-- Clear file button -->
        <button type="button" id="clear-file-btn" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50" style="display:none;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>

        <!-- Send button -->
        <button type="submit" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <line x1="22" y1="2" x2="11" y2="13" />
                <polygon points="22 2 15 22 11 13 2 9 22 2" />
            </svg>
        </button>
    </form>
</div>

</div>

<script>
    window.chatConfig = {
        currentUserId: @json(auth()->id()),
        otherUserId: @json($user->id),
        otherUserAvatar: @json($user->profile_image ? \Illuminate\Support\Facades\Storage::url($user->profile_image) : asset('images/user.png'))
    };
</script>
