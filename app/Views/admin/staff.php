<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Staff Management<?= $this->endSection() ?>
<?= $this->section('header_title') ?>Staff Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6" x-data="staffManager()">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="relative w-full sm:w-96">
            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            <input type="text" x-model="searchQuery" placeholder="Search staff members..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm">
        </div>
        <button @click="openCreateModal()" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
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
                            <tr class="hover:bg-slate-50 transition-colors" x-show="matchesSearch('<?= esc(addslashes($staff['first_name'])) ?>', '<?= esc(addslashes($staff['last_name'])) ?>', '<?= esc(addslashes($staff['email'])) ?>')">
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
                                    <button @click="openEditModal(<?= htmlspecialchars(json_encode($staff), ENT_QUOTES, 'UTF-8') ?>)" class="text-slate-400 hover:text-primary transition-colors p-1"><i class="ph ph-pencil-simple text-lg"></i></button>
                                    <button @click="deleteStaff(<?= $staff['id'] ?>)" class="text-slate-400 hover:text-red-500 transition-colors p-1"><i class="ph ph-trash text-lg"></i></button>
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
    </div>

    <!-- Staff Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" @click="closeModal()" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" x-transition.scale.origin.bottom class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                    <h3 class="text-xl font-serif font-bold text-slate-800" x-text="modalMode === 'create' ? 'Add New Staff' : 'Edit Staff'"></h3>
                    <button @click="closeModal()" class="text-slate-400 hover:text-slate-500 focus:outline-none transition-colors">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>
                
                <form @submit.prevent="saveStaff()">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">First Name</label>
                                <input type="text" x-model="formData.first_name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Last Name</label>
                                <input type="text" x-model="formData.last_name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                                <input type="email" x-model="formData.email" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                                <input type="text" x-model="formData.phone" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Job Title</label>
                                <input type="text" x-model="formData.title" placeholder="e.g. Senior Therapist" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Password <span x-show="modalMode === 'edit'" class="text-xs text-slate-400 font-normal">(Leave blank to keep current)</span></label>
                                <input type="password" x-model="formData.password" :required="modalMode === 'create'" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm">
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col gap-2">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="formData.is_active" class="rounded border-slate-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-slate-700">Account is Active (Can Login)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" x-model="formData.is_available" class="rounded border-slate-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-slate-700">Available for Bookings</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary border border-transparent rounded-lg hover:bg-primary-hover focus:outline-none transition-colors shadow-sm flex items-center">
                            <span x-text="modalMode === 'create' ? 'Create Staff' : 'Save Changes'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function staffManager() {
    return {
        showModal: false,
        modalMode: 'create',
        currentStaffId: null,
        searchQuery: '',
        formData: {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            title: '',
            password: '',
            is_active: true,
            is_available: true
        },
        
        matchesSearch(first, last, email) {
            if (this.searchQuery === '') return true;
            const query = this.searchQuery.toLowerCase();
            const fullName = `${first} ${last}`.toLowerCase();
            return fullName.includes(query) || (email && email.toLowerCase().includes(query));
        },
        
        openCreateModal() {
            this.modalMode = 'create';
            this.currentStaffId = null;
            this.formData = {
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                title: '',
                password: '',
                is_active: true,
                is_available: true
            };
            this.showModal = true;
        },
        
        openEditModal(staff) {
            this.modalMode = 'edit';
            this.currentStaffId = staff.id;
            this.formData = {
                first_name: staff.first_name,
                last_name: staff.last_name,
                email: staff.email,
                phone: staff.phone,
                title: staff.title,
                password: '',
                is_active: staff.is_active == 1,
                is_available: staff.is_available == 1
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
        },
        
        async saveStaff() {
            const formData = new FormData();
            for (const key in this.formData) {
                if (key === 'is_active' || key === 'is_available') {
                    formData.append(key, this.formData[key] ? 1 : 0);
                } else {
                    formData.append(key, this.formData[key]);
                }
            }
            
            let url = '<?= base_url('admin/staff/create') ?>';
            if (this.modalMode === 'edit') {
                url = `<?= base_url('admin/staff/update') ?>/${this.currentStaffId}`;
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
                    alert(data.message || 'Error saving staff member');
                }
            } catch (err) {
                console.error(err);
                alert('Network error occurred.');
            }
        },
        
        async deleteStaff(id) {
            if (!confirm('Are you sure you want to deactivate this staff member?')) return;
            
            try {
                const response = await fetch(`<?= base_url('admin/staff/delete') ?>/${id}`, {
                    method: 'POST'
                });
                
                const data = await response.json();
                if (data.status === 'success') {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error deactivating staff member');
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
