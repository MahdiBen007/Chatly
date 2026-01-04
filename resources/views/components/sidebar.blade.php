@props(['users', 'globalRoom' => null])

<div id="user-list-sidebar"
  class="w-full md:w-80 h-screen flex flex-col border-r border-gray-300 bg-[radial-gradient(circle_at_top_left,#f9fafb,#f3f4f6,#e5e7eb)] text-gray-900 md:flex dark:border-[var(--border)] dark:bg-[radial-gradient(circle_at_top_left,var(--bg-1),var(--bg-2),var(--bg-3))] dark:text-[var(--text)]">
  <!-- Mobile Header -->
  <div class="md:hidden p-4 border-b border-gray-300 dark:border-gray-700 bg-white dark:bg-transparent">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Chatly</h1>
      <a href="" class="flex items-center gap-2.5 transition-transform duration-300 hover:scale-105">
        <div
          class="bg-gradient-to-br from-[#007cf0] to-[#00dfd8] w-8 h-8 md:w-9 md:h-9 rounded-full flex justify-center items-center shadow-[0_0_10px_rgba(0,223,216,0.7)] animate-glow-primary">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
          </svg>
        </div>
      </a>
    </div>

    <div class="relative">
      <svg xmlns="http://www.w3.org/2000/svg"
        class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <circle cx="11" cy="11" r="8" />
        <path d="m21 21-4.3-4.3" />
      </svg>
      <input type="text" placeholder="Search contacts or messages..."
        class="w-full h-10 pl-10 pr-3 rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-900 dark:text-gray-100">
    </div>

    <!-- Friends list -->
    <div class="flex items-start gap-4 mt-4 overflow-x-auto pb-2 no-scrollbar">

      <!-- إضافة القصة -->
      <div class="flex flex-col items-center gap-1 flex-shrink-0">
        <div class="w-14 h-14 rounded-full border-2 border-blue-500 flex items-center justify-center bg-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
        </div>
        <span class="text-xs text-gray-600 dark:text-gray-400">Add Story</span>
      </div>

      <!-- المستخدم الحالي -->
      @if(Auth::check())
        <div class="flex flex-col items-center gap-1 flex-shrink-0">
          <div class="relative">
            <div
              class="w-14 h-14 rounded-full border-2 border-gray-300 overflow-hidden shadow-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
              @if (Auth::user()->profile_image)
                <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->profile_image) }}"
                  alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
              @else
                <img src="{{ asset('images/user.png') }}" class="w-10 h-10 rounded-full object-cover"
                  alt="default avatar">
              @endif
            </div>
            <span
              class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white dark:border-[#0a0f24] rounded-full"></span>
          </div>
          <span class="text-xs text-gray-600 dark:text-gray-400">{{ Auth::user()->name }}</span>
        </div>
      @endif

      <!-- المستخدمون الآخرون -->
      @foreach($users as $user)
        <button
          class="flex flex-col items-center gap-1 flex-shrink-0 user-button-mobile cursor-pointer transition-transform hover:scale-105 active:scale-95"
          data-user-id="{{ $user->id }}">
          <div class="relative">
            <div
              class="w-14 h-14 rounded-full border-2 border-gray-300 overflow-hidden shadow-md flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-lg font-semibold uppercase">
              @if($user->profile_image)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->profile_image) }}"
                  class="w-full h-full object-cover" alt="{{ $user->name }}">
              @else
                {{ strtoupper(substr($user->name, 0, 2)) }}
              @endif
            </div>
            <span
              class="absolute bottom-0 right-0 w-3 h-3 {{ $user->isOnline() ? 'bg-green-400' : 'bg-gray-400' }} border-2 border-white dark:border-[#0a0f24] rounded-full"></span>
          </div>
          <span class="text-xs text-gray-600 dark:text-gray-400">{{ $user->name }}</span>
        </button>
      @endforeach

    </div>
  </div>

  <!-- Desktop Header -->
  <div class="hidden md:block">
    <div class="p-4 border-b border-gray-300 dark:border-gray-700 bg-white dark:bg-transparent flex items-center gap-3">
      <div class="relative">
        <div class="w-10 h-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-md bg-gray-100">
          @if (Auth::user()->profile_image)
            <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->profile_image) }}"
              alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
          @else
            <img src="{{ asset('images/user.png') }}" class="w-10 h-10 rounded-full object-cover" alt="default avatar">
          @endif
        </div>
        <span
          class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white dark:border-[#0a0f24] rounded-full"></span>
      </div>

      <div>
        <h3 class="font-semibold text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</h3>
        <p
          class="text-xs {{ Auth::user()->isOnline() ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
          {{ Auth::user()->isOnline() ? 'Online' : 'Offline' }}</p>
      </div>
    </div>

    <!-- Search -->
    <div class="p-4 bg-white dark:bg-transparent">
      <div class="relative">
        <svg xmlns="http://www.w3.org/2000/svg"
          class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.3-4.3" />
        </svg>
        <input type="text" placeholder="Search contacts or messages..."
          class="w-full h-10 pl-10 pr-3 rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-900 dark:text-gray-100">
      </div>
    </div>
  </div>

  <!-- Messages -->
  <div class="flex-1 overflow-y-auto pb-16 md:pb-0 bg-white dark:bg-transparent">
    <div class="px-4 py-2">
      <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Messages</h4>
    </div>

    @foreach ($users as $user)
      @php
        $unreadCount = \App\Models\Message::where('sender_id', $user->id)
          ->where('receiver_id', Auth::id())
          ->whereNull('read_at')
          ->count();
      @endphp
      <button
        class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition user-button"
        data-user-id="{{ $user->id }}">
        <div class="relative">
          <div
            class="w-12 h-12 rounded-full overflow-hidden shadow-md flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600 text-white">
            @if (!empty($user->profile_image))
              <img src="{{ \Illuminate\Support\Facades\Storage::url($user->profile_image) }}" alt="{{ $user->name }}"
                class="w-full h-full object-cover rounded-full">
            @else
              {{ strtoupper(substr($user->name, 0, 2)) }}
            @endif
          </div>
          <span
            class="absolute bottom-0 right-0 w-3 h-3 {{ $user->isOnline() ? 'bg-green-400' : 'bg-gray-400' }} border-2 border-white dark:border-[#0a0f24] rounded-full"></span>
        </div>
        <div class="flex-1 text-left min-w-0">
          <div class="flex items-center justify-between mb-1">
            <h4 class="font-semibold text-sm truncate text-gray-800 dark:text-gray-100">{{ $user->name }}</h4>
            @if ($unreadCount > 0)
              <span
                class="bg-blue-500 text-white text-xs font-semibold px-1.5 rounded-full h-5 flex items-center justify-center">{{ $unreadCount }}</span>
            @endif
          </div>
          <p
            class="text-xs {{ $user->isOnline() ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }} truncate">
            {{ $user->isOnline() ? 'Online' : 'Offline' }}</p>
        </div>
      </button>
    @endforeach
  </div>

  @if (!empty($globalRoom))
    <div class="px-4 py-3 border-t border-gray-300 dark:border-gray-700 bg-white dark:bg-transparent mb-16 md:mb-0">
      <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Pinned</h4>
      <button
        class="w-full px-3 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition rounded-lg room-button"
        data-room-key="{{ $globalRoom['key'] }}" data-room-name="{{ $globalRoom['name'] }}">
        <div class="relative">
          <div
            class="w-10 h-10 rounded-full overflow-hidden shadow-md flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a4 4 0 0 0-4-4h-1m-4 6H2v-2a4 4 0 0 1 4-4h4m1-4a3 3 0 1 1-6 0 3 3 0 0 1 6 0m8 0a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
            </svg>
          </div>
        </div>
        <div class="flex-1 text-left min-w-0">
          <div class="flex items-center justify-between">
            <h4 class="font-semibold text-sm truncate text-gray-800 dark:text-gray-100">{{ $globalRoom['name'] }}</h4>
          </div>
          <p class="text-xs text-gray-500 dark:text-gray-400 truncate">All users</p>
        </div>
      </button>
    </div>
  @endif

  <!-- Bottom Navigation -->
  <div
    class="p-3 fixed bottom-0 left-0 w-full bg-white dark:bg-[#1c1b29] md:static md:w-auto md:border-t md:border-gray-300 dark:md:border-gray-700 md:bg-transparent">
    <div class="flex justify-around text-gray-700 dark:text-gray-300">
      <button class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24"
          stroke-width="2.5">
          <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
          <path d="M3 10a2 2 0 0 1 .7-1.5l7-6a2 2 0 0 1 2.6 0l7 6a2 2 0 0 1 .7 1.5v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
        </svg>
      </button>

      <button class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24"
          stroke-width="2.5">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
        </svg>
      </button>

      <button class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24"
          stroke-width="2.5">
          <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9z" />
          <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
        </svg>
      </button>

      <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50 transition">
        <svg id="theme-toggle-dark-icon" xmlns="http://www.w3.org/2000/svg" class="hidden w-5 h-5 stroke-current"
          fill="none" viewBox="0 0 24 24" stroke-width="2.5">
          <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
        </svg>
        <svg id="theme-toggle-light-icon" xmlns="http://www.w3.org/2000/svg" class="hidden w-5 h-5 stroke-current"
          fill="none" viewBox="0 0 24 24" stroke-width="2.5">
          <circle cx="12" cy="12" r="3" />
          <path
            d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
        </svg>
      </button>

      <button id="logout-button" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700/50 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24"
          stroke-width="2.5">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
          <polyline points="16 17 21 12 16 7" />
          <line x1="21" x2="9" y1="12" y2="12" />
        </svg>
      </button>
    </div>
  </div>

  <x-confirmation-modal />
</div>
