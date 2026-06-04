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
            <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-accent"></span>
            </span>
        </button>
    </div>
</header>
