<aside class="w-64 bg-primary text-stone-100 flex flex-col hidden md:flex transition-all duration-300 z-20 border-r border-primary-hover shadow-xl" id="sidebar">
    <!-- Logo -->
    <div class="h-20 flex items-center px-8 border-b border-primary-hover/50 bg-primary">
        <i class="ph ph-calendar-check text-2xl mr-3 text-accent"></i>
        <span class="text-xl font-serif font-bold tracking-wider text-white">AppointSys</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <li>
                <a href="<?= base_url('ui/admin') ?>" class="flex items-center px-4 py-3 rounded-md hover:bg-primary-hover transition-colors <?= current_url() == base_url('ui/admin') ? 'bg-primary-hover border-l-4 border-accent text-white' : 'text-stone-300 border-l-4 border-transparent' ?>">
                    <i class="ph ph-squares-four text-xl mr-3 <?= current_url() == base_url('ui/admin') ? 'text-accent' : '' ?>"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('ui/admin/appointments') ?>" class="flex items-center px-4 py-3 rounded-md hover:bg-primary-hover transition-colors <?= current_url() == base_url('ui/admin/appointments') ? 'bg-primary-hover border-l-4 border-accent text-white' : 'text-stone-300 border-l-4 border-transparent' ?>">
                    <i class="ph ph-calendar text-xl mr-3 <?= current_url() == base_url('ui/admin/appointments') ? 'text-accent' : '' ?>"></i>
                    Appointments
                </a>
            </li>
            <li class="pt-6 pb-2">
                <div class="px-4 text-xs font-serif font-semibold text-stone-400 uppercase tracking-widest">Management</div>
            </li>
            <li>
                <a href="<?= base_url('ui/admin/staff') ?>" class="flex items-center px-4 py-3 rounded-md hover:bg-primary-hover transition-colors <?= current_url() == base_url('ui/admin/staff') ? 'bg-primary-hover border-l-4 border-accent text-white' : 'text-stone-300 border-l-4 border-transparent' ?>">
                    <i class="ph ph-users text-xl mr-3 <?= current_url() == base_url('ui/admin/staff') ? 'text-accent' : '' ?>"></i>
                    Staff
                </a>
            </li>
            <li>
                <a href="<?= base_url('ui/admin/services') ?>" class="flex items-center px-4 py-3 rounded-md hover:bg-primary-hover transition-colors <?= current_url() == base_url('ui/admin/services') ? 'bg-primary-hover border-l-4 border-accent text-white' : 'text-stone-300 border-l-4 border-transparent' ?>">
                    <i class="ph ph-list-dashes text-xl mr-3 <?= current_url() == base_url('ui/admin/services') ? 'text-accent' : '' ?>"></i>
                    Services
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Profile Area -->
    <div class="p-6 border-t border-primary-hover/50 bg-primary">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-accent text-white flex items-center justify-center font-serif font-bold text-sm shadow-md">
                AD
            </div>
            <div class="ml-3">
                <p class="text-sm font-serif font-medium text-white">Admin User</p>
                <a href="#" class="text-xs text-stone-400 hover:text-accent transition-colors">Sign out</a>
            </div>
        </div>
    </div>
</aside>
