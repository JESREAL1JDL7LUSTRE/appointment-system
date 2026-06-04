<?= $this->extend('layouts/staff') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('header_title') ?>My Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-slate-800 rounded-xl p-6 text-white shadow-sm flex flex-col md:flex-row justify-between items-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <!-- Decorative background pattern could go here -->
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>
        
        <div class="relative z-10 text-center md:text-left mb-4 md:mb-0">
            <h2 class="text-2xl font-bold mb-1">Welcome back, <?= esc(session('user_first_name') ?? 'Staff') ?>!</h2>
            <p class="text-slate-300">You have <?= esc($stats['today_appointments'] ?? 0) ?> appointments scheduled for today.</p>
        </div>
        <div class="relative z-10 flex space-x-3">
            <a href="<?= base_url('ui/staff/appointments') ?>" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                View Schedule
            </a>
            <button class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm border border-slate-600">
                Request Time Off
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Today's Schedule Timeline -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Today's Agenda</h3>
                <span class="text-sm text-slate-500 font-medium"><?= esc(date('M d, Y')) ?></span>
            </div>
            
            <div class="relative border-l-2 border-slate-200 ml-4 space-y-8 pb-4">
                <?php if (!empty($today_appointments) && is_array($today_appointments)): ?>
                    <?php 
                    $isFirst = true;
                    foreach ($today_appointments as $appointment): 
                        // Determine border color based on status or if it's the next appointment
                        $colorClass = $isFirst ? 'blue' : 'emerald';
                        $isFirst = false;
                    ?>
                        <!-- Appointment Item -->
                        <div class="relative pl-6">
                            <span class="absolute -left-[11px] top-1 w-5 h-5 rounded-full border-4 border-white bg-<?= $colorClass ?>-500"></span>
                            <div class="<?= $colorClass === 'blue' ? 'bg-blue-50 border-blue-100' : 'bg-white border-slate-200 shadow-sm' ?> border rounded-lg p-4 relative">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-bold <?= $colorClass === 'blue' ? 'text-blue-900' : 'text-slate-800' ?>"><?= esc($appointment['client_name']) ?></h4>
                                        <p class="text-sm <?= $colorClass === 'blue' ? 'text-blue-700' : 'text-slate-500' ?>"><?= esc($appointment['service_name']) ?></p>
                                    </div>
                                    <span class="text-sm font-semibold <?= $colorClass === 'blue' ? 'text-blue-800 bg-white' : 'text-slate-700 bg-slate-100' ?> px-2 py-1 rounded shadow-sm">
                                        <?= esc(date('h:i A', strtotime($appointment['start_time']))) ?>
                                    </span>
                                </div>
                                <?php if (!empty($appointment['client_notes'])): ?>
                                    <p class="text-sm <?= $colorClass === 'blue' ? 'text-blue-600 border-blue-200' : 'text-slate-600 border-slate-100' ?> line-clamp-2 mt-2 border-t pt-2">
                                        <span class="font-medium">Note:</span> <?= esc($appointment['client_notes']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="relative pl-6 py-4">
                        <p class="text-slate-500">No appointments scheduled for today.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Weekly Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">This Week</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <div class="flex items-center text-slate-600">
                            <i class="ph ph-check-circle text-emerald-500 mr-2 text-lg"></i>
                            <span class="text-sm font-medium">Completed this month</span>
                        </div>
                        <span class="font-bold text-slate-800"><?= esc($stats['completed_month'] ?? 0) ?></span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <div class="flex items-center text-slate-600">
                            <i class="ph ph-calendar text-blue-500 mr-2 text-lg"></i>
                            <span class="text-sm font-medium">Upcoming 7 Days</span>
                        </div>
                        <span class="font-bold text-slate-800"><?= esc($stats['upcoming_week'] ?? 0) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center text-slate-600">
                            <i class="ph ph-x-circle text-red-500 mr-2 text-lg"></i>
                            <span class="text-sm font-medium">Cancellations</span>
                        </div>
                        <span class="font-bold text-slate-800"><?= esc($stats['cancellations_this_week'] ?? 0) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
