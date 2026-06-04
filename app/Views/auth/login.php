<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AURA - Client Portal Authentication</title>
    <!-- Tailwind CSS -->
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-stone-100 text-stone-850 antialiased font-sans min-h-screen flex items-center justify-center p-4 sm:p-6" x-data="authPage()">

    <!-- Toast Notification System -->
    <div class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-md w-full">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" 
                 x-transition:enter="transition ease-out duration-300 transform translate-x-4 opacity-0"
                 x-transition:enter-start="transform translate-x-4 opacity-0"
                 x-transition:enter-end="transform translate-x-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform translate-x-4 opacity-0"
                 class="p-4 rounded-xl border shadow-lg flex items-start gap-3 w-full"
                 :class="{
                     'bg-green-50 border-green-200 text-green-800': toast.type === 'success',
                     'bg-red-50 border-red-200 text-red-800': toast.type === 'error',
                     'bg-blue-50 border-blue-200 text-blue-800': toast.type === 'info'
                 }">
                <div class="mt-0.5">
                    <i class="ph-bold" :class="{
                        'ph-check-circle text-green-600 text-xl': toast.type === 'success',
                        'ph-x-circle text-red-600 text-xl': toast.type === 'error',
                        'ph-info text-blue-600 text-xl': toast.type === 'info'
                    }"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm" x-text="toast.message"></p>
                </div>
                <button @click="dismissToast(toast.id)" class="text-stone-400 hover:text-stone-600">
                    <i class="ph ph-x"></i>
                </button>
            </div>
        </template>
    </div>

    <!-- Dual Panel Login Container -->
    <div class="bg-white rounded-3xl shadow-2xl border border-stone-200 overflow-hidden max-w-5xl w-full grid grid-cols-1 md:grid-cols-12 min-h-[600px]">
        
        <!-- Left Panel: Luxury Brand Info -->
        <div class="md:col-span-5 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white p-8 sm:p-12 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-24 -top-24 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-24 -bottom-24 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 space-y-8">
                <!-- Logo -->
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2 w-fit">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center">
                        <i class="ph-bold ph-sparkle text-amber-400 text-2xl"></i>
                    </div>
                    <span class="font-serif text-2xl font-bold tracking-wider text-amber-400">AURA</span>
                </a>
                
                <div class="space-y-4">
                    <h2 class="text-3xl font-serif font-bold leading-tight">Exceptional Care, Timeless Elegance</h2>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Access our exclusive client booking workspace to coordinate your appointments, view historical session logs, and manage your contact settings.
                    </p>
                </div>
            </div>

            <div class="relative z-10 pt-12">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-amber-400 hover:text-amber-300 transition">
                    <i class="ph-bold ph-arrow-left"></i> Back to Main Landing Page
                </a>
            </div>
        </div>

        <!-- Right Panel: Auth Forms -->
        <div class="md:col-span-7 p-8 sm:p-12 flex flex-col justify-between bg-white">
            <div class="space-y-8">
                
                <!-- Flash Messages -->
                <?php if ($info): ?>
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-2xl flex items-start gap-2.5 text-sm font-medium">
                        <i class="ph-bold ph-info text-blue-600 text-lg mt-0.5"></i>
                        <span><?= htmlspecialchars($info) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Tab Headers -->
                <div class="flex border-b border-stone-200 pb-0.5 gap-6">
                    <button @click="activeTab = 'login'" 
                            :class="activeTab === 'login' ? 'border-amber-600 text-stone-900 font-bold border-b-2' : 'text-stone-400 font-semibold hover:text-stone-700'"
                            class="pb-3 text-base transition-all focus:outline-none">
                        Client Login
                    </button>
                    <button @click="activeTab = 'register'" 
                            :class="activeTab === 'register' ? 'border-amber-600 text-stone-900 font-bold border-b-2' : 'text-stone-400 font-semibold hover:text-stone-700'"
                            class="pb-3 text-base transition-all focus:outline-none">
                        Register Account
                    </button>
                </div>

                <!-- LOGIN TAB -->
                <div x-show="activeTab === 'login'" x-transition class="space-y-6">
                    <form @submit.prevent="login()" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider">Email Address</label>
                            <input type="email" x-model="loginForm.email" required 
                                   placeholder="you@example.com"
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-950 focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center">
                                <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider">Password</label>
                            </div>
                            <input type="password" x-model="loginForm.password" required 
                                   placeholder="••••••••"
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-950 focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                        
                        <button type="submit" :disabled="loginSubmitting" 
                                class="w-full bg-amber-500 hover:bg-amber-600 text-slate-955 font-bold py-3.5 px-4 rounded-xl transition shadow-lg shadow-amber-500/10 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 mt-6">
                            <span x-show="!loginSubmitting">Sign In</span>
                            <span x-show="loginSubmitting">Signing In...</span>
                            <div x-show="loginSubmitting" class="w-4 h-4 border-2 border-slate-955 border-t-transparent rounded-full animate-spin"></div>
                        </button>
                    </form>
                </div>

                <!-- REGISTER TAB -->
                <div x-show="activeTab === 'register'" x-transition x-cloak class="space-y-6">
                    <form @submit.prevent="register()" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider">First Name</label>
                                <input type="text" x-model="registerForm.first_name" required 
                                       class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-950 focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider">Last Name</label>
                                <input type="text" x-model="registerForm.last_name" required 
                                       class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-950 focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider">Email Address</label>
                            <input type="email" x-model="registerForm.email" required 
                                   placeholder="name@example.com"
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-950 focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider">Phone Number</label>
                                <input type="text" x-model="registerForm.phone" 
                                       placeholder="+1 555-0198"
                                       class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-950 focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider">Password</label>
                                <input type="password" x-model="registerForm.password" required 
                                       placeholder="••••••••"
                                       class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-stone-955 focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>
                        </div>
                        
                        <button type="submit" :disabled="registerSubmitting" 
                                class="w-full bg-amber-500 hover:bg-amber-600 text-slate-955 font-bold py-3.5 px-4 rounded-xl transition shadow-lg shadow-amber-500/10 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 mt-6">
                            <span x-show="!registerSubmitting">Register Account</span>
                            <span x-show="registerSubmitting">Registering...</span>
                            <div x-show="registerSubmitting" class="w-4 h-4 border-2 border-slate-955 border-t-transparent rounded-full animate-spin"></div>
                        </button>
                    </form>
                </div>

            </div>

            <!-- DEMO LOGINS PANEL (For ease of OJT Testing / Evaluation) -->
            <?php if (!empty($clients)): ?>
                <div class="border-t border-stone-200 mt-10 pt-6">
                    <span class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-3">Quick Demo Logins (For Testing)</span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <?php 
                        // Show first 6 clients to avoid cluttered UI
                        $demoClients = array_slice($clients, 0, 6);
                        foreach ($demoClients as $dc): 
                        ?>
                            <button @click="quickLogin('<?= $dc['id'] ?>', '<?= htmlspecialchars($dc['first_name'] . ' ' . $dc['last_name']) ?>')" 
                                    class="p-2.5 bg-stone-50 hover:bg-amber-500/10 border border-stone-200 hover:border-amber-500 rounded-xl text-left text-xs transition duration-200 group">
                                <span class="font-bold text-stone-900 block group-hover:text-amber-800 truncate"><?= htmlspecialchars($dc['first_name'] . ' ' . $dc['last_name']) ?></span>
                                <span class="text-[10px] text-stone-400 truncate block">Click to sign in</span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>

    <!-- Alpine.js Page Logic -->
    <script>
        function authPage() {
            return {
                activeTab: 'login',
                toasts: [],
                toastId: 0,

                loginSubmitting: false,
                loginForm: {
                    email: '',
                    password: ''
                },

                registerSubmitting: false,
                registerForm: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    password: ''
                },

                showToast(message, type = 'success') {
                    const id = this.toastId++;
                    this.toasts.push({ id, message, type, visible: true });
                    setTimeout(() => this.dismissToast(id), 5000);
                },

                dismissToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index !== -1) {
                        this.toasts[index].visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300);
                    }
                },

                login() {
                    this.loginSubmitting = true;
                    const params = new URLSearchParams(this.loginForm);
                    
                    fetch('<?= base_url("login") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: params.toString()
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.loginSubmitting = false;
                        if (data.status === 'success') {
                            this.showToast(data.message);
                            setTimeout(() => {
                                window.location.href = '<?= base_url("dashboard") ?>';
                            }, 800);
                        } else {
                            this.showToast(data.message, 'error');
                        }
                    });
                },

                register() {
                    this.registerSubmitting = true;
                    const params = new URLSearchParams(this.registerForm);
                    
                    fetch('<?= base_url("register") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: params.toString()
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.registerSubmitting = false;
                        if (data.status === 'success') {
                            this.showToast(data.message);
                            setTimeout(() => {
                                window.location.href = '<?= base_url("dashboard") ?>';
                            }, 800);
                        } else {
                            this.showToast(data.message, 'error');
                        }
                    });
                },

                quickLogin(id, name) {
                    fetch('<?= base_url("client/switch") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `client_id=${id}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            this.showToast(`Logged in successfully as ${name}!`);
                            setTimeout(() => {
                                window.location.href = '<?= base_url("dashboard") ?>';
                            }, 800);
                        } else {
                            this.showToast(data.message, 'error');
                        }
                    });
                }
            };
        }
    </script>

</body>
</html>
