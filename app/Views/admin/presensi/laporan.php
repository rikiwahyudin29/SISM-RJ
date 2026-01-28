<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Laporan Kehadiran</h1>
            <p class="text-sm text-slate-500">Rekapitulasi absensi siswa per bulan.</p>
        </div>
        
        <form action="" method="get" class="flex flex-wrap gap-2">
            <input type="month" name="bulan" value="<?= $bulan ?>" class="px-4 py-2 border rounded-xl font-bold text-sm outline-none focus:border-blue-500">
            
            <select name="id_kelas" class="px-4 py-2 border rounded-xl font-bold text-sm outline-none focus:border-blue-500">
                <option value="">-- Semua Kelas --</option>
                <?php foreach($kelas as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= $filter_kelas == $k['id'] ? 'selected' : '' ?>><?= $k['nama_kelas'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-xl font-bold text-sm">Filter</button>
            
            <a href="<?= base_url('admin/presensi/cetak_harian') ?>" target="_blank" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-rose-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Rekap Harian (Hari Ini)
            </a>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Siswa</th>
                    <th class="p-4">Kelas</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Jam Masuk/Pulang</th>
                    <th class="p-4">Ket</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php foreach($data as $d): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                    <td class="p-4 font-mono text-slate-500"><?= date('d/m/Y', strtotime($d['tanggal'])) ?></td>
                    <td class="p-4 font-bold text-slate-800 dark:text-white"><?= esc($d['nama_lengkap']) ?></td>
                    <td class="p-4"><?= esc($d['nama_kelas']) ?></td>
                    <td class="p-4">
                        <?php 
                            $bg = 'bg-slate-100 text-slate-600';
                            if($d['status_kehadiran'] == 'Hadir') $bg = 'bg-emerald-100 text-emerald-600';
                            if($d['status_kehadiran'] == 'Terlambat') $bg = 'bg-yellow-100 text-yellow-600';
                            if($d['status_kehadiran'] == 'Sakit') $bg = 'bg-rose-100 text-rose-600';
                            if($d['status_kehadiran'] == 'Izin') $bg = 'bg-blue-100 text-blue-600';
                            if($d['status_kehadiran'] == 'Alpha') $bg = 'bg-red-100 text-red-600';
                        ?>
                        <span class="px-2 py-1 rounded text-xs font-bold <?= $bg ?>">
                            <?= $d['status_kehadiran'] ?>
                        </span>
                    </td>
                    <td class="p-4 font-mono text-xs">
                        In: <?= $d['jam_masuk'] ?? '-' ?><br>
                        Out: <?= $d['jam_pulang'] ?? '-' ?>
                    </td>
                    <td class="p-4 italic text-slate-500"><?= esc($d['keterangan']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>