<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Manajemen Rombongan Belajar (Rombel)</h1>
        <p class="text-sm text-gray-500">Pilih kelas untuk melakukan kenaikan kelas atau kelulusan.</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 m-4"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach($kelas as $k): ?>
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 transition">
        <div class="p-5">
            <div class="flex items-center justify-between mb-2">
                <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?= $k['nama_kelas'] ?></h5>
                <i class="fas fa-users text-blue-500 text-xl"></i>
            </div>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 text-sm">
                Kelola anggota kelas ini.
            </p>
            <a href="<?= base_url('admin/rombel/atur/' . $k['id']) ?>" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full justify-center">
                Atur / Naik Kelas
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
    
    <div class="bg-gray-100 border border-gray-300 rounded-lg shadow dark:bg-gray-700 dark:border-gray-600 border-dashed">
        <div class="p-5 text-center">
             <h5 class="text-2xl font-bold tracking-tight text-gray-500 dark:text-gray-300 mt-2">DATA ALUMNI</h5>
             <a href="<?= base_url('admin/rombel/alumni') ?>" class="mt-4 inline-flex items-center px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                Lihat Alumni <i class="fas fa-graduation-cap ml-2"></i>
             </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>