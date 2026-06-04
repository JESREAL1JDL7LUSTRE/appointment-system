<?= $this->extend('layouts/staff') ?>

<?= $this->section('title') ?>My Schedule<?= $this->endSection() ?>
<?= $this->section('header_title') ?>Manage Schedule<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Working Hours -->
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Weekly Working Hours</h3>
                    <p class="text-sm text-slate-500">Define your regular availability.</p>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Save Changes
                </button>
            </div>
            <div class="p-6">
                <form action="<?= base_url('ui/staff/schedule/update') ?>" method="post" class="space-y-4">
                    <?php 
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach ($days as $index => $day): 
                            // Try to find the setting for this day, or default to not working
                            $setting = null;
                            if (isset($working_hours) && is_array($working_hours)) {
                                foreach ($working_hours as $wh) {
                                    if ($wh['day_of_week'] == ($index + 1)) {
                                        $setting = $wh;
                                        break;
                                    }
                                }
                            }
                            
                            $isWorking = $setting ? (bool)$setting['is_working'] : ($index < 5); // default Mon-Fri active
                            $startTime = $setting ? date('H:i', strtotime($setting['start_time'])) : '09:00';
                            $endTime = $setting ? date('H:i', strtotime($setting['end_time'])) : '17:00';
                    ?>
                        <div class="flex items-center justify-between p-4 rounded-lg border <?= $isWorking ? 'border-slate-200 bg-white' : 'border-slate-100 bg-slate-50 opacity-60' ?>">
                            <div class="flex items-center w-1/3">
                                <input type="checkbox" name="schedule[<?= $index + 1 ?>][is_working]" value="1" <?= $isWorking ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                <span class="ml-3 font-medium text-slate-700"><?= esc($day) ?></span>
                            </div>
                            <div class="flex items-center space-x-2 w-2/3 justify-end">
                                <?php if ($isWorking): ?>
                                    <input type="time" name="schedule[<?= $index + 1 ?>][start_time]" value="<?= esc($startTime) ?>" class="border border-slate-300 rounded px-3 py-1.5 text-sm text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                    <span class="text-slate-400">to</span>
                                    <input type="time" name="schedule[<?= $index + 1 ?>][end_time]" value="<?= esc($endTime) ?>" class="border border-slate-300 rounded px-3 py-1.5 text-sm text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                <?php else: ?>
                                    <span class="text-sm font-medium text-slate-500 bg-slate-200 px-3 py-1 rounded">Unavailable</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Time Off Requests -->
    <div class="xl:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-slate-800">Time Off</h3>
                <button class="text-sm text-blue-600 font-medium hover:text-blue-700 transition-colors">+ Request</button>
            </div>
            <div class="p-0">
                <ul class="divide-y divide-slate-100">
                    <?php if (!empty($time_off_requests) && is_array($time_off_requests)): ?>
                        <?php foreach ($time_off_requests as $request): ?>
                            <?php 
                                $statusColor = 'slate';
                                switch(strtolower($request['status'])) {
                                    case 'approved': $statusColor = 'emerald'; break;
                                    case 'pending': $statusColor = 'amber'; break;
                                    case 'rejected': $statusColor = 'red'; break;
                                }
                                
                                $dateDisplay = date('M d, Y', strtotime($request['start_date']));
                                if ($request['start_date'] != $request['end_date']) {
                                    $dateDisplay .= ' - ' . date('M d, Y', strtotime($request['end_date']));
                                }
                            ?>
                            <li class="p-4 hover:bg-slate-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-slate-800 text-sm"><?= esc(ucwords(str_replace('_', ' ', $request['type'] ?? 'leave'))) ?></h4>
                                        <p class="text-xs text-slate-500 mt-1"><?= esc($dateDisplay) ?></p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                        <?= esc(ucfirst($request['status'])) ?>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="p-4 text-center text-slate-500 text-sm">
                            No time off requests found.
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
