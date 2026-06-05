<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmniSchedule - Client Portal</title>
    <!-- Tailwind CSS -->
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-stone-50 text-stone-850 antialiased font-sans min-h-screen flex flex-col" x-data="clientPortal()">

    <!-- Toast Notification System -->
    <div class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-md w-full">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" 
                 x-transition:enter="transition ease-out duration-300 transform translate-x-4 opacity-0"
                 x-transition:enter-start="transform translate-x-4 opacity-0"
                 x-transition:enter-end="transform translate-x-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform translate-x-4 opacity-0"
                 class="p-4 rounded-xl border shadow-lg flex items-start gap-3 w-full"
                 :class="{
                     'bg-green-50 border-green-200 text-green-800': toast.type === 'success',
                     'bg-red-50 border-red-200 text-red-800': toast.type === 'error',
                     'bg-blue-50 border-blue-200 text-blue-800': toast.type === 'info'
                 }">
                <div class="mt-0.5">
                    <i class="ph-bold" :class="{
                        'ph-check-circle text-green-600 text-xl': toast.type === 'success',
                        'ph-x-circle text-red-600 text-xl': toast.type === 'error',
                        'ph-info text-blue-600 text-xl': toast.type === 'info'
                    }"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm" x-text="toast.message"></p>
                </div>
                <button @click="dismissToast(toast.id)" class="text-stone-400 hover:text-stone-600">
                    <i class="ph ph-x"></i>
                </button>
            </div>
        </template>
    </div>

    <!-- Header Navigation -->
    <header class="bg-slate-900 text-white shadow-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <img src="<?= base_url('image/logo-3.png') ?>" alt="OmniSchedule Logo" class="h-10 w-auto">
                </div>
                <!-- Navigation -->
                <nav class="hidden md:flex items-center gap-1">
                    <button @click="setTab('home')" :class="activeTab === 'home' ? 'bg-amber-500/10 text-amber-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white'" class="px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                        <i class="ph ph-house inline-block mr-1.5 align-text-bottom"></i>Dashboard
                    </button>
                    <button @click="setTab('book')" :class="activeTab === 'book' ? 'bg-amber-500/10 text-amber-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white'" class="px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                        <i class="ph ph-calendar-plus inline-block mr-1.5 align-text-bottom"></i>Book Appointment
                    </button>
                    <button @click="setTab('bookings')" :class="activeTab === 'bookings' ? 'bg-amber-500/10 text-amber-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white'" class="px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                        <i class="ph ph-list-bullets inline-block mr-1.5 align-text-bottom"></i>My Bookings
                    </button>
                    <button @click="setTab('profile')" :class="activeTab === 'profile' ? 'bg-amber-500/10 text-amber-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white'" class="px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                        <i class="ph ph-user inline-block mr-1.5 align-text-bottom"></i>Profile
                    </button>
                    <div class="h-6 w-[1px] bg-slate-800 mx-2"></div>
                    <a href="<?= base_url('/') ?>" class="text-slate-400 hover:bg-slate-800 hover:text-white px-4 py-2 rounded-xl text-sm font-medium transition duration-200">
                        <i class="ph ph-arrow-left inline-block mr-1.5 align-text-bottom"></i>Back to Home
                    </a>
                </nav>
            </div>

            <!-- Logout Button -->
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 hidden lg:inline">Logged in as: <strong class="text-white"><?= htmlspecialchars($activeClient['first_name'] . ' ' . $activeClient['last_name']) ?></strong></span>
                <a href="<?= base_url('logout') ?>" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition duration-200 shadow-lg shadow-red-600/10 flex items-center gap-1.5">
                    <i class="ph-bold ph-sign-out text-base"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Bar -->
    <div class="md:hidden bg-slate-900 border-t border-slate-800 fixed bottom-0 left-0 right-0 z-30 px-4 py-2 flex justify-between items-center text-slate-400 shadow-xl">
        <button @click="setTab('home')" :class="activeTab === 'home' ? 'text-amber-400' : 'text-slate-400'" class="flex flex-col items-center gap-0.5">
            <i class="ph text-2xl" :class="activeTab === 'home' ? 'ph-house-fill' : 'ph-house'"></i>
            <span class="text-[10px] font-medium">Home</span>
        </button>
        <button @click="setTab('book')" :class="activeTab === 'book' ? 'text-amber-400' : 'text-slate-400'" class="flex flex-col items-center gap-0.5">
            <i class="ph text-2xl" :class="activeTab === 'book' ? 'ph-calendar-plus-fill' : 'ph-calendar-plus'"></i>
            <span class="text-[10px] font-medium">Book</span>
        </button>
        <button @click="setTab('bookings')" :class="activeTab === 'bookings' ? 'text-amber-400' : 'text-slate-400'" class="flex flex-col items-center gap-0.5">
            <i class="ph text-2xl" :class="activeTab === 'bookings' ? 'ph-list-bullets-fill' : 'ph-list-bullets'"></i>
            <span class="text-[10px] font-medium">Bookings</span>
        </button>
        <button @click="setTab('profile')" :class="activeTab === 'profile' ? 'text-amber-400' : 'text-slate-400'" class="flex flex-col items-center gap-0.5">
            <i class="ph text-2xl" :class="activeTab === 'profile' ? 'ph-user-fill' : 'ph-user'"></i>
            <span class="text-[10px] font-medium">Profile</span>
        </button>
        <a href="<?= base_url('/') ?>" class="flex flex-col items-center gap-0.5 text-slate-400">
            <i class="ph ph-arrow-left text-2xl"></i>
            <span class="text-[10px] font-medium">Back</span>
        </a>
    </div>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-20 md:mb-0">
        
        <!-- Tab: Home/Dashboard -->
        <div x-show="activeTab === 'home'" x-transition class="space-y-8">
            <!-- Welcome Hero -->
            <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-slate-955 text-white rounded-3xl p-8 sm:p-10 shadow-2xl border border-slate-800">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-500/5 rounded-full blur-3xl"></div>
                <div class="relative z-10 max-w-2xl">
                    <span class="text-amber-400 font-serif text-sm font-semibold tracking-wider uppercase mb-2 block">Client Workspace</span>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-bold leading-tight mb-4">
                        Welcome, <span class="text-amber-300"><?= $activeClient ? htmlspecialchars($activeClient['first_name']) : 'Guest' ?></span>!
                    </h1>
                    <p class="text-slate-300 text-base sm:text-lg mb-6">
                        Manage your upcoming appointments, explore our premium therapeutic and wellness services, and book a session with our specialists instantly.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <button @click="setTab('book')" class="bg-amber-50 hover:bg-amber-600 bg-amber-500 text-slate-950 font-semibold px-6 py-3 rounded-xl transition duration-200 shadow-lg shadow-amber-500/20 flex items-center gap-2">
                            <i class="ph-bold ph-calendar-plus text-lg"></i>Book New Session
                        </button>
                        <button @click="setTab('bookings')" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-medium px-6 py-3 rounded-xl transition duration-200 flex items-center gap-2">
                            <i class="ph ph-calendar text-lg"></i>View Appointments
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Widgets -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center border border-amber-100">
                        <i class="ph-bold ph-calendar-check text-amber-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-stone-500 uppercase tracking-wider">Active Bookings</p>
                        <p class="text-2xl font-bold text-stone-900 mt-1" x-text="stats.active"></p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center border border-green-100">
                        <i class="ph-bold ph-check-square text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-stone-500 uppercase tracking-wider">Completed Sessions</p>
                        <p class="text-2xl font-bold text-stone-900 mt-1" x-text="stats.completed"></p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center border border-red-100">
                        <i class="ph-bold ph-x-square text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-stone-500 uppercase tracking-wider">Cancelled Bookings</p>
                        <p class="text-2xl font-bold text-stone-900 mt-1" x-text="stats.cancelled"></p>
                    </div>
                </div>
            </div>

            <!-- Quick Upcoming Appointment Section -->
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-stone-100 flex items-center justify-between">
                    <h3 class="text-lg font-serif font-bold text-stone-900">Upcoming Appointments</h3>
                    <button @click="setTab('bookings')" class="text-sm font-semibold text-amber-600 hover:text-amber-700 transition">View All</button>
                </div>
                <div class="p-6">
                    <div class="space-y-4" x-show="upcomingAppointments.length > 0">
                        <template x-for="apt in upcomingAppointments" :key="apt.id">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-xl border border-stone-100 bg-stone-50 hover:bg-stone-100/50 transition gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex flex-col items-center justify-center text-amber-800 shrink-0">
                                        <span class="text-[10px] font-bold uppercase" x-text="formatDateShort(apt.appointment_date).month"></span>
                                        <span class="text-lg font-bold font-serif leading-none" x-text="formatDateShort(apt.appointment_date).day"></span>
                                    </div>
                                    <div>
                                        <h4 class="font-serif font-bold text-stone-900 text-base" x-text="apt.service_name"></h4>
                                        <p class="text-xs text-stone-500 mt-0.5">
                                            Specialist: <span class="font-medium text-stone-700" x-text="apt.staff_first_name + ' ' + apt.staff_last_name"></span>
                                        </p>
                                        <div class="flex items-center gap-3 mt-1.5">
                                            <span class="text-xs text-stone-600 flex items-center gap-1">
                                                <i class="ph ph-clock"></i>
                                                <span x-text="formatTime12(apt.start_time) + ' - ' + formatTime12(apt.end_time)"></span>
                                            </span>
                                            <span class="text-xs text-stone-600 flex items-center gap-1">
                                                <i class="ph ph-tag"></i>
                                                <span x-text="'$' + parseFloat(apt.price).toFixed(2)"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 justify-between sm:justify-end border-t sm:border-t-0 pt-3 sm:pt-0 border-stone-100">
                                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold border uppercase tracking-wider" 
                                          :class="{
                                              'bg-amber-50 border-amber-200 text-amber-700': apt.status === 'pending',
                                              'bg-green-50 border-green-200 text-green-700': apt.status === 'confirmed'
                                          }"
                                          x-text="apt.status"></span>
                                    <button @click="confirmCancel(apt.id)" class="text-xs font-semibold text-red-600 hover:text-red-700 border border-red-200 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="text-center py-10" x-show="upcomingAppointments.length === 0">
                        <div class="w-16 h-16 rounded-full bg-stone-100 flex items-center justify-center mx-auto mb-4">
                            <i class="ph ph-calendar-blank text-stone-400 text-3xl"></i>
                        </div>
                        <h4 class="text-stone-700 font-serif font-bold text-lg">No Upcoming Appointments</h4>
                        <p class="text-stone-500 text-sm mt-1 max-w-sm mx-auto">You don't have any sessions scheduled currently. Choose from our services to make a booking.</p>
                        <button @click="setTab('book')" class="mt-5 bg-amber-500 hover:bg-amber-600 text-slate-955 font-semibold px-5 py-2.5 rounded-xl text-sm transition">
                            Book Session Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Booking Wizard -->
        <div x-show="activeTab === 'book'" x-transition class="max-w-4xl mx-auto space-y-8">
            <!-- Wizard Steps Progress Bar -->
            <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
                <div class="flex items-center justify-between max-w-lg mx-auto relative">
                    <!-- Progress Line -->
                    <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-1 bg-stone-100 z-0"></div>
                    <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-1 bg-amber-500 z-0 transition-all duration-300"
                         :style="'width: ' + ((bookingStep - 1) / 3 * 100) + '%'"></div>

                    <!-- Step 1 -->
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-all duration-300"
                             :class="bookingStep >= 1 ? (bookingStep === 1 ? 'bg-white border-amber-500 text-amber-600 ring-4 ring-amber-500/10' : 'bg-amber-500 border-amber-500 text-slate-955') : 'bg-white border-stone-200 text-stone-400'">
                            <span x-show="bookingStep <= 1">1</span>
                            <i x-show="bookingStep > 1" class="ph-bold ph-check"></i>
                        </div>
                        <span class="text-xs font-semibold" :class="bookingStep >= 1 ? 'text-stone-900 font-bold' : 'text-stone-400'">Service</span>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-all duration-300"
                             :class="bookingStep >= 2 ? (bookingStep === 2 ? 'bg-white border-amber-500 text-amber-600 ring-4 ring-amber-500/10' : 'bg-amber-500 border-amber-500 text-slate-955') : 'bg-white border-stone-200 text-stone-400'">
                            <span x-show="bookingStep <= 2">2</span>
                            <i x-show="bookingStep > 2" class="ph-bold ph-check"></i>
                        </div>
                        <span class="text-xs font-semibold" :class="bookingStep >= 2 ? 'text-stone-900 font-bold' : 'text-stone-400'">Specialist</span>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-all duration-300"
                             :class="bookingStep >= 3 ? (bookingStep === 3 ? 'bg-white border-amber-500 text-amber-600 ring-4 ring-amber-500/10' : 'bg-amber-500 border-amber-500 text-slate-955') : 'bg-white border-stone-200 text-stone-400'">
                            <span x-show="bookingStep <= 3">3</span>
                            <i x-show="bookingStep > 3" class="ph-bold ph-check"></i>
                        </div>
                        <span class="text-xs font-semibold" :class="bookingStep >= 3 ? 'text-stone-900 font-bold' : 'text-stone-400'">Schedule</span>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-all duration-300"
                             :class="bookingStep >= 4 ? 'bg-white border-amber-500 text-amber-600 ring-4 ring-amber-500/10' : 'bg-white border-stone-200 text-stone-400'">
                            <span>4</span>
                        </div>
                        <span class="text-xs font-semibold" :class="bookingStep >= 4 ? 'text-stone-900 font-bold' : 'text-stone-400'">Confirm</span>
                    </div>
                </div>
            </div>

            <!-- Booking Form Panel -->
            <div class="bg-white rounded-3xl border border-stone-200 shadow-md p-6 sm:p-8 min-h-[400px] flex flex-col">
                
                <!-- STEP 1: Select Service -->
                <div x-show="bookingStep === 1" class="space-y-6 flex-1">
                    <div>
                        <h2 class="text-2xl font-serif font-bold text-stone-900">Select a Service</h2>
                        <p class="text-sm text-stone-500 mt-1">Browse our catalogs of premium wellness sessions and select your desired service.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="service in services" :key="service.id">
                            <div @click="selectService(service)"
                                 class="p-6 rounded-2xl border-2 hover:border-amber-500 cursor-pointer transition bg-stone-50 hover:bg-white flex flex-col justify-between group"
                                 :class="selectedService && selectedService.id === service.id ? 'border-amber-500 bg-white ring-4 ring-amber-500/5' : 'border-stone-200'">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-serif font-bold text-lg text-stone-900 group-hover:text-amber-700 transition" x-text="service.name"></h3>
                                        <span class="font-serif text-lg font-bold text-stone-900" x-text="'$' + parseFloat(service.price).toFixed(2)"></span>
                                    </div>
                                    <p class="text-sm text-stone-600 mt-2 line-clamp-3" x-text="service.description"></p>
                                </div>
                                <div class="flex items-center gap-4 mt-6 pt-4 border-t border-stone-200/60 text-xs text-stone-500">
                                    <span class="flex items-center gap-1">
                                        <i class="ph ph-clock text-base"></i>
                                        <span x-text="service.duration_minutes + ' min'"></span>
                                    </span>
                                    <span class="ml-auto text-amber-600 font-semibold flex items-center gap-1 group-hover:translate-x-1 transition duration-200">
                                        Select specialist <i class="ph-bold ph-caret-right"></i>
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- STEP 2: Select Specialist -->
                <div x-show="bookingStep === 2" class="space-y-6 flex-1">
                    <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                        <div>
                            <h2 class="text-2xl font-serif font-bold text-stone-900">Choose a Specialist</h2>
                            <p class="text-sm text-stone-500 mt-1">Select from our certified staff members available for this service.</p>
                        </div>
                        <button @click="bookingStep = 1" class="text-sm font-semibold text-stone-500 hover:text-stone-800 flex items-center gap-1">
                            <i class="ph-bold ph-arrow-left"></i> Back to Services
                        </button>
                    </div>
                    
                    <!-- Loading Specialists -->
                    <div x-show="loadingStaff" class="text-center py-12">
                        <div class="w-10 h-10 border-4 border-amber-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                        <p class="text-stone-500 text-sm">Finding specialists offering this service...</p>
                    </div>

                    <!-- Specialists List -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="!loadingStaff && availableStaff.length > 0">
                        <template x-for="stf in availableStaff" :key="stf.id">
                            <div @click="selectStaff(stf)"
                                 class="p-6 rounded-2xl border-2 hover:border-amber-500 cursor-pointer transition bg-stone-50 hover:bg-white flex flex-col justify-between group"
                                 :class="selectedStaff && selectedStaff.id === stf.id ? 'border-amber-500 bg-white ring-4 ring-amber-500/5' : 'border-stone-200'">
                                <div>
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-serif text-lg font-bold">
                                            <span x-text="stf.first_name[0] + stf.last_name[0]"></span>
                                        </div>
                                        <div>
                                            <h3 class="font-serif font-bold text-base text-stone-900 group-hover:text-amber-700 transition" x-text="stf.first_name + ' ' + stf.last_name"></h3>
                                            <span class="text-xs font-semibold text-amber-600" x-text="stf.title"></span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-stone-600 mt-4 line-clamp-3" x-text="stf.bio"></p>
                                </div>
                                <div class="flex items-center gap-1.5 mt-6 pt-4 border-t border-stone-200/60 text-xs font-semibold text-amber-600 ml-auto group-hover:translate-x-1 transition duration-200">
                                    Choose date & time <i class="ph-bold ph-caret-right"></i>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- No Specialists for Service -->
                    <div x-show="!loadingStaff && availableStaff.length === 0" class="text-center py-12 bg-stone-50 rounded-2xl border border-dashed border-stone-200">
                        <i class="ph ph-user-focus text-stone-400 text-4xl mb-3 block"></i>
                        <h4 class="text-stone-700 font-serif font-bold text-base">No Specialists Found</h4>
                        <p class="text-stone-500 text-sm max-w-xs mx-auto mt-1">Currently, no active staff profiles are linked to this service. Please choose another service.</p>
                        <button @click="bookingStep = 1" class="mt-4 bg-stone-850 hover:bg-stone-900 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                            View Services
                        </button>
                    </div>
                </div>

                <!-- STEP 3: Select Date & Time -->
                <div x-show="bookingStep === 3" class="space-y-6 flex-1">
                    <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                        <div>
                            <h2 class="text-2xl font-serif font-bold text-stone-900">Select Date & Time</h2>
                            <p class="text-sm text-stone-500 mt-1">Pick an available calendar date to view conflict-free time slots.</p>
                        </div>
                        <button @click="bookingStep = 2" class="text-sm font-semibold text-stone-500 hover:text-stone-800 flex items-center gap-1">
                            <i class="ph-bold ph-arrow-left"></i> Back to Specialist
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Date Picker Column -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-stone-700 uppercase tracking-wider">Appointment Date</label>
                            <input type="date" 
                                   x-model="selectedDate" 
                                   @change="fetchTimeSlots()" 
                                   :min="todayDateStr"
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-950 font-medium focus:ring-amber-500 focus:border-amber-500" />
                            <div class="text-xs text-stone-500 bg-stone-100 p-3 rounded-lg flex items-start gap-2">
                                <i class="ph ph-info mt-0.5 text-base text-amber-600"></i>
                                <span>Slots are generated based on the specialist's working hours, overlapping leaves, and existing bookings.</span>
                            </div>
                        </div>

                        <!-- Slots Column (spanning 2 cols) -->
                        <div class="md:col-span-2 space-y-4">
                            <label class="block text-sm font-bold text-stone-700 uppercase tracking-wider">Available Sessions</label>
                            
                            <!-- Loading Slots -->
                            <div x-show="loadingSlots" class="text-center py-8">
                                <div class="w-8 h-8 border-4 border-amber-500 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                                <p class="text-stone-500 text-xs">Generating free time slots...</p>
                            </div>

                            <!-- Slots Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2" x-show="!loadingSlots && slots.length > 0">
                                <template x-for="slot in slots" :key="slot.start_time">
                                    <button @click="selectSlot(slot)"
                                            class="py-2.5 px-4 rounded-xl border-2 text-center text-sm font-medium transition cursor-pointer hover:border-amber-500 focus:outline-none"
                                            :class="selectedSlot && selectedSlot.start_time === slot.start_time ? 'bg-amber-500 border-amber-500 text-slate-955 font-semibold ring-4 ring-amber-500/10' : 'bg-stone-50 border-stone-200 text-stone-805 hover:bg-white'">
                                        <span x-text="slot.display.split(' - ')[0]"></span>
                                    </button>
                                </template>
                            </div>

                            <!-- No Slots Available -->
                            <div x-show="!loadingSlots && slots.length === 0 && selectedDate" class="text-center py-10 bg-red-50/50 rounded-2xl border border-red-200/50 p-6">
                                <i class="ph ph-calendar-x text-red-500 text-3xl mb-2 block"></i>
                                <h4 class="text-red-800 font-serif font-bold text-base">No Sessions Available</h4>
                                <p class="text-red-700/80 text-sm max-w-sm mx-auto mt-0.5">There are no available slots for this date. The specialist may be fully booked, on leave, or off-duty. Please select another date.</p>
                            </div>

                            <!-- Prompt to Select Date -->
                            <div x-show="!selectedDate" class="text-center py-12 bg-stone-50 rounded-2xl border border-dashed border-stone-200 text-stone-400">
                                <i class="ph ph-calendar text-4xl mb-2 block"></i>
                                <p class="text-sm font-medium">Select an appointment date first to load available times.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: Confirm Booking -->
                <div x-show="bookingStep === 4" class="space-y-6 flex-1 flex flex-col justify-between">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                            <div>
                                <h2 class="text-2xl font-serif font-bold text-stone-900">Confirm Appointment</h2>
                                <p class="text-sm text-stone-500 mt-1">Review your appointment details and add any special requests before booking.</p>
                            </div>
                            <button @click="bookingStep = 3" class="text-sm font-semibold text-stone-500 hover:text-stone-800 flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Back to Schedule
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Summary Details Card -->
                            <div class="bg-gradient-to-br from-stone-50 to-stone-100 border border-stone-200 p-6 rounded-2xl space-y-4">
                                <h3 class="font-serif font-bold text-lg text-stone-900 border-b border-stone-200 pb-2">Booking Summary</h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-start justify-between">
                                        <span class="text-stone-500 font-medium">Service:</span>
                                        <span class="text-stone-900 font-bold text-right" x-text="selectedService ? selectedService.name : ''"></span>
                                    </div>
                                    <div class="flex items-start justify-between">
                                        <span class="text-stone-500 font-medium">Duration:</span>
                                        <span class="text-stone-900 font-semibold text-right" x-text="selectedService ? selectedService.duration_minutes + ' minutes' : ''"></span>
                                    </div>
                                    <div class="flex items-start justify-between">
                                        <span class="text-stone-500 font-medium">Price:</span>
                                        <span class="text-stone-900 font-bold text-right" x-text="selectedService ? '$' + parseFloat(selectedService.price).toFixed(2) : ''"></span>
                                    </div>
                                    <div class="border-t border-stone-200 my-2"></div>
                                    <div class="flex items-start justify-between">
                                        <span class="text-stone-500 font-medium">Specialist:</span>
                                        <span class="text-stone-900 font-bold text-right" x-text="selectedStaff ? selectedStaff.first_name + ' ' + selectedStaff.last_name : ''"></span>
                                    </div>
                                    <div class="flex items-start justify-between">
                                        <span class="text-stone-500 font-medium">Title:</span>
                                        <span class="text-amber-600 font-semibold text-right" x-text="selectedStaff ? selectedStaff.title : ''"></span>
                                    </div>
                                    <div class="border-t border-stone-200 my-2"></div>
                                    <div class="flex items-start justify-between">
                                        <span class="text-stone-500 font-medium">Date:</span>
                                        <span class="text-stone-900 font-bold text-right" x-text="formatFullDate(selectedDate)"></span>
                                    </div>
                                    <div class="flex items-start justify-between">
                                        <span class="text-stone-500 font-medium">Time Slot:</span>
                                        <span class="text-amber-600 font-bold text-right" x-text="selectedSlot ? selectedSlot.display : ''"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div class="space-y-3">
                                <label class="block text-sm font-bold text-stone-700 uppercase tracking-wider">Special Requests / Notes</label>
                                <textarea x-model="bookingNotes" 
                                          placeholder="Enter any details or concerns you wish to share with your specialist (e.g. medical conditions, preferences, specific objectives)..."
                                          rows="6"
                                          class="w-full bg-stone-50 border border-stone-300 rounded-2xl px-4 py-3.5 text-stone-900 focus:ring-amber-500 focus:border-amber-500 text-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="pt-6 border-t border-stone-100 flex items-center justify-between mt-8">
                        <button @click="resetWizard()" class="text-sm font-semibold text-stone-500 hover:text-stone-800">
                            Reset Wizard
                        </button>
                        <button @click="submitBooking()" 
                                :disabled="bookingSubmitting"
                                class="bg-amber-500 hover:bg-amber-600 text-slate-955 px-8 py-3.5 rounded-xl font-bold transition shadow-lg shadow-amber-500/10 flex items-center gap-2 cursor-pointer disabled:opacity-50">
                            <span x-show="!bookingSubmitting">Confirm & Book Appointment</span>
                            <span x-show="bookingSubmitting">Processing Booking...</span>
                            <div x-show="bookingSubmitting" class="w-4 h-4 border-2 border-slate-955 border-t-transparent rounded-full animate-spin"></div>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Tab: My Bookings -->
        <div x-show="activeTab === 'bookings'" x-transition class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-serif font-bold text-stone-900">Your Appointment History</h2>
                    <p class="text-sm text-stone-500 mt-1">Review and manage your pending, confirmed, and past appointments.</p>
                </div>
                <!-- Filters -->
                <div class="flex items-center gap-1.5 bg-stone-100 p-1 rounded-xl w-fit self-start">
                    <button @click="bookingFilter = 'all'" :class="bookingFilter === 'all' ? 'bg-white shadow text-stone-900 font-semibold' : 'text-stone-500 hover:text-stone-800'" class="px-4 py-1.5 rounded-lg text-xs transition">All</button>
                    <button @click="bookingFilter = 'upcoming'" :class="bookingFilter === 'upcoming' ? 'bg-white shadow text-stone-900 font-semibold' : 'text-stone-500 hover:text-stone-800'" class="px-4 py-1.5 rounded-lg text-xs transition">Upcoming</button>
                    <button @click="bookingFilter = 'past'" :class="bookingFilter === 'past' ? 'bg-white shadow text-stone-900 font-semibold' : 'text-stone-500 hover:text-stone-800'" class="px-4 py-1.5 rounded-lg text-xs transition">Past</button>
                </div>
            </div>

            <!-- Bookings List -->
            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 border-b border-stone-100 text-stone-400 text-xs font-bold uppercase tracking-wider">
                                <th class="p-6">Service</th>
                                <th class="p-6">Specialist</th>
                                <th class="p-6">Date & Time</th>
                                <th class="p-6">Status</th>
                                <th class="p-6">Notes</th>
                                <th class="p-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm">
                            <template x-for="apt in filteredAppointments" :key="apt.id">
                                <tr class="hover:bg-stone-50/50 transition">
                                    <!-- Service -->
                                    <td class="p-6">
                                        <span class="font-serif font-bold text-stone-900 block" x-text="apt.service_name"></span>
                                        <div class="flex items-center gap-3 mt-1 text-xs text-stone-505">
                                            <span class="flex items-center gap-0.5"><i class="ph ph-clock"></i> <span x-text="apt.duration_minutes + 'm'"></span></span>
                                            <span class="flex items-center gap-0.5"><i class="ph ph-tag"></i> <span x-text="'$' + parseFloat(apt.price).toFixed(2)"></span></span>
                                        </div>
                                    </td>
                                    <!-- Specialist -->
                                    <td class="p-6">
                                        <span class="font-semibold text-stone-800 block" x-text="apt.staff_first_name + ' ' + apt.staff_last_name"></span>
                                        <span class="text-xs text-amber-600 font-medium" x-text="apt.staff_title"></span>
                                    </td>
                                    <!-- Date & Time -->
                                    <td class="p-6">
                                        <span class="font-medium text-stone-700 block" x-text="formatFullDate(apt.appointment_date)"></span>
                                        <span class="text-xs text-stone-500 mt-0.5 block" x-text="formatTime12(apt.start_time) + ' - ' + formatTime12(apt.end_time)"></span>
                                    </td>
                                    <!-- Status -->
                                    <td class="p-6">
                                        <span class="text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider border"
                                              :class="{
                                                  'bg-amber-50 border-amber-200 text-amber-700': apt.status === 'pending',
                                                  'bg-green-50 border-green-200 text-green-700': apt.status === 'confirmed',
                                                  'bg-blue-50 border-blue-200 text-blue-700': apt.status === 'completed',
                                                  'bg-red-50 border-red-200 text-red-700': apt.status === 'cancelled',
                                                  'bg-stone-50 border-stone-200 text-stone-505': apt.status === 'no_show'
                                              }"
                                              x-text="apt.status"></span>
                                    </td>
                                    <!-- Notes -->
                                    <td class="p-6 max-w-xs">
                                        <span class="text-stone-500 text-xs line-clamp-2" :title="apt.client_notes" x-text="apt.client_notes || '—'"></span>
                                    </td>
                                    <!-- Actions -->
                                    <td class="p-6 text-right">
                                        <button @click="confirmCancel(apt.id)"
                                                x-show="apt.status === 'pending' || apt.status === 'confirmed'"
                                                class="text-xs font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 border border-red-100 px-3 py-1.5 rounded-lg transition">
                                            Cancel
                                        </button>
                                        <span x-show="apt.status !== 'pending' && apt.status !== 'confirmed'" class="text-stone-400 text-xs">—</span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="filteredAppointments.length === 0">
                                <td colspan="6" class="p-12 text-center text-stone-500">
                                    <i class="ph ph-calendar-blank text-stone-300 text-4xl mb-2 block"></i>
                                    <p class="font-medium text-stone-700">No appointments found matching this filter.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab: Profile Settings -->
        <div x-show="activeTab === 'profile'" x-transition class="max-w-2xl mx-auto space-y-6">
            <div>
                <h2 class="text-2xl font-serif font-bold text-stone-900">Your Profile Details</h2>
                <p class="text-sm text-stone-500 mt-1">Keep your contact details up to date to receive notifications about your bookings.</p>
            </div>
            
            <div class="bg-white rounded-3xl border border-stone-200 shadow-sm p-6 sm:p-8">
                <form @submit.prevent="updateProfile()" class="space-y-6" enctype="multipart/form-data">
                    <!-- Profile Picture -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-stone-700 uppercase tracking-wider">Profile Picture</label>
                        <div class="flex items-center gap-4">
                            <img :src="profileForm.profile_picture || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(profileForm.first_name + '+' + profileForm.last_name) + '&background=fef3c7&color=92400e'" class="w-16 h-16 rounded-full object-cover border border-stone-200">
                            <input type="file" x-ref="profilePictureInput" accept="image/*" class="text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-stone-700 uppercase tracking-wider">First Name</label>
                            <input type="text" x-model="profileForm.first_name" required
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-900 focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                        <!-- Last Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-stone-700 uppercase tracking-wider">Last Name</label>
                            <input type="text" x-model="profileForm.last_name" required
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-900 focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-stone-700 uppercase tracking-wider">Email Address</label>
                            <input type="email" x-model="profileForm.email" required
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-900 focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-stone-700 uppercase tracking-wider">Phone Number</label>
                            <input type="text" x-model="profileForm.phone"
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-900 focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-stone-100 flex justify-end">
                        <button type="submit" :disabled="profileSaving"
                                class="bg-amber-500 hover:bg-amber-600 text-slate-955 font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-amber-500/10 flex items-center gap-2 cursor-pointer disabled:opacity-50">
                            <span x-show="!profileSaving">Save Profile Changes</span>
                            <span x-show="profileSaving">Saving Changes...</span>
                            <div x-show="profileSaving" class="w-4 h-4 border-2 border-slate-955 border-t-transparent rounded-full animate-spin"></div>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <!-- MODAL: Cancel Confirmation -->
    <div x-show="showCancelModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full border border-stone-200 shadow-2xl p-6 space-y-6" @click.away="showCancelModal = false">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-650 shrink-0">
                    <i class="ph-bold ph-warning-octagon text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-serif font-bold text-lg text-stone-900">Cancel Appointment</h3>
                    <p class="text-sm text-stone-500 mt-2">Are you sure you want to cancel this appointment? This action cannot be undone, and the time slot will be released back to the general pool.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 justify-end pt-4 border-t border-stone-100">
                <button @click="showCancelModal = false" class="px-4 py-2.5 rounded-xl border border-stone-200 hover:bg-stone-50 text-sm font-semibold text-stone-700 transition">
                    No, Keep Booking
                </button>
                <button @click="executeCancellation()" :disabled="cancelSubmitting" class="bg-red-650 hover:bg-red-750 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition flex items-center gap-2 cursor-pointer disabled:opacity-50">
                    <span x-show="!cancelSubmitting">Yes, Cancel Booking</span>
                    <span x-show="cancelSubmitting">Cancelling...</span>
                    <div x-show="cancelSubmitting" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                </button>
            </div>
        </div>
    </div>



    <!-- UI Footer -->
    <footer class="bg-stone-100 border-t border-stone-200/60 py-6 mt-12 text-center text-xs text-stone-500 mb-20 md:mb-0">
        <p>&copy; <?= date('Y') ?> OmniSchedule Appointments. All rights reserved.</p>
    </footer>

    <!-- Alpine.js Application Logic -->
    <script>
        function clientPortal() {
            return {
                // SPA Navigation
                activeTab: ['home', 'book', 'bookings', 'profile'].includes(window.location.hash.substring(1)) ? window.location.hash.substring(1) : 'home',
                toasts: [],
                toastId: 0,
                
                // Seeded Data
                services: <?= json_encode($services) ?>,
                staff: <?= json_encode($staff) ?>,
                appointments: <?= json_encode($clientAppointments) ?>,
                activeClient: <?= json_encode($activeClient) ?>,

                // Booking Wizard
                bookingStep: 1,
                selectedService: null,
                availableStaff: [],
                loadingStaff: false,
                selectedStaff: null,
                selectedDate: '',
                slots: [],
                loadingSlots: false,
                selectedSlot: null,
                bookingNotes: '',
                bookingSubmitting: false,

                // Cancellation state
                showCancelModal: false,
                appointmentToCancel: null,
                cancelSubmitting: false,



                // Profile State
                profileSaving: false,
                profileForm: {
                    first_name: '<?= $activeClient ? htmlspecialchars($activeClient['first_name'], ENT_QUOTES) : "" ?>',
                    last_name: '<?= $activeClient ? htmlspecialchars($activeClient['last_name'], ENT_QUOTES) : "" ?>',
                    email: '<?= $activeClient ? htmlspecialchars($activeClient['email'], ENT_QUOTES) : "" ?>',
                    phone: '<?= $activeClient ? htmlspecialchars($activeClient['phone'], ENT_QUOTES) : "" ?>',
                    profile_picture: '<?= session()->get("profile_picture") ? htmlspecialchars(session()->get("profile_picture"), ENT_QUOTES) : ($activeClient["profile_picture"] ?? "") ?>'
                },

                // Booking History Filter
                bookingFilter: 'all',

                init() {
                    // Watch for browser back/forward buttons
                    window.addEventListener('hashchange', () => {
                        const hash = window.location.hash.substring(1);
                        if (['home', 'book', 'bookings', 'profile'].includes(hash)) {
                            if (this.activeTab !== hash) {
                                this.activeTab = hash;
                                if (hash !== 'book') this.resetWizard();
                            }
                        }
                    });

                    // Min Date for date picker
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    this.todayDateStr = `${yyyy}-${mm}-${dd}`;
                },

                // Toast helper
                showToast(message, type = 'success') {
                    const id = this.toastId++;
                    this.toasts.push({ id, message, type, visible: true });
                    setTimeout(() => {
                        this.dismissToast(id);
                    }, 5000);
                },

                dismissToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index !== -1) {
                        this.toasts[index].visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300);
                    }
                },

                setTab(tab) {
                    this.activeTab = tab;
                    window.location.hash = tab;
                    // Reset wizard on changing tab away from book
                    if(tab !== 'book') {
                        this.resetWizard();
                    }
                },

                // Statistics Computed Properties
                get stats() {
                    return {
                        active: this.appointments.filter(a => a.status === 'pending' || a.status === 'confirmed').length,
                        completed: this.appointments.filter(a => a.status === 'completed').length,
                        cancelled: this.appointments.filter(a => a.status === 'cancelled').length
                    };
                },

                get upcomingAppointments() {
                    return this.appointments.filter(a => a.status === 'pending' || a.status === 'confirmed');
                },

                get filteredAppointments() {
                    const nowStr = this.todayDateStr;
                    if (this.bookingFilter === 'upcoming') {
                        return this.appointments.filter(a => a.appointment_date >= nowStr && a.status !== 'cancelled' && a.status !== 'no_show');
                    } else if (this.bookingFilter === 'past') {
                        return this.appointments.filter(a => a.appointment_date < nowStr || a.status === 'completed');
                    }
                    return this.appointments;
                },

                // Date formatting helpers
                formatDateShort(dateStr) {
                    const date = new Date(dateStr);
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    return {
                        month: months[date.getMonth()],
                        day: date.getDate()
                    };
                },

                formatFullDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                },

                formatTime12(timeStr) {
                    if (!timeStr) return '';
                    const [hours, minutes] = timeStr.split(':');
                    let hrs = parseInt(hours);
                    const ampm = hrs >= 12 ? 'PM' : 'AM';
                    hrs = hrs % 12;
                    hrs = hrs ? hrs : 12; // 0 should be 12
                    return `${hrs}:${minutes} ${ampm}`;
                },



                // Profile Updates
                updateProfile() {
                    this.profileSaving = true;
                    
                    const formData = new FormData();
                    formData.append('first_name', this.profileForm.first_name);
                    formData.append('last_name', this.profileForm.last_name);
                    formData.append('email', this.profileForm.email);
                    formData.append('phone', this.profileForm.phone);
                    
                    if (this.$refs.profilePictureInput.files.length > 0) {
                        formData.append('profile_picture', this.$refs.profilePictureInput.files[0]);
                    }

                    fetch('<?= base_url('update-profile') ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.profileSaving = false;
                        if (data.status === 'success') {
                            this.showToast(data.message, 'success');
                            if (this.$refs.profilePictureInput.files.length > 0) {
                                // Simple reload to show new picture (or could update src manually)
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        } else {
                            this.showToast(data.message, 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        this.profileSaving = false;
                        this.showToast('Network error updating profile.', 'error');
                    });
                },

                // Booking Wizard Methods
                selectService(service) {
                    this.selectedService = service;
                    this.availableStaff = [];
                    this.selectedStaff = null;
                    this.bookingStep = 2;
                    this.loadingStaff = true;

                    // Fetch staff offering service
                    fetch(`<?= base_url("client/staff-by-service") ?>/${service.id}`)
                        .then(res => res.json())
                        .then(data => {
                            this.availableStaff = data;
                            this.loadingStaff = false;
                        });
                },

                selectStaff(staff) {
                    this.selectedStaff = staff;
                    this.selectedDate = '';
                    this.slots = [];
                    this.selectedSlot = null;
                    this.bookingStep = 3;
                },

                fetchTimeSlots() {
                    if (!this.selectedDate) return;
                    this.loadingSlots = true;
                    this.slots = [];
                    this.selectedSlot = null;

                    fetch('<?= base_url("client/slots") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `staff_id=${this.selectedStaff.id}&service_id=${this.selectedService.id}&date=${this.selectedDate}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.loadingSlots = false;
                        if(Array.isArray(data)) {
                            this.slots = data;
                        } else {
                            this.showToast(data.message || 'Error fetching time slots.', 'error');
                        }
                    });
                },

                selectSlot(slot) {
                    this.selectedSlot = slot;
                    this.bookingStep = 4;
                },

                resetWizard() {
                    this.bookingStep = 1;
                    this.selectedService = null;
                    this.availableStaff = [];
                    this.selectedStaff = null;
                    this.selectedDate = '';
                    this.slots = [];
                    this.selectedSlot = null;
                    this.bookingNotes = '';
                },

                submitBooking() {
                    this.bookingSubmitting = true;
                    
                    const bodyParams = new URLSearchParams({
                        service_id: this.selectedService.id,
                        staff_id: this.selectedStaff.id,
                        date: this.selectedDate,
                        start_time: this.selectedSlot.start_time,
                        client_notes: this.bookingNotes
                    });

                    fetch('<?= base_url("client/book") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: bodyParams.toString()
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.bookingSubmitting = false;
                        if (data.status === 'success') {
                            this.showToast(data.message);
                            this.resetWizard();
                            this.activeTab = 'bookings';
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            this.showToast(data.message, 'error');
                        }
                    });
                },

                // Cancellation Workflow
                confirmCancel(id) {
                    this.appointmentToCancel = id;
                    this.showCancelModal = true;
                },

                executeCancellation() {
                    this.cancelSubmitting = true;
                    fetch(`<?= base_url("client/cancel") ?>/${this.appointmentToCancel}`, {
                        method: 'POST'
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.cancelSubmitting = false;
                        this.showCancelModal = false;
                        if (data.status === 'success') {
                            this.showToast(data.message);
                            setTimeout(() => window.location.reload(), 1200);
                        } else {
                            this.showToast(data.message, 'error');
                        }
                    });
                }
            };
        }
    </script>
    <?= $this->include('components/chatbot') ?>
</body>
</html>
