<x-app-layout>
    <div 
        x-data="{ selectedUser: null }"
        class="flex w-full h-screen text-[var(--text)] 
               bg-[radial-gradient(circle_at_top_left,#f9fafb,#f3f4f6,#e5e7eb)] 
               dark:bg-[radial-gradient(circle_at_top_left,var(--bg-1),var(--bg-2),var(--bg-3))]"
    >
        <!-- Sidebar -->
        <div 
            id="user-list-sidebar" 
            class="w-full md:w-80 flex-shrink-0 
                   border-r border-gray-300 
                   dark:border-gray-700 
                   bg-white 
                   dark:bg-transparent"
        >
            <x-sidebar :users="$users" />
        </div>

        <!-- Chat Area -->
        <div 
            id="chat-area-container" 
            class="flex-1 hidden md:flex flex-col w-full 
                   bg-gray-50 
                   dark:bg-transparent"
        >
            <!-- Placeholder عند عدم اختيار مستخدم -->
            <div 
                id="chat-placeholder" 
                class="flex-1 flex flex-col items-center justify-center text-center px-4"
            >
                <div
                    class="inline-flex items-center justify-center w-20 h-20 
                           rounded-full bg-blue-100 mb-4 shadow-lg 
                           dark:bg-primary/20"
                >
                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        width="28" height="28" 
                        viewBox="0 0 24 24" 
                        fill="none"
                        stroke="currentColor" 
                        stroke-width="2" 
                        stroke-linecap="round" 
                        stroke-linejoin="round"
                        class="h-10 w-10 text-blue-500 dark:text-primary"
                    >
                        <path
                            d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"
                        ></path>
                        <path d="m21.854 2.147-10.94 10.939"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold mb-1 text-gray-800 dark:text-white">
                    Select a conversation
                </h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">
                    Choose a contact from the sidebar to start chatting
                </p>
            </div>
        </div>
    </div>
    <script>
        window.currentUserId = @json(auth()->id());
    </script>
</x-app-layout>
