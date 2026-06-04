<?= $this->extend('layouts/staff') ?>

<?= $this->section('title') ?>My Appointments<?= $this->endSection() ?>
<?= $this->section('header_title') ?>My Appointments<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Filters and Actions -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Search client name..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
            </div>
            
            <select class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 bg-white text-slate-700">
                <option value="">Upcoming</option>
                <option value="today">Today</option>
                <option value="this_week">This Week</option>
                <option value="past">Past Appointments</option>
            </select>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="space-y-4">
        <?php if (!empty($appointments) && is_array($appointments)): ?>
            <?php foreach ($appointments as $appointment): ?>
                <?php 
                    $statusColor = 'slate';
                    switch (strtolower($appointment['status'])) {
                        case 'confirmed': $statusColor = 'emerald'; break;
                        case 'pending': $statusColor = 'amber'; break;
                        case 'completed': $statusColor = 'blue'; break;
                        case 'cancelled': $statusColor = 'red'; break;
                    }

                    // Simple logic to show 'Today', 'Tomorrow', or Date
                    $appDate = strtotime($appointment['appointment_date']);
                    $today = strtotime('today');
                    $tomorrow = strtotime('tomorrow');
                    
                    if ($appDate == $today) {
                        $dateLabel = 'Today';
                    } elseif ($appDate == $tomorrow) {
                        $dateLabel = 'Tomorrow';
                    } else {
                        $dateLabel = date('M d', $appDate);
                    }
                ?>
                <!-- Appointment Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-0 overflow-hidden">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <!-- Status/Time Column -->
                        <div class="bg-slate-50 md:w-48 p-6 flex flex-col justify-center items-center md:border-r border-b md:border-b-0 border-slate-100">
                            <span class="text-sm font-semibold text-slate-500 uppercase tracking-widest mb-1"><?= esc($dateLabel) ?></span>
                            <span class="text-2xl font-bold text-slate-800"><?= esc(date('h:i A', strtotime($appointment['start_time']))) ?></span>
                            <span class="text-xs text-slate-500 mt-1"><?= esc($appointment['duration_minutes'] ?? '60') ?> mins</span>
                        </div>
                        
                        <!-- Details Column -->
                        <div class="p-6 flex-1">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-lg font-bold text-slate-900"><?= esc($appointment['client_name']) ?></h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                            <?= esc(ucfirst($appointment['status'])) ?>
                                        </span>
                                    </div>
                                    <p class="text-sm font-medium text-blue-600 mb-1"><?= esc($appointment['service_name']) ?></p>
                                    <p class="text-sm text-slate-600">
                                        <?php if (!empty($appointment['client_notes'])): ?>
                                            <span class="font-medium">Client Notes:</span> <?= esc($appointment['client_notes']) ?>
                                        <?php else: ?>
                                            <span class="italic text-slate-400">No special notes provided.</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex sm:flex-col gap-2 shrink-0">
                                    <?php if (strtolower($appointment['status']) === 'confirmed'): ?>
                                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full">
                                            Start Session
                                        </button>
                                    <?php elseif (strtolower($appointment['status']) === 'pending'): ?>
                                        <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full">
                                            Confirm
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full">
                                        <?= strtolower($appointment['status']) === 'pending' || strtolower($appointment['status']) === 'confirmed' ? 'Reschedule' : 'View Details' ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-12 text-center">
                <i class="ph ph-calendar-blank text-5xl text-slate-300 mb-4"></i>
                <h3 class="text-lg font-bold text-slate-700 mb-2">No appointments found</h3>
                <p class="text-slate-500">You don't have any appointments matching the current filters.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
