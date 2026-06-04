<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Appointments<?= $this->endSection() ?>
<?= $this->section('header_title') ?>System Appointments<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Filters and Actions -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Search client or ID..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm">
            </div>
            
            <select class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary bg-white text-slate-700">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            
            <select class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary bg-white text-slate-700">
                <option value="">All Staff</option>
                <option value="1">Dr. Sarah Smith</option>
                <option value="2">Dr. Michael Brown</option>
            </select>
            
            <input type="date" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary bg-white text-slate-700">
        </div>
        
        <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center w-full lg:w-auto justify-center">
            <i class="ph ph-export mr-2"></i> Export CSV
        </button>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-sm">
                        <th class="py-3 px-6 font-medium border-b border-slate-200">ID</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Client Details</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Service & Staff</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Schedule</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Status</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                    <?php if (!empty($appointments) && is_array($appointments)): ?>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 font-medium text-slate-500">#<?= esc($appointment['id'] ?? 'APT-000') ?></td>
                                <td class="py-4 px-6">
                                    <div class="font-medium text-slate-900"><?= esc($appointment['client_name']) ?></div>
                                    <div class="text-xs text-slate-500"><?= esc($appointment['client_phone'] ?? '') ?></div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-medium text-slate-900"><?= esc($appointment['service_name']) ?></div>
                                    <div class="text-xs text-slate-500">with <?= esc($appointment['staff_name']) ?></div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-medium text-slate-900"><?= esc(date('M d, Y', strtotime($appointment['appointment_date']))) ?></div>
                                    <div class="text-xs text-slate-500">
                                        <?= esc(date('h:i A', strtotime($appointment['start_time']))) ?> (<?= esc($appointment['duration_minutes'] ?? '60') ?>m)
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <?php 
                                        $statusClass = 'bg-slate-100 text-slate-800 border-slate-200';
                                        $dotClass = 'bg-slate-500';
                                        switch (strtolower($appointment['status'])) {
                                            case 'confirmed': 
                                                $statusClass = 'bg-emerald-100 text-emerald-800 border-emerald-200'; 
                                                $dotClass = 'bg-emerald-500';
                                                break;
                                            case 'pending': 
                                                $statusClass = 'bg-amber-100 text-amber-800 border-amber-200'; 
                                                $dotClass = 'bg-amber-500';
                                                break;
                                            case 'completed': 
                                                $statusClass = 'bg-blue-100 text-blue-800 border-blue-200'; 
                                                $dotClass = 'bg-blue-500';
                                                break;
                                            case 'cancelled': 
                                                $statusClass = 'bg-red-100 text-red-800 border-red-200'; 
                                                $dotClass = 'bg-red-500';
                                                break;
                                        }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $statusClass ?>">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 <?= $dotClass ?>"></span>
                                        <?= esc(ucfirst($appointment['status'])) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <?php if(strtolower($appointment['status']) === 'pending'): ?>
                                        <button class="text-emerald-500 hover:text-emerald-600 transition-colors bg-emerald-50 p-1 rounded" title="Approve"><i class="ph ph-check text-lg"></i></button>
                                    <?php endif; ?>
                                    <button class="text-slate-400 hover:text-primary transition-colors p-1" title="View Details"><i class="ph ph-eye text-lg"></i></button>
                                    <?php if(in_array(strtolower($appointment['status']), ['pending', 'confirmed'])): ?>
                                        <button class="text-slate-400 hover:text-amber-500 transition-colors p-1" title="Reschedule"><i class="ph ph-calendar-blank text-lg"></i></button>
                                        <button class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Cancel"><i class="ph ph-x-circle text-lg"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                <i class="ph ph-calendar-blank text-4xl mb-3 text-slate-300"></i>
                                <p>No appointments found matching your criteria.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
            <span>Showing 1 to 2 of 45 entries</span>
            <div class="flex space-x-1">
                <button class="px-3 py-1 border border-slate-200 rounded hover:bg-slate-50 disabled:opacity-50" disabled>Prev</button>
                <button class="px-3 py-1 border border-primary bg-primary text-white rounded">1</button>
                <button class="px-3 py-1 border border-slate-200 rounded hover:bg-slate-50">2</button>
                <button class="px-3 py-1 border border-slate-200 rounded hover:bg-slate-50">3</button>
                <button class="px-3 py-1 border border-slate-200 rounded hover:bg-slate-50">Next</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
