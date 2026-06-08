<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Video Conference</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-white min-h-screen flex flex-col font-sans text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white px-4 py-3 flex justify-between items-center w-full">
        <!-- Left Side: Hamburger & Logo -->
        <div class="flex items-center gap-4">
            <button class="p-2 hover:bg-gray-100 rounded-full text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div class="flex items-center gap-2 cursor-pointer">
                <!-- Colorful Meet-like Logo -->
                <div class="w-8 h-8 flex items-center justify-center">
                    <svg class="w-full h-full" viewBox="0 0 24 24" fill="none">
                        <path d="M2.5 16.5V7.5C2.5 6.11929 3.61929 5 5 5H13C14.3807 5 15.5 6.11929 15.5 7.5V16.5C15.5 17.8807 14.3807 19 13 19H5C3.61929 19 2.5 17.8807 2.5 16.5Z" fill="#0284c7"/>
                        <path d="M21.5 15.2111V8.78885C21.5 7.82582 20.4079 7.27211 19.6214 7.83403L16 10.4214V13.5786L19.6214 16.166C20.4079 16.7279 21.5 16.1742 21.5 15.2111Z" fill="#34A853"/>
                    </svg>
                </div>
                <span class="text-[22px] font-medium text-gray-600 tracking-tight">Meet BPS</span>
            </div>
        </div>

        <!-- Right Side: Date, Icons, Profile -->
        <div class="flex items-center gap-2 md:gap-4 text-gray-600">
            <div class="hidden md:block text-lg px-2" id="headerDateTime">
                {{ date('g:i A • D, M j') }}
            </div>
            
            <button class="hidden md:block p-2 hover:bg-gray-100 rounded-full transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/></svg>
            </button>
            <button class="hidden md:block p-2 hover:bg-gray-100 rounded-full transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-4h-2V6h2v4z"/></svg>
            </button>
            <button class="p-2 hover:bg-gray-100 rounded-full transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.06-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32a.488.488 0 00-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 00-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.56-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.49.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.06.94l-2.03 1.58a.49.49 0 00-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .43-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39-.96c.22-.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.49-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
            </button>
            <div class="mx-2 hidden md:block">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"/></svg>
            </div>
            <!-- Avatar -->
            <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-sm">
                {{ substr(auth()->user()?->name ?? 'User', 0, 1) }}
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="grow flex flex-col w-full h-full relative overflow-hidden">
        {{ $slot }}
    </main>

    <script>
        function updateTime() {
            const el = document.getElementById('headerDateTime');
            if(el) {
                const now = new Date();
                let h = now.getHours();
                const m = now.getMinutes().toString().padStart(2, '0');
                const ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12;
                h = h ? h : 12;
                const days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                el.innerText = h + ':' + m + ' ' + ampm + ' • ' + days[now.getDay()] + ', ' + months[now.getMonth()] + ' ' + now.getDate();
            }
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>

</html>
