<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <style>
        /* Custom DataTables styling with Tailwind */
        .dataTables_wrapper {
            @apply w-full bg-white rounded-lg shadow-sm;
        }
        
        /* Search and Length Menu Styling */
        .dataTables_length, .dataTables_filter {
            @apply p-4;
        }
        
        .dataTables_length select {
            @apply block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mx-2 !important;
            min-width: 80px;
        }
        
        .dataTables_filter input {
            @apply block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm ml-2 !important;
            min-width: 200px;
        }
        
        /* Table Header Styling */
        table.dataTable thead th {
            @apply bg-gray-50 text-gray-600 text-sm font-semibold px-6 py-4 border-b border-gray-200 !important;
        }
        
        /* Table Body Styling */
        table.dataTable tbody td {
            @apply px-6 py-4 text-gray-800 border-b border-gray-100 !important;
        }
        
        table.dataTable tbody tr:hover {
            @apply bg-blue-50/50 transition duration-150 !important;
        }
        
        /* Pagination Styling */
        .dataTables_paginate {
            @apply p-4 flex items-center justify-end space-x-2 !important;
        }
        
        .paginate_button {
            @apply px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 !important;
        }
        
        .paginate_button.current {
            @apply bg-blue-50 text-blue-600 border-blue-500 !important;
        }
        
        .paginate_button.disabled {
            @apply opacity-50 cursor-not-allowed !important;
        }
        
        /* Info Styling */
        .dataTables_info {
            @apply p-4 text-sm text-gray-700 !important;
        }
        
        /* Responsive Table Styling */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
            @apply bg-blue-500 hover:bg-blue-600 rounded-full !important;
            margin-top: 0 !important;
        }
        
        /* Custom Scrollbar */
        .dataTables_wrapper .dataTables_scroll {
            @apply overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100;
        }
        
        /* Button Styling */
        .dt-button {
            @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 !important;
        }
        
        /* Sorting Icons */
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_desc:before {
            @apply opacity-50;
        }
        
        /* Even/Odd Row Styling */
        table.dataTable tbody tr:nth-child(even) {
            @apply bg-gray-50/50;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-blue-600 hover:text-blue-700 transition">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>
                    <!-- Navigation Links -->
                    @auth
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}" 
                           class="{{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('customers.index') }}"
                           class="{{ request()->routeIs('customers.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Customers
                        </a>
                    </div>
                    @endauth
                </div>

                <!-- Right side menu -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Register
                        </a>
                    @else
                        <!-- Profile dropdown -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" type="button" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                        <span class="text-sm font-medium leading-none text-blue-700">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                    </span>
                                </button>
                            </div>
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 role="menu">
                                <div class="px-4 py-2 text-xs text-gray-500">
                                    {{ Auth::user()->email }}
                                </div>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <svg class="h-6 w-6" x-show="!open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" x-show="open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="open" class="sm:hidden">
            @auth
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="{{ request()->routeIs('dashboard') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Dashboard
                </a>
                <a href="{{ route('customers.index') }}"
                   class="{{ request()->routeIs('customers.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Customers
                </a>
            </div>
            @endauth
            @guest
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300">
                    Register
                </a>
            </div>
            @endguest
        </div>
    </nav>

    <!-- Page Content -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html> 