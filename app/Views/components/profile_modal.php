<!-- Profile Settings Modal Component -->
<div x-data="profileModal()" @open-profile-modal.window="openModal()" x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden" @click.away="closeModal()">
        <div class="px-6 py-4 border-b border-stone-100 flex justify-between items-center bg-stone-50">
            <h3 class="font-serif font-bold text-lg text-stone-900">Edit Profile</h3>
            <button @click="closeModal()" class="text-stone-400 hover:text-stone-600 transition"><i class="ph-bold ph-x text-xl"></i></button>
        </div>
        <form @submit.prevent="saveProfile()" class="p-6 space-y-5" enctype="multipart/form-data">
            <div class="flex items-center gap-4">
                <img :src="profileForm.profile_picture || 'https://ui-avatars.com/api/?name=User&background=fef3c7&color=92400e'" class="w-16 h-16 rounded-full object-cover border border-stone-200">
                <input type="file" x-ref="profilePic" accept="image/*" class="text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
            </div>
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-stone-700 uppercase">First Name</label>
                <input type="text" x-model="profileForm.first_name" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-stone-700 uppercase">Last Name</label>
                <input type="text" x-model="profileForm.last_name" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-stone-700 uppercase">Email</label>
                <input type="email" x-model="profileForm.email" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-stone-700 uppercase">Phone</label>
                <input type="text" x-model="profileForm.phone" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>

            <div x-show="message" :class="messageType === 'success' ? 'text-green-600 bg-green-50 border-green-200' : 'text-red-600 bg-red-50 border-red-200'" class="px-4 py-2 rounded-lg text-sm border font-medium text-center" x-text="message"></div>

            <button type="submit" :disabled="loading" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-955 font-bold py-3 rounded-xl transition flex justify-center items-center gap-2">
                <span x-show="!loading">Save Profile</span>
                <span x-show="loading">Saving...</span>
                <div x-show="loading" class="w-4 h-4 border-2 border-slate-955 border-t-transparent rounded-full animate-spin"></div>
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('profileModal', () => ({
        isOpen: false,
        loading: false,
        message: '',
        messageType: '',
        profileForm: {
            first_name: '<?= session()->get("first_name") ?? "" ?>',
            last_name: '<?= session()->get("last_name") ?? "" ?>',
            email: '<?= session()->get("email") ?? "" ?>',
            phone: '<?= session()->get("phone") ?? "" ?>',
            profile_picture: '<?= session()->get("profile_picture") ?? "" ?>'
        },
        openModal() { this.isOpen = true; this.message = ''; },
        closeModal() { this.isOpen = false; },
        saveProfile() {
            this.loading = true;
            this.message = '';
            const formData = new FormData();
            formData.append('first_name', this.profileForm.first_name);
            formData.append('last_name', this.profileForm.last_name);
            formData.append('email', this.profileForm.email);
            formData.append('phone', this.profileForm.phone);
            if (this.$refs.profilePic.files.length > 0) {
                formData.append('profile_picture', this.$refs.profilePic.files[0]);
            }
            fetch('<?= base_url("update-profile") ?>', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                this.loading = false;
                this.message = data.message;
                this.messageType = data.status;
                if (data.status === 'success') {
                    setTimeout(() => window.location.reload(), 1000);
                }
            }).catch(e => {
                this.loading = false;
                this.message = 'Network error.';
                this.messageType = 'error';
            });
        }
    }))
})
</script>
