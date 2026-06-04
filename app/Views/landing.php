<?php
$bookUrl = !empty($activeClient) ? base_url('dashboard') : base_url('login');
$authLinkText = !empty($activeClient) ? 'Dashboard' : 'Login';
$authLinkUrl = !empty($activeClient) ? base_url('dashboard') : base_url('login');
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AURA - Premium Appointment Scheduling & Wellness</title>
    <!-- Tailwind CSS -->
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-stone-50 text-stone-850 antialiased font-sans flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">

    <!-- Top Navigation Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-stone-200/50 fixed top-0 left-0 right-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center">
                    <i class="ph-bold ph-sparkle text-amber-600 text-2xl"></i>
                </div>
                <span class="font-serif text-2xl font-bold tracking-wider text-amber-800">AURA</span>
            </div>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-stone-600">
                <a href="#services" class="hover:text-amber-700 transition">Services</a>
                <a href="#specialists" class="hover:text-amber-700 transition">Specialists</a>
                <a href="#testimonials" class="hover:text-amber-700 transition">Testimonials</a>
                <a href="#faq" class="hover:text-amber-700 transition">FAQ</a>
                <a href="#portals" class="hover:text-amber-700 transition">Portals</a>
                <a href="<?= $authLinkUrl ?>" class="hover:text-amber-700 transition"><?= $authLinkText ?></a>
            </nav>

            <!-- CTA Buttons -->
            <div class="hidden md:flex items-center gap-3">
                <a href="<?= $bookUrl ?>" class="bg-amber-500 hover:bg-amber-600 text-slate-955 font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-lg shadow-amber-500/10">
                    Book Online
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-stone-600 p-2 focus:outline-none">
                <i class="ph-bold text-2xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
            </button>
        </div>

        <!-- Mobile Nav Menu -->
        <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden bg-white border-b border-stone-200 px-4 py-4 space-y-3 shadow-lg">
            <a href="#services" @click="mobileMenuOpen = false" class="block font-semibold text-stone-600 py-1 hover:text-amber-700">Services</a>
            <a href="#specialists" @click="mobileMenuOpen = false" class="block font-semibold text-stone-600 py-1 hover:text-amber-700">Specialists</a>
            <a href="#testimonials" @click="mobileMenuOpen = false" class="block font-semibold text-stone-600 py-1 hover:text-amber-700">Testimonials</a>
            <a href="#faq" @click="mobileMenuOpen = false" class="block font-semibold text-stone-600 py-1 hover:text-amber-700">FAQ</a>
            <a href="#portals" @click="mobileMenuOpen = false" class="block font-semibold text-stone-600 py-1 hover:text-amber-700">Portals</a>
            <a href="<?= $authLinkUrl ?>" @click="mobileMenuOpen = false" class="block font-semibold text-stone-600 py-1 hover:text-amber-700"><?= $authLinkText ?></a>
            <div class="pt-3 border-t border-stone-100 flex flex-col gap-2">
                <a href="<?= $bookUrl ?>" class="bg-amber-500 hover:bg-amber-600 text-slate-955 text-center font-bold px-4 py-2.5 rounded-xl text-sm transition shadow-lg">
                    Book Online
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 sm:pt-40 sm:pb-28 relative overflow-hidden bg-gradient-to-b from-stone-100 via-stone-50 to-stone-50">
        <div class="absolute -right-24 top-20 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
        <div class="absolute -left-32 bottom-10 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/20 text-amber-800 px-3.5 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-6">
                <i class="ph-bold ph-sparkle text-sm"></i> Elegant Wellness Bookings
            </div>
            
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-stone-900 tracking-tight leading-tight max-w-4xl mx-auto mb-6">
                Harmonize Your Body, Mind, &amp; <span class="text-amber-700">Daily Schedule</span>
            </h1>
            
            <p class="text-stone-600 text-base sm:text-lg lg:text-xl max-w-2xl mx-auto leading-relaxed mb-10">
                Experience a seamless booking interface for our exclusive therapeutic consultations, expert counseling, and specialty wellness services. Select your specialist and secure your session in seconds.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="<?= $bookUrl ?>" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-slate-955 font-bold px-8 py-4 rounded-xl text-base transition shadow-xl shadow-amber-500/15 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-calendar-plus text-lg"></i> Book Appointment Now
                </a>
                <a href="#services" class="w-full sm:w-auto bg-white hover:bg-stone-50 border border-stone-300 text-stone-700 font-semibold px-8 py-4 rounded-xl text-base transition flex items-center justify-center gap-2">
                    Explore Services <i class="ph-bold ph-arrow-down"></i>
                </a>
            </div>

            <!-- Features Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16 max-w-5xl mx-auto border-t border-stone-200/60 pt-12">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-white border border-stone-200 rounded-xl flex items-center justify-center text-amber-600 shadow-sm mb-3">
                        <i class="ph ph-shield-check text-2xl"></i>
                    </div>
                    <span class="font-serif font-bold text-stone-900 text-sm">Conflict Free</span>
                    <span class="text-xs text-stone-500 mt-1">Real-time slot checking</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-white border border-stone-200 rounded-xl flex items-center justify-center text-amber-600 shadow-sm mb-3">
                        <i class="ph ph-users-three text-2xl"></i>
                    </div>
                    <span class="font-serif font-bold text-stone-900 text-sm">Expert Staff</span>
                    <span class="text-xs text-stone-500 mt-1">Certified professionals</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-white border border-stone-200 rounded-xl flex items-center justify-center text-amber-600 shadow-sm mb-3">
                        <i class="ph ph-bell-ringing text-2xl"></i>
                    </div>
                    <span class="font-serif font-bold text-stone-900 text-sm">Smart Alerts</span>
                    <span class="text-xs text-stone-500 mt-1">Notifications & reminders</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-white border border-stone-200 rounded-xl flex items-center justify-center text-amber-600 shadow-sm mb-3">
                        <i class="ph ph-pencil-line text-2xl"></i>
                    </div>
                    <span class="font-serif font-bold text-stone-900 text-sm">Self-Manage</span>
                    <span class="text-xs text-stone-500 mt-1">Easy cancels & updates</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Catalog -->
    <section id="services" class="py-20 sm:py-28 bg-white border-t border-stone-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-amber-700 font-serif text-sm font-semibold tracking-wider uppercase mb-2 block">Our Catalog</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-stone-900">Premium Packages &amp; Services</h2>
                <p class="text-stone-500 mt-3 text-sm sm:text-base">We offer custom-tailored therapy sessions and consultation programs designed around your individual goals.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                        <div class="bg-stone-50 p-8 rounded-3xl border border-stone-200 flex flex-col justify-between hover:border-amber-500 hover:shadow-xl hover:shadow-stone-200/50 transition duration-300 group">
                            <div>
                                <div class="flex items-start justify-between">
                                    <h3 class="font-serif font-bold text-xl text-stone-900 group-hover:text-amber-800 transition" style="margin: 0;"><?= htmlspecialchars($service['name']) ?></h3>
                                    <span class="font-serif text-xl font-bold text-stone-950">$<?= number_format($service['price'], 2) ?></span>
                                </div>
                                <p class="text-stone-600 text-sm mt-4 leading-relaxed"><?= htmlspecialchars($service['description']) ?></p>
                            </div>
                            <div class="mt-8 pt-6 border-t border-stone-200/80 flex items-center justify-between text-xs text-stone-500">
                                <span class="flex items-center gap-1">
                                    <i class="ph ph-clock text-base"></i>
                                    <span><?= $service['duration_minutes'] ?> min duration</span>
                                </span>
                                <a href="<?= $bookUrl ?>" class="text-amber-700 font-semibold flex items-center gap-0.5 group-hover:translate-x-1 transition duration-200">
                                    Book Service <i class="ph-bold ph-caret-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-3 text-center py-10 text-stone-500">
                        <p>No active services found in the database. Please run the seeds.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Specialists Section -->
    <section id="specialists" class="py-20 sm:py-28 bg-stone-50 border-t border-stone-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-amber-700 font-serif text-sm font-semibold tracking-wider uppercase mb-2 block">Our Specialists</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-stone-900">Meet Our Certified Team</h2>
                <p class="text-stone-500 mt-3 text-sm sm:text-base">Connect with our dedicated service providers trained in advanced care methodologies.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (!empty($staff)): ?>
                    <?php foreach ($staff as $s): ?>
                        <div class="bg-white p-6 rounded-3xl border border-stone-200 flex flex-col justify-between hover:shadow-lg transition">
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-serif text-xl font-bold">
                                        <?= $s['first_name'][0] . $s['last_name'][0] ?>
                                    </div>
                                    <div>
                                        <h3 class="font-serif font-bold text-lg text-stone-900" style="margin:0;"><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></h3>
                                        <span class="text-xs font-semibold text-amber-700 block mt-0.5"><?= htmlspecialchars($s['title']) ?></span>
                                    </div>
                                </div>
                                <p class="text-stone-600 text-sm leading-relaxed"><?= htmlspecialchars($s['bio']) ?></p>
                            </div>
                            <div class="mt-6 pt-4 border-t border-stone-100 flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1">
                                    <span class="w-2.5 h-2.5 rounded-full <?= $s['is_available'] ? 'bg-green-500' : 'bg-red-500' ?>"></span>
                                    <span class="text-stone-500 font-medium"><?= $s['is_available'] ? 'Available for bookings' : 'On leave' ?></span>
                                </span>
                                <?php if ($s['is_available']): ?>
                                    <a href="<?= $bookUrl ?>" class="text-amber-700 font-semibold">Select specialist &rarr;</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-3 text-center py-10 text-stone-500">
                        <p>No specialists found in the database. Please run the seeds.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 sm:py-28 bg-white border-t border-stone-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-amber-700 font-serif text-sm font-semibold tracking-wider uppercase mb-2 block">Client Reviews</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-stone-900">What Our Clients Say</h2>
                <p class="text-stone-500 mt-3 text-sm sm:text-base">Read feedback from clients who have experienced Aura's wellness consultations.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="p-8 rounded-3xl border border-stone-200 bg-stone-50 flex flex-col justify-between relative">
                    <i class="ph-fill ph-quotes text-amber-500/10 text-7xl absolute right-6 top-4 z-0"></i>
                    <p class="text-stone-700 text-sm leading-relaxed relative z-10 italic">
                        "The booking experience is exceptionally smooth. Being able to choose my therapist, inspect their availability, and cancel online directly without calling is fantastic."
                    </p>
                    <div class="flex items-center gap-3 mt-8 relative z-10 border-t border-stone-200/60 pt-4">
                        <div class="w-9 h-9 rounded-full bg-amber-500/20 flex items-center justify-center font-bold text-xs text-amber-800">JD</div>
                        <div>
                            <span class="font-bold text-stone-900 text-sm block">John Doe</span>
                            <span class="text-[10px] text-stone-400">Regular Client</span>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 2 -->
                <div class="p-8 rounded-3xl border border-stone-200 bg-stone-50 flex flex-col justify-between relative">
                    <i class="ph-fill ph-quotes text-amber-500/10 text-7xl absolute right-6 top-4 z-0"></i>
                    <p class="text-stone-700 text-sm leading-relaxed relative z-10 italic">
                        "Dr. Sarah Smith's consultations changed my wellness goals entirely. Setting appointments is conflict-free and the calendar interface is very intuitive."
                    </p>
                    <div class="flex items-center gap-3 mt-8 relative z-10 border-t border-stone-200/60 pt-4">
                        <div class="w-9 h-9 rounded-full bg-amber-500/20 flex items-center justify-center font-bold text-xs text-amber-800">JS</div>
                        <div>
                            <span class="font-bold text-stone-900 text-sm block">Jane Smith</span>
                            <span class="text-[10px] text-stone-400">Consultation Client</span>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 3 -->
                <div class="p-8 rounded-3xl border border-stone-200 bg-stone-50 flex flex-col justify-between relative">
                    <i class="ph-fill ph-quotes text-amber-500/10 text-7xl absolute right-6 top-4 z-0"></i>
                    <p class="text-stone-700 text-sm leading-relaxed relative z-10 italic">
                        "I love how quick and transparent the portal is. I can update my profile details, register in seconds, and see my entire appointment log history in one tab."
                    </p>
                    <div class="flex items-center gap-3 mt-8 relative z-10 border-t border-stone-200/60 pt-4">
                        <div class="w-9 h-9 rounded-full bg-amber-500/20 flex items-center justify-center font-bold text-xs text-amber-800">AJ</div>
                        <div>
                            <span class="font-bold text-stone-900 text-sm block">Alice Johnson</span>
                            <span class="text-[10px] text-stone-400">Therapy Client</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Accordions -->
    <section id="faq" class="py-20 sm:py-28 bg-stone-50 border-t border-stone-200/50" x-data="{ activeFaq: null }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-amber-700 font-serif text-sm font-semibold tracking-wider uppercase mb-2 block">FAQ</span>
                <h2 class="text-3xl font-serif font-bold text-stone-900">Frequently Asked Questions</h2>
                <p class="text-stone-500 mt-2 text-sm">Everything you need to know about setting up and managing your appointments.</p>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden transition">
                    <button @click="activeFaq = activeFaq === 1 ? null : 1" class="w-full text-left p-6 font-serif font-bold text-stone-900 text-base flex justify-between items-center focus:outline-none">
                        <span>How do I schedule an appointment?</span>
                        <i class="ph text-lg text-amber-600 transition duration-300" :class="activeFaq === 1 ? 'ph-minus' : 'ph-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 1" x-transition class="px-6 pb-6 text-stone-600 text-sm leading-relaxed border-t border-stone-100 pt-4">
                        To schedule an appointment, click the "Book Online" or "Book Appointment Now" CTA. Choose your service, select a preferred specialist, select an available date, and pick a slot. Submit your notes to confirm.
                    </div>
                </div>
                <!-- FAQ 2 -->
                <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden transition">
                    <button @click="activeFaq = activeFaq === 2 ? null : 2" class="w-full text-left p-6 font-serif font-bold text-stone-900 text-base flex justify-between items-center focus:outline-none">
                        <span>Can I cancel or reschedule my appointment online?</span>
                        <i class="ph text-lg text-amber-600 transition duration-300" :class="activeFaq === 2 ? 'ph-minus' : 'ph-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 2" x-transition class="px-6 pb-6 text-stone-600 text-sm leading-relaxed border-t border-stone-100 pt-4">
                        Yes! In the Client Dashboard under the "My Bookings" tab, you will see a list of your appointments. Click the "Cancel" action on any pending or confirmed booking, and it will be updated instantly.
                    </div>
                </div>
                <!-- FAQ 3 -->
                <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden transition">
                    <button @click="activeFaq = activeFaq === 3 ? null : 3" class="w-full text-left p-6 font-serif font-bold text-stone-900 text-base flex justify-between items-center focus:outline-none">
                        <span>How does the calendar prevent double bookings?</span>
                        <i class="ph text-lg text-amber-600 transition duration-300" :class="activeFaq === 3 ? 'ph-minus' : 'ph-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 3" x-transition class="px-6 pb-6 text-stone-600 text-sm leading-relaxed border-t border-stone-100 pt-4">
                        Our scheduling system dynamically checks the database. It reviews the specialist's standard working hours for that day, cross-references any approved time-off requests, and filters out all times overlapping with existing appointments.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portal Navigation Section -->
    <section id="portals" class="py-20 sm:py-28 bg-slate-900 text-white relative overflow-hidden border-t border-stone-850">
        <div class="absolute right-0 bottom-0 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-amber-400 font-serif text-sm font-semibold tracking-wider uppercase mb-2 block">System Portals</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-white">Access Aura Portal Applications</h2>
                <p class="text-slate-400 mt-2 text-sm sm:text-base">Switch between customer-facing booking dashboard, staff calendars, or admin controls.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <!-- Client Portal -->
                <div class="p-6 bg-slate-800 border border-slate-700 rounded-3xl flex flex-col justify-between hover:border-amber-400 transition">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                            <i class="ph ph-user text-2xl"></i>
                        </div>
                        <h3 class="font-serif font-bold text-lg text-white" style="margin: 0;">Client Dashboard</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Self-schedule wellness appointments, manage bookings history, and update your client profile details.</p>
                    </div>
                    <a href="<?= $bookUrl ?>" class="mt-8 bg-slate-700 hover:bg-slate-650 text-white font-semibold text-xs py-3 px-4 rounded-xl text-center transition">
                        Open Client Portal
                    </a>
                </div>
                <!-- Staff Portal -->
                <div class="p-6 bg-slate-800 border border-slate-700 rounded-3xl flex flex-col justify-between hover:border-amber-400 transition">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                            <i class="ph ph-identification-card text-2xl"></i>
                        </div>
                        <h3 class="font-serif font-bold text-lg text-white" style="margin: 0;">Staff Portal</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Manage your personal schedules, define working shifts, request time off, and update patient appointment logs.</p>
                    </div>
                    <a href="<?= base_url('ui/staff') ?>" class="mt-8 bg-slate-700 hover:bg-slate-650 text-white font-semibold text-xs py-3 px-4 rounded-xl text-center transition">
                        Open Staff Portal
                    </a>
                </div>
                <!-- Admin Portal -->
                <div class="p-6 bg-slate-800 border border-slate-700 rounded-3xl flex flex-col justify-between hover:border-amber-400 transition">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                            <i class="ph ph-shield-star text-2xl"></i>
                        </div>
                        <h3 class="font-serif font-bold text-lg text-white" style="margin: 0;">Admin Dashboard</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">System-wide overview: audit statistics, services settings, staff accounts registry, and all appointments reports.</p>
                    </div>
                    <a href="<?= base_url('ui/admin') ?>" class="mt-8 bg-slate-700 hover:bg-slate-650 text-white font-semibold text-xs py-3 px-4 rounded-xl text-center transition">
                        Open Admin Portal
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-stone-100 border-t border-stone-200 py-10 mt-auto text-center text-xs text-stone-500">
        <div class="max-w-7xl mx-auto px-4 space-y-4">
            <div class="flex justify-center gap-8 text-stone-600 font-semibold">
                <a href="#services" class="hover:text-amber-700">Services</a>
                <a href="#specialists" class="hover:text-amber-700">Specialists</a>
                <a href="#testimonials" class="hover:text-amber-700">Testimonials</a>
                <a href="#faq" class="hover:text-amber-700">FAQ</a>
            </div>
            <p class="text-stone-400">&copy; <?= date('Y') ?> Aura Appointments. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
