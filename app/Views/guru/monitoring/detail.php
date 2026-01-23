<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:ml-64">
    
    <div class="flex justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $ruang['nama_ruangan'] ?></h1>
            <p class="text-sm text-gray-500">Monitoring Realtime • <?= date('d M Y') ?></p>
        </div>
        <a href="<?= base_url('guru/monitoring') ?>" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-lg dark:text-blue-100 dark:bg-blue-600"><i class="fas fa-user-clock"></i></div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Belum</p>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white"><?= $stats['belum'] ?></h3>
            </div>
        </div>
        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-lg dark:text-purple-100 dark:bg-purple-600"><i class="fas fa-pen-alt"></i></div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Sedang</p>
                <h3 class="text-xl font-bold text-purple-600 dark:text-purple-400"><?= $stats['sedang'] ?></h3>
            </div>
        </div>
        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-lg dark:text-green-100 dark:bg-green-600"><i class="fas fa-check-circle"></i></div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Selesai</p>
                <h3 class="text-xl font-bold text-green-600 dark:text-green-400"><?= $stats['selesai'] ?></h3>
            </div>
        </div>
        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-lg dark:text-orange-100 dark:bg-orange-600"><i class="fas fa-book"></i></div>
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
            <button onclick="openTimeModal()" class="text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-full text-sm p-2.5" title="Tambah Waktu"><i class="fas fa-clock"></i></button>
            <button onclick="confirmAction('unlock')" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-full text-sm p-2.5" title="Buka Kunci"><i class="fas fa-lock-open"></i></button>
            <button onclick="confirmAction('stop')" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-full text-sm p-2.5" title="Paksa Selesai"><i class="fas fa-stop-circle"></i></button>
            <button onclick="confirmAction('reset')" class="text-white bg-green-500 hover:bg-green-600 font-medium rounded-full text-sm p-2.5" title="Reset"><i class="fas fa-sync-alt"></i></button>
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
                    <tr><td colspan="6" class="text-center py-6 text-gray-400">Ruangan kosong / Belum ada peserta.</td></tr>
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
                                    <span class="text-green-600 font-bold"><i class="fas fa-check"></i> <?= $s['jml_benar'] ?></span> | 
                                    <span class="text-red-600 font-bold"><i class="fas fa-times"></i> <?= $s['jml_salah'] ?></span>
                                    <div class="text-xs mt-1">Nilai: <?= number_format($s['nilai_sementara'], 0) ?></div>
                                <?php else: ?> - <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                if($s['status'] == 'SELESAI') echo '<span class="badge-hijau">SELESAI</span>';
                                elseif($s['status']) {
                                    echo '<span class="badge-biru animate-pulse">MENGERJAKAN</span>';
                                    if($s['is_locked']) echo ' <i class="fas fa-lock text-orange-500 ml-1" title="Terkunci"></i>';
                                }
                                else echo '<span class="badge-abu">BELUM</span>';
                                ?>
                            </td>
                            <td class="px-6 py-4 text-center flex justify-center gap-1">
                                <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'reset')" class="btn-icon bg-green-500"><i class="fas fa-sync"></i></button>
                                <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'stop')" class="btn-icon bg-red-500"><i class="fas fa-stop"></i></button>
                                <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'unlock')" class="btn-icon bg-blue-500"><i class="fas fa-lock-open"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="timeModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-50">
    <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4 dark:text-white">Tambah Waktu</h3>
        <input type="number" id="menit" class="w-full p-2 border rounded mb-4 text-gray-900" placeholder="Menit (cth: 10)">
        <div class="flex justify-end gap-2">
            <button onclick="closeModal('timeModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button onclick="submitTime()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
        </div>
    </div>
</div>

<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-50">
    <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-6 w-96 text-center">
        <h3 id="confirmTitle" class="text-lg font-bold mb-4 dark:text-white">Konfirmasi</h3>
        <div class="flex justify-center gap-2">
            <button onclick="closeModal('confirmModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button onclick="executeAction()" id="btnConfirmYes" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Ya, Proses</button>
        </div>
    </div>
</div>

<script>
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
    .btn-icon { @apply text-white rounded p-1.5 w-8 h-8 hover:opacity-80; }
</style>

<?= $this->endSection() ?>