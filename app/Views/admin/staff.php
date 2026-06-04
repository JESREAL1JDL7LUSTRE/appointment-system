<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Staff Management<?= $this->endSection() ?>
<?= $this->section('header_title') ?>Staff Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="relative w-full sm:w-96">
            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            <input type="text" placeholder="Search staff members..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm">
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
            <i class="ph ph-plus mr-2"></i> Add Staff
        </button>
    </div>

    <!-- Staff List -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-sm">
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Name & Contact</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Title</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Status</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200">Joined</th>
                        <th class="py-3 px-6 font-medium border-b border-slate-200 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                    <?php if (!empty($staff_members) && is_array($staff_members)): ?>
                        <?php foreach ($staff_members as $staff): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-serif font-bold text-sm shadow-sm">
                                            <?= esc(substr($staff['first_name'], 0, 1) . substr($staff['last_name'], 0, 1)) ?>
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-medium text-slate-900"><?= esc($staff['first_name'] . ' ' . $staff['last_name']) ?></div>
                                            <div class="text-xs text-slate-500"><?= esc($staff['email']) ?> • <?= esc($staff['phone'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><?= esc($staff['title'] ?? 'Staff') ?></td>
                                <td class="py-4 px-6">
                                    <?php if ($staff['is_active']): ?>
                                        <?php if ($staff['is_available']): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Available
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                On Leave
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-slate-500">
                                    <?= isset($staff['created_at']) ? esc(date('M d, Y', strtotime($staff['created_at']))) : 'N/A' ?>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <button class="text-slate-400 hover:text-primary transition-colors"><i class="ph ph-pencil-simple text-lg"></i></button>
                                    <button class="text-slate-400 hover:text-red-500 transition-colors"><i class="ph ph-trash text-lg"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500">
                                <i class="ph ph-users text-4xl mb-3 text-slate-300"></i>
                                <p>No staff members found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination (Static for preview) -->
        <div class="p-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
            <span>Showing 1 to 2 of 24 entries</span>
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
