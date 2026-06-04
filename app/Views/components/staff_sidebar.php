<aside class="w-64 bg-primary text-stone-100 flex flex-col hidden md:flex transition-all duration-300 z-20 shadow-xl border-r border-primary-hover" id="sidebar">
    <!-- Logo -->
    <div class="h-20 flex items-center px-8 border-b border-primary-hover/50 bg-primary">
        <i class="ph ph-calendar-check text-2xl mr-3 text-accent"></i>
        <span class="text-xl font-serif font-bold tracking-wider text-white">Staff Portal</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6">
        <ul class="space-y-1 px-3">
            <li>
                <a href="<?= base_url('ui/staff') ?>" class="flex items-center px-4 py-3 rounded-md hover:bg-primary-hover transition-colors <?= current_url() == base_url('ui/staff') ? 'bg-primary-hover border-l-4 border-accent text-white' : 'text-stone-300 border-l-4 border-transparent' ?>">
                    <i class="ph ph-squares-four text-xl mr-3 <?= current_url() == base_url('ui/staff') ? 'text-accent' : '' ?>"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('ui/staff/appointments') ?>" class="flex items-center px-4 py-3 rounded-md hover:bg-primary-hover transition-colors <?= current_url() == base_url('ui/staff/appointments') ? 'bg-primary-hover border-l-4 border-accent text-white' : 'text-stone-300 border-l-4 border-transparent' ?>">
                    <i class="ph ph-calendar text-xl mr-3 <?= current_url() == base_url('ui/staff/appointments') ? 'text-accent' : '' ?>"></i>
                    My Appointments
                </a>
            </li>
            <li>
                <a href="<?= base_url('ui/staff/schedule') ?>" class="flex items-center px-4 py-3 rounded-md hover:bg-primary-hover transition-colors <?= current_url() == base_url('ui/staff/schedule') ? 'bg-primary-hover border-l-4 border-accent text-white' : 'text-stone-300 border-l-4 border-transparent' ?>">
                    <i class="ph ph-clock text-xl mr-3 <?= current_url() == base_url('ui/staff/schedule') ? 'text-accent' : '' ?>"></i>
                    My Schedule
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Profile Area -->
    <div class="p-6 border-t border-primary-hover/50 bg-primary">
        <div class="flex items-center group cursor-pointer" @click="$dispatch('open-profile-modal')">
            <?php if(session()->get('profile_picture')): ?>
                <img src="<?= session()->get('profile_picture') ?>" alt="Profile" class="w-10 h-10 rounded-full shadow-md object-cover group-hover:ring-2 ring-accent transition">
            <?php else: ?>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('first_name') . ' ' . session()->get('last_name')) ?>&background=b4975a&color=fff" alt="User Avatar" class="w-10 h-10 rounded-full shadow-md group-hover:ring-2 ring-accent transition">
            <?php endif; ?>
            <div class="ml-3">
                <p class="text-sm font-serif font-medium text-white group-hover:text-accent transition">
                    <?= session()->get('first_name') ?? 'Staff' ?> <?= session()->get('last_name') ?? 'User' ?>
                </p>
                <a href="<?= base_url('logout') ?>" class="text-xs text-stone-400 hover:text-accent transition-colors" @click.stop>Sign out</a>
            </div>
        </div>
    </div>
</aside>
