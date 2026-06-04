<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Service Management<?= $this->endSection() ?>
<?= $this->section('header_title') ?>Service Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6" x-data="serviceManager()">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="relative w-full sm:w-96">
            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            <input type="text" x-model="searchQuery" placeholder="Search services..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm">
        </div>
        <button @click="openCreateModal()" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
            <i class="ph ph-plus mr-2"></i> Add Service
        </button>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($services) && is_array($services)): ?>
            <?php foreach ($services as $service): ?>
                <!-- Service Card -->
                <div x-show="matchesSearch('<?= esc(addslashes($service['name'])) ?>', '<?= esc(addslashes($service['description'])) ?>')" class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 relative group <?= !$service['is_active'] ? 'opacity-75 grayscale' : '' ?>">
                    <div class="absolute top-4 right-4 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="openEditModal(<?= htmlspecialchars(json_encode($service), ENT_QUOTES, 'UTF-8') ?>)" class="w-8 h-8 rounded-full bg-slate-50 text-slate-500 hover:text-primary flex items-center justify-center transition-colors shadow-sm border border-slate-200">
                            <i class="ph ph-pencil-simple"></i>
                        </button>
                        <button @click="deleteService(<?= $service['id'] ?>)" class="w-8 h-8 rounded-full bg-slate-50 text-slate-500 hover:text-red-500 flex items-center justify-center transition-colors shadow-sm border border-slate-200">
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
        
        <!-- Add New Service Card -->
        <button @click="openCreateModal()" class="bg-slate-50 rounded-xl border-2 border-dashed border-slate-300 p-6 flex flex-col items-center justify-center hover:bg-slate-100 hover:border-primary transition-all group h-full min-h-[240px]">
            <div class="w-12 h-12 rounded-full bg-white text-slate-400 group-hover:text-primary flex items-center justify-center mb-3 shadow-sm">
                <i class="ph ph-plus text-xl"></i>
            </div>
            <span class="text-slate-600 font-medium group-hover:text-primary">Create New Service</span>
        </button>
    </div>

    <!-- Service Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showModal" @click="closeModal()" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showModal" x-transition.scale.origin.bottom class="relative z-10 inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                    <h3 class="text-xl font-serif font-bold text-slate-800" x-text="modalMode === 'create' ? 'Add New Service' : 'Edit Service'"></h3>
                    <button @click="closeModal()" class="text-slate-400 hover:text-slate-500 focus:outline-none transition-colors">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>
                
                <form @submit.prevent="saveService()">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Service Name</label>
                            <input type="text" x-model="formData.name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                            <textarea x-model="formData.description" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Duration (Mins)</label>
                                <input type="number" x-model="formData.duration_minutes" required min="15" step="15" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Price ($)</label>
                                <input type="number" x-model="formData.price" required min="0" step="0.01" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm">
                            </div>
                        </div>

                        <div class="pt-2">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="formData.is_active" class="rounded border-slate-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-slate-700">Service is active and bookable</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary border border-transparent rounded-lg hover:bg-primary-hover focus:outline-none transition-colors shadow-sm flex items-center">
                            <span x-text="modalMode === 'create' ? 'Create Service' : 'Save Changes'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function serviceManager() {
    return {
        showModal: false,
        modalMode: 'create',
        currentServiceId: null,
        searchQuery: '',
        formData: {
            name: '',
            description: '',
            duration_minutes: 60,
            price: 100.00,
            is_active: true
        },
        
        matchesSearch(name, desc) {
            if (this.searchQuery === '') return true;
            const query = this.searchQuery.toLowerCase();
            return name.toLowerCase().includes(query) || (desc && desc.toLowerCase().includes(query));
        },
        
        openCreateModal() {
            this.modalMode = 'create';
            this.currentServiceId = null;
            this.formData = {
                name: '',
                description: '',
                duration_minutes: 60,
                price: 100.00,
                is_active: true
            };
            this.showModal = true;
        },
        
        openEditModal(service) {
            this.modalMode = 'edit';
            this.currentServiceId = service.id;
            this.formData = {
                name: service.name,
                description: service.description,
                duration_minutes: service.duration_minutes,
                price: service.price,
                is_active: service.is_active == 1
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
        },
        
        async saveService() {
            const formData = new FormData();
            for (const key in this.formData) {
                // Handle booleans for backend
                if (key === 'is_active') {
                    formData.append(key, this.formData[key] ? 1 : 0);
                } else {
                    formData.append(key, this.formData[key]);
                }
            }
            
            let url = '<?= base_url('ui/admin/services/create') ?>';
            if (this.modalMode === 'edit') {
                url = `<?= base_url('ui/admin/services/update') ?>/${this.currentServiceId}`;
            }
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                if (data.status === 'success') {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error saving service');
                }
            } catch (err) {
                console.error(err);
                alert('Network error occurred.');
            }
        },
        
        async deleteService(id) {
            if (!confirm('Are you sure you want to delete this service? This cannot be undone.')) return;
            
            try {
                const response = await fetch(`<?= base_url('ui/admin/services/delete') ?>/${id}`, {
                    method: 'POST'
                });
                
                const data = await response.json();
                if (data.status === 'success') {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error deleting service');
                }
            } catch (err) {
                console.error(err);
                alert('Network error occurred.');
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
