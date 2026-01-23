<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-1 sm:ml-1">
    
    <div class="flex justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $ruang['nama_ruangan'] ?></h1>
            <p class="text-sm text-gray-500">Monitoring Realtime • <?= date('d M Y') ?></p>
        </div>
        <a href="<?= base_url('guru/monitoring') ?>" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-lg dark:text-blue-100 dark:bg-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Belum</p>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white"><?= $stats['belum'] ?></h3>
            </div>
        </div>
        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-lg dark:text-purple-100 dark:bg-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Sedang</p>
                <h3 class="text-xl font-bold text-purple-600 dark:text-purple-400"><?= $stats['sedang'] ?></h3>
            </div>
        </div>
        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-lg dark:text-green-100 dark:bg-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Selesai</p>
                <h3 class="text-xl font-bold text-green-600 dark:text-green-400"><?= $stats['selesai'] ?></h3>
            </div>
        </div>
        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-lg dark:text-orange-100 dark:bg-orange-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div class="overflow-hidden">
                <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Mapel</p>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate">
                    <?= !empty($siswa) && isset($siswa[0]['judul_ujian']) ? $siswa[0]['judul_ujian'] : '-' ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 bg-white dark:bg-slate-800 p-4 rounded-t-xl border-b border-gray-200 dark:border-slate-700">
        <div class="flex items-center">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="toggleRefresh" class="sr-only peer" checked>
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Auto Refresh</span>
            </label>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openTimeModal()" class="text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-full text-sm p-2.5 inline-flex items-center" title="Tambah Waktu">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </button>
            <button onclick="confirmAction('unlock')" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-full text-sm p-2.5 inline-flex items-center" title="Buka Kunci">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
            </button>
            <button onclick="confirmAction('stop')" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-full text-sm p-2.5 inline-flex items-center" title="Paksa Selesai">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
            </button>
            <button onclick="confirmAction('reset')" class="text-white bg-green-500 hover:bg-green-600 font-medium rounded-full text-sm p-2.5 inline-flex items-center" title="Reset">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
    </div>

    <div class="relative overflow-x-auto bg-white dark:bg-slate-800 shadow-md rounded-b-xl pb-4 min-h-[400px]">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-700 dark:text-gray-400">
                <tr>
                    <th class="p-4 w-4"><input id="checkbox-all" type="checkbox" class="w-4 h-4 rounded"></th>
                    <th class="px-6 py-3">No PC</th>
                    <th class="px-6 py-3">Identitas</th>
                    <th class="px-6 py-3">Progres</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($siswa)): ?>
                    <tr><td colspan="6" class="text-center py-12 text-gray-400">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Ruangan kosong / Belum ada peserta.
                        </div>
                    </td></tr>
                <?php else: ?>
                    <?php foreach($siswa as $s): ?>
                        <tr class="bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-50">
                            <td class="w-4 p-4"><input type="checkbox" value="<?= $s['siswa_id'] ?>" class="chk-siswa w-4 h-4 rounded"></td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white"><?= $s['no_komputer'] ?></td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 dark:text-white"><?= $s['nama_lengkap'] ?></div>
                                <div class="text-xs text-gray-500"><?= $s['nis'] ?> • <?= $s['nama_kelas'] ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($s['status']): ?>
                                    <span class="text-green-600 font-bold inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <?= $s['jml_benar'] ?>
                                    </span> 
                                    <span class="text-gray-300 mx-1">|</span>
                                    <span class="text-red-600 font-bold inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        <?= $s['jml_salah'] ?>
                                    </span>
                                    <div class="text-xs mt-1 text-gray-500">Skor: <?= number_format($s['nilai_sementara'], 0) ?></div>
                                <?php else: ?> - <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                if($s['status'] == 'SELESAI') echo '<span class="badge-hijau">SELESAI</span>';
                                elseif($s['status']) {
                                    echo '<span class="badge-biru animate-pulse">MENGERJAKAN</span>';
                                    if($s['is_locked']) echo ' <span title="Terkunci">🔒</span>';
                                }
                                else echo '<span class="badge-abu">BELUM</span>';
                                ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'reset')" class="btn-icon bg-green-500 hover:bg-green-600" title="Reset">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    </button>
                                    <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'stop')" class="btn-icon bg-red-500 hover:bg-red-600" title="Stop">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                                    </button>
                                    <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'unlock')" class="btn-icon bg-blue-500 hover:bg-blue-600" title="Unlock">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="timeModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-2xl p-6 w-96 transform transition-all scale-100">
        <h3 class="text-lg font-bold mb-4 dark:text-white flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Tambah Waktu
        </h3>
        <input type="number" id="menit" class="w-full p-2.5 border border-gray-300 rounded-lg mb-4 text-gray-900 focus:ring-blue-500 focus:border-blue-500" placeholder="Menit (cth: 10)">
        <div class="flex justify-end gap-2">
            <button onclick="closeModal('timeModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Batal</button>
            <button onclick="submitTime()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Simpan</button>
        </div>
    </div>
