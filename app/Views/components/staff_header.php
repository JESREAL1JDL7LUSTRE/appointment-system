<header class="h-20 bg-white border-b border-stone-200 flex items-center justify-between px-8 shrink-0 z-10 shadow-sm">
    <div class="flex items-center">
        <button id="mobileMenuBtn" class="md:hidden text-stone-500 hover:text-primary mr-4 focus:outline-none">
            <i class="ph ph-list text-2xl"></i>
        </button>
        <h1 class="text-2xl font-serif text-stone-800 tracking-wide"><?= $this->renderSection('header_title') ?></h1>
    </div>
    
    <div class="flex items-center space-x-6">
        <button class="text-stone-400 hover:text-accent transition-colors relative">
            <i class="ph ph-bell text-xl"></i>
            <!-- Notification Badge (Hidden if none) -->
        </button>
    </div>
</header>
