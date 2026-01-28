<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Verifikasi Izin & Sakit</h1>
            <p class="text-sm text-slate-500">Kelola pengajuan ketidakhadiran Siswa & Guru.</p>
        </div>
        
        <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-blue-500/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Input Manual Siswa
        </button>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-4 mb-4 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-sm flex items-center gap-2">
            ✅ <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="flex gap-2 mb-6 border-b border-slate-200 dark:border-slate-700">
        <button onclick="switchTab('siswa')" id="tab-siswa" class="px-6 py-2 font-bold text-sm border-b-2 border-blue-600 text-blue-600 transition-all">
            Data Siswa
        </button>
        <button onclick="switchTab('guru')" id="tab-guru" class="px-6 py-2 font-bold text-sm border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-all">
            Data Guru
        </button>
    </div>

    <div id="panel-siswa" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Siswa</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Keterangan</th>
                    <th class="p-4 text-center">Bukti</th>
                    <th class="p-4 text-center">Verifikasi</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if(empty($izinSiswa)): ?>
                    <tr><td colspan="7" class="p-8 text-center text-slate-400 italic">Tidak ada data pengajuan siswa.</td></tr>
                <?php else: ?>
                    <?php foreach($izinSiswa as $d): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="p-4 font-mono text-slate-500"><?= date('d/m/Y', strtotime($d['tanggal'])) ?></td>
                        <td class="p-4">
                            <div class="font-bold text-slate-800 dark:text-white"><?= esc($d['nama_lengkap']) ?></div>
                            <div class="text-xs text-slate-400"><?= esc($d['nama_kelas']) ?></div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-xs font-bold <?= ($d['status_kehadiran']=='Sakit') ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' ?>">
                                <?= $d['status_kehadiran'] ?>
                            </span>
                        </td>
                        <td class="p-4 text-slate-600 italic">"<?= esc($d['keterangan']) ?>"</td>
                        <td class="p-4 text-center">
                            <?php if($d['bukti_izin']): ?>
                                <a href="<?= base_url('uploads/surat_izin/'.$d['bukti_izin']) ?>" target="_blank" class="text-blue-600 hover:underline text-xs font-bold">📄 Lihat</a>
                            <?php else: ?>
                                <span class="text-slate-300">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center">
                            <?php if($d['status_verifikasi'] == 'Pending'): ?>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold animate-pulse">⏳ Pending</span>
                            <?php elseif($d['status_verifikasi'] == 'Disetujui'): ?>
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-600 rounded text-xs font-bold">✅ Disetujui</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-xs font-bold">❌ Ditolak</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center flex justify-center gap-1">
                            <?php if($d['status_verifikasi'] == 'Pending'): ?>
                                <a href="<?= base_url('admin/presensi/verifikasi/'.$d['id'].'/Disetujui') ?>" onclick="return confirm('Setujui izin ini?')" class="bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-lg" title="Setujui">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </a>
                                <a href="<?= base_url('admin/presensi/verifikasi/'.$d['id'].'/Ditolak') ?>" onclick="return confirm('Tolak izin ini?')" class="bg-rose-500 hover:bg-rose-600 text-white p-2 rounded-lg" title="Tolak">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('admin/presensi/hapus_izin/'.$d['id']) ?>" onclick="return confirm('Hapus data ini?')" class="text-slate-400 hover:text-rose-500 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="panel-guru" class="hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Guru</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Keterangan</th>
                    <th class="p-4 text-center">Bukti</th>
                    <th class="p-4 text-center">Verifikasi</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if(empty($izinGuru)): ?>
                    <tr><td colspan="7" class="p-8 text-center text-slate-400 italic">Tidak ada data pengajuan guru.</td></tr>
                <?php else: ?>
                    <?php foreach($izinGuru as $g): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="p-4 font-mono text-slate-500"><?= date('d/m/Y', strtotime($g['tanggal'])) ?></td>
                        <td class="p-4">
                            <div class="font-bold text-slate-800 dark:text-white"><?= esc($g['nama_guru']) ?></div>
                            <div class="text-xs text-slate-400">NIP: <?= esc($g['nip']) ?></div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-600">
                                <?= $g['status_kehadiran'] ?>
                            </span>
                        </td>
                        <td class="p-4 text-slate-600 italic">"<?= esc($g['keterangan']) ?>"</td>
                        <td class="p-4 text-center">
                            <?php if($g['bukti_izin']): ?>
                                <a href="<?= base_url('uploads/surat_izin/'.$g['bukti_izin']) ?>" target="_blank" class="text-blue-600 hover:underline text-xs font-bold">📄 Lihat</a>
                            <?php else: ?>
                                <span class="text-slate-300">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center">
                            <?php if($g['status_verifikasi'] == 'Pending'): ?>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold animate-pulse">⏳ Pending</span>
                            <?php elseif($g['status_verifikasi'] == 'Disetujui'): ?>
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-600 rounded text-xs font-bold">✅ Disetujui</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-xs font-bold">❌ Ditolak</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center flex justify-center gap-1">
                            <?php if($g['status_verifikasi'] == 'Pending'): ?>
                                <a href="<?= base_url('admin/presensi/verifikasi/'.$g['id'].'/Disetujui') ?>" onclick="return confirm('Setujui izin guru ini?')" class="bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </a>
                                <a href="<?= base_url('admin/presensi/verifikasi/'.$g['id'].'/Ditolak') ?>" onclick="return confirm('Tolak izin guru ini?')" class="bg-rose-500 hover:bg-rose-600 text-white p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('admin/presensi/hapus_izin/'.$g['id']) ?>" onclick="return confirm('Hapus data ini?')" class="text-slate-400 hover:text-rose-500 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function switchTab(tab) {
        // Hide All
        document.getElementById('panel-siswa').classList.add('hidden');
        document.getElementById('panel-guru').classList.add('hidden');
        
        // Reset Style Tab
        document.getElementById('tab-siswa').className = "px-6 py-2 font-bold text-sm border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-all";
        document.getElementById('tab-guru').className = "px-6 py-2 font-bold text-sm border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-all";

        // Show Active
        document.getElementById('panel-' + tab).classList.remove('hidden');
        
        // Set Active Style
        document.getElementById('tab-' + tab).className = "px-6 py-2 font-bold text-sm border-b-2 border-blue-600 text-blue-600 transition-all";
    }
</script>

<?= $this->endSection() ?>