</div>

<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-2xl p-6 w-96 text-center transform transition-all scale-100">
        <div class="mb-4 text-red-500 mx-auto w-12 h-12 flex items-center justify-center bg-red-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 id="confirmTitle" class="text-lg font-bold mb-4 dark:text-white">Konfirmasi</h3>
        <div class="flex justify-center gap-2">
            <button onclick="closeModal('confirmModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Batal</button>
            <button onclick="executeAction()" id="btnConfirmYes" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Ya, Proses</button>
        </div>
    </div>
</div>

<script>
    // JS TETAP SAMA SEPERTI SEBELUMNYA
    let currentAction = '';

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }
    
    function openTimeModal() { openModal('timeModal'); }
    function closeTimeModal() { closeModal('timeModal'); }
    function closeConfirmModal() { closeModal('confirmModal'); }

    document.getElementById('checkbox-all').addEventListener('change', function() {
        document.querySelectorAll('.chk-siswa').forEach(c => c.checked = this.checked);
    });

    function getSelectedIds() {
        let ids = [];
        document.querySelectorAll('.chk-siswa:checked').forEach(c => ids.push(c.value));
        return ids;
    }

    function checkAndAction(id, action) {
        document.querySelectorAll('.chk-siswa').forEach(c => c.checked = false);
        document.querySelector(`.chk-siswa[value="${id}"]`).checked = true;
        confirmAction(action);
    }

    function confirmAction(action) {
        if (getSelectedIds().length === 0) { alert('Pilih siswa dulu!'); return; }
        currentAction = action;
        let title = "Yakin melakukan aksi ini?";
        if(action === 'reset') title = "Reset ujian siswa terpilih?";
        if(action === 'stop') title = "Paksa selesai siswa terpilih?";
        if(action === 'unlock') title = "Buka kunci login siswa terpilih?";
        
        document.getElementById('confirmTitle').innerText = title;
        openModal('confirmModal');
    }

    function executeAction() {
        kirimData(getSelectedIds(), currentAction, 0);
        closeConfirmModal();
    }

    function submitTime() {
        kirimData(getSelectedIds(), 'add_time', document.getElementById('menit').value);
        closeTimeModal();
    }

    function kirimData(ids, aksi, menit) {
        const formData = new FormData();
        ids.forEach(id => formData.append('ids[]', id));
        formData.append('aksi', aksi);
        formData.append('menit', menit);

        fetch('<?= base_url('guru/monitoring/aksi_masal') ?>', {
            method: 'POST',
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') location.reload();
            else alert(data.msg);
        });
    }

    setInterval(() => {
        if(document.getElementById('toggleRefresh').checked) location.reload();
    }, 10000);
</script>

<style>
    .badge-hijau { @apply bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400; }
    .badge-biru { @apply bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400; }
    .badge-abu { @apply bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-400; }
    .btn-icon { @apply text-white rounded-lg p-2 w-8 h-8 flex items-center justify-center transition-colors shadow-sm; }
</style>

<?= $this->endSection() ?>