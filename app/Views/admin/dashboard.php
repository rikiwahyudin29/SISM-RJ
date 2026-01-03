<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="flex items-center justify-between p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Guru</p>
            <h5 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $total_guru ?></h5>
        </div>
        <div class="p-3 bg-blue-100 rounded-lg dark:bg-blue-900">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
        </div>
    </div>
    </div>

<div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <h3 class="text-lg font-bold mb-4">Log Aktivitas Terbaru</h3>
    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
        <?php foreach($logs as $l): ?>
        <li class="py-3">
            <p class="text-sm font-medium text-gray-900 dark:text-white"><?= $l['act'] ?></p>
            <p class="text-xs text-gray-500"><?= $l['user'] ?> - <?= $l['time'] ?></p>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?= $this->endSection() ?>