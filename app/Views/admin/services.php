<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Service Management<?= $this->endSection() ?>
<?= $this->section('header_title') ?>Service Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="relative w-full sm:w-96">
            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            <input type="text" placeholder="Search services..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm">
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
            <i class="ph ph-plus mr-2"></i> Add Service
        </button>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($services) && is_array($services)): ?>
            <?php foreach ($services as $service): ?>
                <!-- Service Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 relative group <?= !$service['is_active'] ? 'opacity-75 grayscale' : '' ?>">
                    <div class="absolute top-4 right-4 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-8 h-8 rounded-full bg-slate-50 text-slate-500 hover:text-primary flex items-center justify-center transition-colors shadow-sm border border-slate-200">
                            <i class="ph ph-pencil-simple"></i>
                        </button>
                        <button class="w-8 h-8 rounded-full bg-slate-50 text-slate-500 hover:text-red-500 flex items-center justify-center transition-colors shadow-sm border border-slate-200">
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                    
                    <div class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-4">
                        <i class="ph ph-stethoscope text-2xl"></i>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">
                        <?= esc($service['name']) ?>
                        <?php if (!$service['is_active']): ?>
                            <span class="ml-2 text-xs font-medium text-red-500 bg-red-50 px-2 py-0.5 rounded-full">Inactive</span>
                        <?php endif; ?>
                    </h3>
                    <p class="text-sm text-slate-500 mb-4 line-clamp-2"><?= esc($service['description'] ?? 'No description provided.') ?></p>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Duration</span>
                            <span class="text-sm font-medium text-slate-700 flex items-center mt-1">
                                <i class="ph ph-clock mr-1 text-slate-400"></i> <?= esc($service['duration_minutes']) ?> mins
                            </span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Price</span>
                            <span class="text-lg font-bold text-slate-800 mt-1">$<?= esc(number_format($service['price'], 2)) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <!-- Add New Service Card (Empty state style) -->
        <button class="bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 p-6 flex flex-col items-center justify-center hover:bg-slate-100 hover:border-primary transition-all group h-full min-h-[240px]">
            <div class="w-12 h-12 rounded-full bg-white text-slate-400 group-hover:text-primary flex items-center justify-center mb-3 shadow-sm">
                <i class="ph ph-plus text-xl"></i>
            </div>
            <span class="text-slate-600 font-medium group-hover:text-primary">Create New Service</span>
        </button>
    </div>
</div>
<?= $this->endSection() ?>
