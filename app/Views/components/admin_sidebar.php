<aside class="w-64 bg-primary text-stone-100 flex flex-col hidden md:flex transition-all duration-300 z-20 border-r border-primary-hover shadow-xl" id="sidebar">
    <!-- Logo -->
    <div class="h-20 flex items-center px-8 border-b border-primary-hover/50 bg-primary">
        <img src="<?= base_url('image/logo-3.png') ?>" alt="OmniSchedule Logo" class="h-8 w-auto">
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
        <div class="flex items-center group cursor-pointer" @click="$dispatch('open-profile-modal')">
            <?php if(session()->get('profile_picture')): ?>
                <img src="<?= session()->get('profile_picture') ?>" alt="Profile" class="w-10 h-10 rounded-full shadow-md object-cover group-hover:ring-2 ring-accent transition">
            <?php else: ?>
                <div class="w-10 h-10 rounded-full bg-accent text-white flex items-center justify-center font-serif font-bold text-sm shadow-md group-hover:bg-accent/90 transition">
                    <?= substr(session()->get('first_name') ?? 'A', 0, 1) . substr(session()->get('last_name') ?? 'D', 0, 1) ?>
                </div>
            <?php endif; ?>
            <div class="ml-3">
                <p class="text-sm font-serif font-medium text-white group-hover:text-accent transition">
                    <?= session()->get('first_name') ?? 'Admin' ?> <?= session()->get('last_name') ?? 'User' ?>
                </p>
                <a href="<?= base_url('logout') ?>" class="text-xs text-stone-400 hover:text-accent transition-colors" @click.stop>Sign out</a>
            </div>
        </div>
    </div>
</aside>
