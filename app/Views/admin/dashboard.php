<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('header_title') ?>Dashboard Overview<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Appointments -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center">
            <div class="w-14 h-14 rounded-lg bg-blue-50 flex items-center justify-center text-primary">
                <i class="ph ph-calendar-check text-3xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Appointments</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= esc($stats['total_appointments'] ?? 0) ?></h3>
                <p class="text-sm text-emerald-500 font-medium mt-1">
                    <i class="ph ph-trend-up"></i> +12% this week
                </p>
            </div>
        </div>

        <!-- Active Staff -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center">
            <div class="w-14 h-14 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i class="ph ph-users text-3xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Active Staff</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= esc($stats['active_staff_count'] ?? 0) ?></h3>
                <p class="text-sm text-slate-400 mt-1">3 on leave today</p>
            </div>
        </div>

        <!-- Total Services -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center">
            <div class="w-14 h-14 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500">
                <i class="ph ph-list-star text-3xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Services</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= esc($stats['total_services'] ?? 0) ?></h3>
                <p class="text-sm text-slate-400 mt-1">Across 4 categories</p>
            </div>
        </div>

        <!-- Today's Revenue (Optional/Conceptual) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center">
            <div class="w-14 h-14 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="ph ph-currency-circle-dollar text-3xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Today's Revenue</p>
                <h3 class="text-2xl font-bold text-slate-800">$<?= esc(number_format($stats['todays_revenue'] ?? 0, 2)) ?></h3>
                <p class="text-sm text-emerald-500 font-medium mt-1">
                    <i class="ph ph-trend-up"></i> +4.5% vs yesterday
                </p>
            </div>
        </div>
    </div>

    <!-- Recent Appointments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-slate-800">Recent Appointments</h2>
            <a href="<?= base_url('ui/admin/appointments') ?>" class="text-sm font-medium text-primary hover:text-primary-hover transition-colors">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-sm">
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Client</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Service</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Staff</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Date & Time</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                    <?php if (!empty($recent_appointments) && is_array($recent_appointments)): ?>
                        <?php foreach ($recent_appointments as $appointment): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-medium text-slate-900"><?= esc($appointment['client_name']) ?></div>
                                    <div class="text-xs text-slate-500"><?= esc($appointment['client_email'] ?? '') ?></div>
                                </td>
                                <td class="py-4 px-6"><?= esc($appointment['service_name']) ?></td>
                                <td class="py-4 px-6"><?= esc($appointment['staff_name']) ?></td>
                                <td class="py-4 px-6">
                                    <div class="font-medium"><?= esc(date('M d, Y', strtotime($appointment['appointment_date']))) ?></div>
                                    <div class="text-xs text-slate-500">
                                        <?= esc(date('h:i A', strtotime($appointment['start_time']))) ?> - <?= esc(date('h:i A', strtotime($appointment['end_time']))) ?>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <?php 
                                        $statusClass = 'bg-slate-100 text-slate-800';
                                        switch (strtolower($appointment['status'])) {
                                            case 'confirmed': $statusClass = 'bg-emerald-100 text-emerald-800'; break;
                                            case 'pending': $statusClass = 'bg-amber-100 text-amber-800'; break;
                                            case 'completed': $statusClass = 'bg-blue-100 text-blue-800'; break;
                                            case 'cancelled': $statusClass = 'bg-red-100 text-red-800'; break;
                                        }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                        <?= esc(ucfirst($appointment['status'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center text-slate-500">
                                No recent appointments found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
