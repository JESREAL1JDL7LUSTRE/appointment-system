<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Appointments<?= $this->endSection() ?>
<?= $this->section('header_title') ?>System Appointments<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $appointmentsJson = htmlspecialchars(json_encode($appointments), ENT_QUOTES, 'UTF-8'); ?>
<div class="space-y-6" x-data="appointmentManager(<?= $appointmentsJson ?>)">
    <!-- Filters and Actions -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                <input type="text" x-model="searchQuery" placeholder="Search client or ID..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm">
            </div>
            
            <select x-model="statusFilter" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary bg-white text-slate-700">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            
            <input type="date" x-model="dateFilter" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary bg-white text-slate-700">
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
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                    <template x-for="appointment in filteredAppointments" :key="appointment.id">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-medium text-slate-500" x-text="appointment.id"></td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-slate-900" x-text="appointment.client_name"></div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-slate-900" x-text="appointment.service_name"></div>
                                <div class="text-xs text-slate-500" x-text="'with ' + appointment.staff_name"></div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-slate-900" x-text="formatDate(appointment.appointment_date)"></div>
                                <div class="text-xs text-slate-500" x-text="formatTime(appointment.start_time) + ' - ' + formatTime(appointment.end_time)">
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border" :class="getStatusClass(appointment.status)">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="getDotClass(appointment.status)"></span>
                                    <span x-text="appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)"></span>
                                </span>
                            </td>
                        </tr>
                    </template>
                    
                    <tr x-show="filteredAppointments.length === 0">
                        <td colspan="5" class="py-12 text-center text-slate-500">
                            <i class="ph ph-calendar-blank text-4xl mb-3 text-slate-300"></i>
                            <p>No appointments found matching your criteria.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
            <span x-text="'Showing ' + filteredAppointments.length + ' entries'"></span>
        </div>
    </div>
</div>

<script>
function appointmentManager(initialData) {
    return {
        appointments: initialData || [],
        searchQuery: '',
        statusFilter: '',
        dateFilter: '',
        
        get filteredAppointments() {
            return this.appointments.filter(apt => {
                let matchesSearch = true;
                if (this.searchQuery) {
                    const q = this.searchQuery.toLowerCase();
                    matchesSearch = 
                        (apt.client_name && apt.client_name.toLowerCase().includes(q)) ||
                        (apt.id && apt.id.toLowerCase().includes(q)) ||
                        (apt.staff_name && apt.staff_name.toLowerCase().includes(q));
                }
                
                let matchesStatus = true;
                if (this.statusFilter) {
                    matchesStatus = apt.status.toLowerCase() === this.statusFilter.toLowerCase();
                }
                
                let matchesDate = true;
                if (this.dateFilter) {
                    matchesDate = apt.appointment_date === this.dateFilter;
                }
                
                return matchesSearch && matchesStatus && matchesDate;
            });
        },
        
        getStatusClass(status) {
            switch(status.toLowerCase()) {
                case 'confirmed': return 'bg-emerald-100 text-emerald-800 border-emerald-200';
                case 'pending': return 'bg-amber-100 text-amber-800 border-amber-200';
                case 'completed': return 'bg-blue-100 text-blue-800 border-blue-200';
                case 'cancelled': return 'bg-red-100 text-red-800 border-red-200';
                default: return 'bg-slate-100 text-slate-800 border-slate-200';
            }
        },
        
        getDotClass(status) {
            switch(status.toLowerCase()) {
                case 'confirmed': return 'bg-emerald-500';
                case 'pending': return 'bg-amber-500';
                case 'completed': return 'bg-blue-500';
                case 'cancelled': return 'bg-red-500';
                default: return 'bg-slate-500';
            }
        },
        
        formatDate(dateString) {
            if (!dateString) return '';
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
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
        }
    }
}
</script>
<?= $this->endSection() ?>
