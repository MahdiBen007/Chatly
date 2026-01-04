<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chat - Connect & Create</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col items-center font-sans transition-all duration-300 
  bg-[radial-gradient(circle_at_top_left,#ffffff,#f3f4f6,#e5e7eb)] 
  dark:bg-[radial-gradient(circle_at_top_left,#0a0f24,#182848,#1c1b29)] 
  text-gray-800 dark:text-white">

  <header
    class="sticky top-0 z-50 w-full flex justify-between items-center py-3 px-4 md:py-4 md:px-14 backdrop-blur-xl border-b
    bg-white/80 dark:bg-black/10 text-gray-900 dark:text-white border-gray-300 dark:border-white/10 shadow-sm dark:shadow-none">
    <a href="{{ url('/') }}" class="flex items-center gap-2.5 transition-transform duration-300 hover:scale-105">
      <div
        class="bg-gradient-to-br from-[#007cf0] to-[#00dfd8] w-8 h-8 md:w-9 md:h-9 rounded-full flex justify-center items-center shadow-[0_0_12px_rgba(0,223,216,0.6)] animate-glow-primary">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
          <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
        </svg>
      </div>
      <span class="font-bold text-xl md:text-xl tracking-wider text-gray-900 dark:text-white">Chatly</span>
    </a>
    <div class="flex items-center gap-2 md:gap-4">
      <a href="{{ route('login') }}"
        class="py-1 px-4 text-sm md:py-1.5 md:px-6 rounded-lg md:rounded-xl font-semibold transition-all duration-300 
        bg-transparent border border-gray-400 dark:border-white text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-white/15">Log In</a>
      <a href="{{ route('register') }}"
        class="py-1 px-4 text-sm md:py-1.5 md:px-6 rounded-lg md:rounded-xl font-semibold transition-all duration-300 
        bg-gradient-to-r from-[#007cf0] to-[#00dfd8] text-white hover:shadow-[0_0_15px_#00dfd8] hover:-translate-y-0.5">Sign Up</a>
      <button id="theme-toggle"
        class="p-2 rounded-full bg-gray-100 dark:bg-white/10 border border-gray-300 dark:border-white/20 hover:bg-gray-200 dark:hover:bg-white/20 transition">
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
    </div>
  </header>

  <main
    class="flex flex-col lg:flex-row justify-center lg:justify-between items-center w-full px-4 md:w-11/12 lg:w-4/5 lg:px-0 flex-1 my-8 md:my-10 gap-12 lg:gap-10">
    <div class="w-full lg:max-w-[50%] flex flex-col items-center lg:items-start opacity-0 animate-fade-in-up">
      <h1
        class="text-4xl md:text-5xl lg:text-6xl leading-tight font-bold text-center lg:text-left text-gray-900 dark:text-white">
        Chat. <span
          class="bg-gradient-to-r from-[#00dfd8] to-[#007cf0] text-transparent bg-clip-text">Connect.</span><br>
        <span class="bg-gradient-to-r from-[#00dfd8] to-[#007cf0] text-transparent bg-clip-text">Create.</span>
      </h1>
      <p class="my-5 text-base md:text-lg text-gray-600 dark:text-[#bbb] text-center lg:text-left">
        A modern way to talk with anyone, anywhere — fast, secure, and beautiful.
      </p>
      <div class="flex gap-4 mt-5">
        <a href="{{ route('register') }}"
          class="py-2 px-7 rounded-full font-semibold transition-all duration-300 
          bg-gradient-to-r from-[#007cf0] to-[#00dfd8] text-white hover:shadow-[0_0_15px_#00dfd8] hover:-translate-y-0.5">Get Started</a>
        <a href="{{ route('login') }}"
          class="py-2 px-7 rounded-full font-semibold transition-all duration-300 
          bg-transparent border-2 border-gray-600 dark:border-white text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-white/15">Log
          In</a>
      </div>
      <div
        class="mt-10 flex justify-center lg:justify-start gap-2 md:gap-4 text-xs md:text-sm flex-wrap text-gray-700 dark:text-[#ccc]">
        <div
          class="rounded-full py-2 px-3 md:px-4 bg-gray-200 dark:bg-white/10 shadow-sm dark:shadow-[0_0_10px_rgba(0,223,216,0.2)]">
          🔒 End-to-End Encryption</div>
        <div
          class="rounded-full py-2 px-3 md:px-4 bg-gray-200 dark:bg-white/10 shadow-sm dark:shadow-[0_0_10px_rgba(0,223,216,0.2)]">
          🌍 Global Connection</div>
        <div
          class="rounded-full py-2 px-3 md:px-4 bg-gray-200 dark:bg-white/10 shadow-sm dark:shadow-[0_0_10px_rgba(0,223,216,0.2)]">
          ⚡ Real-Time Messaging</div>
      </div>
    </div>

    <div
      class="relative w-full max-w-[500px] lg:w-[30rem] bg-white dark:bg-black/20 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl shadow-xl dark:shadow-2xl overflow-hidden opacity-0 animate-fade-in-up [animation-delay:300ms]">
      <div class="bg-gradient-to-r from-[#007cf0] to-[#7928ca] p-4 flex items-center gap-3 text-white">
        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center font-bold">J
        </div>
        <div>
          <div class="font-semibold">John Doe</div>
          <div class="text-white/80 text-xs">Online</div>
        </div>
        <div class="ml-auto">
          <div class="w-2 h-2 rounded-full bg-[#00dfd8] animate-pulse"></div>
        </div>
      </div>
      <div class="p-4 md:p-6 space-y-4 min-h-[300px]">
        <div class="flex justify-start opacity-0 animate-slide-in-left [animation-delay:1.5s]">
          <div
            class="max-w-[75%] px-3 py-2 md:px-4 md:py-3 rounded-2xl bg-gray-100 dark:bg-white/10 text-gray-800 dark:text-white text-sm md:text-base">
            Hey! How's it going?</div>
        </div>
        <div class="flex justify-end opacity-0 animate-slide-in-right [animation-delay:2.2s]">
          <div
            class="max-w-[75%] px-3 py-2 md:px-4 md:py-3 rounded-2xl bg-gradient-to-r from-[#007cf0] to-[#7928ca] text-white shadow-[0_4px_10px_rgba(0,223,216,0.3)] text-sm md:text-base">
            Great! Just finished the project 🎉</div>
        </div>
        <div class="flex justify-start opacity-0 animate-slide-in-left [animation-delay:3.2s]">
          <div
            class="max-w-[75%] px-3 py-2 md:px-4 md:py-3 rounded-2xl bg-gray-100 dark:bg-white/10 text-gray-800 dark:text-white text-sm md:text-base">
            That's awesome! Want to celebrate?</div>
        </div>
        <div class="flex justify-end opacity-0 animate-slide-in-right [animation-delay:4.0s]">
          <div
            class="max-w-[75%] px-3 py-2 md:px-4 md:py-3 rounded-2xl bg-gradient-to-r from-[#007cf0] to-[#7928ca] text-white shadow-[0_4px_10px_rgba(0,223,216,0.3)] text-sm md:text-base">
            Absolutely! Let's meet up 😄</div>
        </div>
      </div>
      <div class="p-3 md:p-4 border-t border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/10">
        <div class="flex gap-2 items-center px-3 py-1 md:px-4 md:py-2 bg-gray-100 dark:bg-white/10 rounded-full">
          <input type="text" placeholder="Type a message..."
            class="flex-1 bg-transparent border-none outline-none text-gray-800 dark:text-white placeholder:text-gray-500 text-sm md:text-base">
          <button
            class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gradient-to-r from-[#007cf0] to-[#00dfd8] flex items-center justify-center text-white text-lg md:text-xl">➤</button>
        </div>
      </div>
    </div>
  </main>



  <button id="to-top-button" title="Go To Top"
    class="hidden fixed bottom-8 right-8 z-50 p-3 bg-gradient-to-r from-[#007cf0] to-[#00dfd8] text-white rounded-full shadow-lg transition-transform duration-300 hover:scale-110 focus:outline-none">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
  </button>

  <script>
    const toTopButton = document.getElementById('to-top-button');

    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 100) {
        toTopButton.classList.remove('hidden');
      } else {
        toTopButton.classList.add('hidden');
      }
    });

    toTopButton.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  </script>

</body>

</html>