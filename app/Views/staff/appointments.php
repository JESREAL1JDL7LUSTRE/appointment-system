<?= $this->extend('layouts/staff') ?>

<?= $this->section('title') ?>My Appointments<?= $this->endSection() ?>
<?= $this->section('header_title') ?>My Appointments<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $appointmentsJson = htmlspecialchars(json_encode($appointments), ENT_QUOTES, 'UTF-8'); ?>
<div class="space-y-6" x-data="staffAppointmentManager(<?= $appointmentsJson ?>)">
    <!-- Filters and Actions -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                <input type="text" x-model="searchQuery" placeholder="Search client name..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
            </div>
            
            <select x-model="timeFilter" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 bg-white text-slate-700">
                <option value="upcoming">Upcoming</option>
                <option value="today">Today</option>
                <option value="this_week">This Week</option>
                <option value="past">Past Appointments</option>
                <option value="all">All Appointments</option>
            </select>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="space-y-4">
        <template x-for="appointment in filteredAppointments" :key="appointment.id">
            <!-- Appointment Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-0 overflow-hidden transition-all hover:shadow-md">
                <div class="flex flex-col md:flex-row md:items-center">
                    <!-- Status/Time Column -->
                    <div class="bg-slate-50 md:w-48 p-6 flex flex-col justify-center items-center md:border-r border-b md:border-b-0 border-slate-100">
                        <span class="text-sm font-semibold text-slate-500 uppercase tracking-widest mb-1" x-text="getDateLabel(appointment.appointment_date)"></span>
                        <span class="text-2xl font-bold text-slate-800" x-text="formatTime(appointment.start_time)"></span>
                        <span class="text-xs text-slate-500 mt-1" x-text="appointment.duration_minutes + ' mins'"></span>
                    </div>
                    
                    <!-- Details Column -->
                    <div class="p-6 flex-1">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <h3 class="text-lg font-bold text-slate-900" x-text="appointment.client_name"></h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getStatusClass(appointment.status)" x-text="capitalize(appointment.status)">
                                    </span>
                                </div>
                                <p class="text-sm font-medium text-blue-600 mb-1" x-text="appointment.service_name"></p>
                                <p class="text-sm text-slate-600">
                                    <template x-if="appointment.client_notes">
                                        <span><span class="font-medium">Client Notes:</span> <span x-text="appointment.client_notes"></span></span>
                                    </template>
                                    <template x-if="!appointment.client_notes">
                                        <span class="italic text-slate-400">No special notes provided.</span>
                                    </template>
                                </p>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex sm:flex-col gap-2 shrink-0">
                                <template x-if="appointment.status === 'confirmed'">
                                    <button @click="updateStatus(appointment.raw_id, 'completed')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full flex items-center justify-center">
                                        <i class="ph ph-check-circle mr-2"></i> Complete
                                    </button>
                                </template>
                                <template x-if="appointment.status === 'pending'">
                                    <button @click="updateStatus(appointment.raw_id, 'confirmed')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full flex items-center justify-center">
                                        <i class="ph ph-check mr-2"></i> Confirm
                                    </button>
                                </template>
                                <template x-if="appointment.status === 'confirmed' || appointment.status === 'pending'">
                                    <button @click="updateStatus(appointment.raw_id, 'cancelled')" class="bg-white border border-red-200 text-red-600 hover:bg-red-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full">
                                        Cancel
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        
        <div x-show="filteredAppointments.length === 0" class="bg-white rounded-xl shadow-sm border border-slate-100 p-12 text-center" style="display: none;">
            <i class="ph ph-calendar-blank text-5xl text-slate-300 mb-4"></i>
            <h3 class="text-lg font-bold text-slate-700 mb-2">No appointments found</h3>
            <p class="text-slate-500">You don't have any appointments matching the current filters.</p>
        </div>
    </div>
</div>

<script>
function staffAppointmentManager(initialData) {
    return {
        appointments: initialData || [],
        searchQuery: '',
        timeFilter: 'upcoming',
        
        get filteredAppointments() {
            const today = new Date();
            today.setHours(0,0,0,0);
            
            const nextWeek = new Date(today);
            nextWeek.setDate(nextWeek.getDate() + 7);
            
            return this.appointments.filter(apt => {
                let matchesSearch = true;
                if (this.searchQuery) {
                    const q = this.searchQuery.toLowerCase();
                    matchesSearch = apt.client_name && apt.client_name.toLowerCase().includes(q);
                }
                
                let matchesTime = true;
                const aptDateStr = apt.appointment_date;
                const aptDate = new Date(aptDateStr + 'T00:00:00'); 
                
                switch(this.timeFilter) {
                    case 'today':
                        matchesTime = aptDate.getTime() === today.getTime();
                        break;
                    case 'this_week':
                        matchesTime = aptDate >= today && aptDate <= nextWeek;
                        break;
                    case 'past':
                        matchesTime = aptDate < today;
                        break;
                    case 'upcoming':
                        matchesTime = aptDate >= today && apt.status !== 'completed' && apt.status !== 'cancelled';
                        break;
                    case 'all':
                        matchesTime = true;
                        break;
                }
                
                return matchesSearch && matchesTime;
            });
        },
        
        getStatusClass(status) {
            switch(status.toLowerCase()) {
                case 'confirmed': return 'bg-emerald-100 text-emerald-800';
                case 'pending': return 'bg-amber-100 text-amber-800';
                case 'completed': return 'bg-blue-100 text-blue-800';
                case 'cancelled': return 'bg-red-100 text-red-800';
                default: return 'bg-slate-100 text-slate-800';
            }
        },
        
        capitalize(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        
        getDateLabel(dateString) {
            if (!dateString) return '';
            const aptDate = new Date(dateString + 'T00:00:00');
            const today = new Date();
            today.setHours(0,0,0,0);
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            if (aptDate.getTime() === today.getTime()) return 'Today';
            if (aptDate.getTime() === tomorrow.getTime()) return 'Tomorrow';
            
            const options = { month: 'short', day: 'numeric' };
            return aptDate.toLocaleDateString('en-US', options);
        },
        
        formatTime(timeString) {
            if (!timeString) return '';
            const parts = timeString.split(':');
            if (parts.length >= 2) {
                let hours = parseInt(parts[0], 10);
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                return hours + ':' + parts[1] + ' ' + ampm;
            }
            return timeString;
        },
        
        async updateStatus(id, status) {
            if (status === 'cancelled' && !confirm('Are you sure you want to cancel this appointment?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('status', status);
            
            try {
                const response = await fetch(`<?= base_url('staff/appointments/update-status') ?>/${id}`, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.status === 'success') {
                    // Update locally
                    const index = this.appointments.findIndex(a => a.raw_id === id);
                    if (index !== -1) {
                        this.appointments[index].status = status;
                    }
                } else {
                    alert(data.message || 'Error updating status');
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
