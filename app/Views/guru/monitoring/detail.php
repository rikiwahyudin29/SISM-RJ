<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<div class="p-1 sm:ml-1 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-10 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight"><?= $ruang['nama_ruangan'] ?></h1>
            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Monitoring Realtime • <?= date('d M Y') ?></p>
        </div>
        <div class="flex gap-2">
            <div id="liveClock" class="bg-emerald-500 text-white px-5 py-2.5 rounded-full text-[10px] font-black shadow-lg shadow-emerald-500/20"><?= date('H:i:s') ?></div>
            <a href="<?= base_url('guru/monitoring') ?>" class="text-white bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 px-5 py-2.5 rounded-xl text-xs font-bold flex items-center shadow-lg transition-all active:scale-95">
                <i class="fas fa-arrow-left mr-2"></i> KEMBALI
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="p-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 flex items-center shadow-sm">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/30 text-blue-500 rounded-2xl mr-4"><i class="fas fa-user-clock text-xl"></i></div>
            <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Belum</p><h3 class="text-2xl font-black text-slate-800 dark:text-white"><?= $stats['belum'] ?></h3></div>
        </div>
        <div class="p-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 flex items-center shadow-sm">
            <div class="p-4 bg-purple-50 dark:bg-purple-900/30 text-purple-500 rounded-2xl mr-4"><i class="fas fa-pen-nib text-xl"></i></div>
            <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sedang</p><h3 class="text-2xl font-black text-purple-600 dark:text-purple-400"><?= $stats['sedang'] ?></h3></div>
        </div>
        <div class="p-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 flex items-center shadow-sm">
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 rounded-2xl mr-4"><i class="fas fa-check-circle text-xl"></i></div>
            <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Selesai</p><h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400"><?= $stats['selesai'] ?></h3></div>
        </div>
        <div class="p-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 flex items-center shadow-sm">
            <div class="p-4 bg-orange-50 dark:bg-orange-900/30 text-orange-500 rounded-2xl mr-4"><i class="fas fa-book text-xl"></i></div>
            <div class="overflow-hidden"><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ujian</p><h3 class="text-sm font-black text-slate-800 dark:text-white truncate uppercase"><?= $siswa[0]['judul_ujian'] ?? '-' ?></h3></div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden mb-4">
        <div class="flex flex-col md:flex-row items-center justify-between p-6 gap-4">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" id="toggleRefresh" class="sr-only peer" checked>
                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-checked:bg-blue-600 rounded-full relative transition-all after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                <span class="ml-3 text-xs font-bold text-slate-400 dark:text-slate-300 uppercase">Auto Refresh (15s)</span>
            </label>
            <div class="flex gap-2">
                <button onclick="openTimeModal()" class="p-4 bg-amber-400 text-white rounded-2xl shadow-lg hover:scale-110 transition-transform" title="Tambah Waktu"><i class="fas fa-clock"></i></button>
                <button onclick="confirmAction('unlock')" class="p-4 bg-blue-500 text-white rounded-2xl shadow-lg hover:scale-110 transition-transform" title="Buka Kunci"><i class="fas fa-unlock-alt"></i></button>
                <button onclick="confirmAction('stop')" class="p-4 bg-rose-600 text-white rounded-2xl shadow-lg hover:scale-110 transition-transform" title="Paksa Selesai"><i class="fas fa-flag-checkered"></i></button>
                <button onclick="confirmAction('reset')" class="p-4 bg-teal-400 text-white rounded-2xl shadow-lg hover:scale-110 transition-transform" title="Reset Ujian"><i class="fas fa-sync-alt"></i></button>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-700/50 text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest">
                    <tr>
                        <th class="p-6 w-4"><input id="checkbox-all" type="checkbox" class="rounded-md border-slate-300 dark:bg-slate-700 dark:border-slate-600"></th>
                        <th class="px-4 py-4">No PC</th>
                        <th class="px-4 py-4">Identitas</th>
                        <th class="px-4 py-4 text-center">Jawaban</th>
                        <th class="px-4 py-4 text-center">Nilai</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4 text-center">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    <?php foreach($siswa as $s): ?>
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="p-6 text-center"><input type="checkbox" value="<?= $s['siswa_id'] ?>" class="chk-siswa rounded-md border-slate-300 dark:bg-slate-700 dark:border-slate-600"></td>
                        <td class="px-4 py-4 font-mono font-bold text-slate-500 dark:text-slate-400"><?= $s['no_komputer'] ?></td>
                        <td class="px-4 py-4">
                            <div class="font-black text-slate-700 dark:text-white uppercase text-xs"><?= $s['nama_lengkap'] ?></div>
                            <div class="text-[10px] font-bold text-slate-400"><?= $s['nis'] ?> • <?= $s['nama_kelas'] ?></div>
                        </td>
                        <td class="px-4 py-4">
                            <?php if($s['status']): ?>
                            <div class="flex justify-center gap-1 font-bold text-[9px]">
                                <span class="bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 px-2 py-1 rounded-lg"><?= $s['jml_benar'] ?> <i class="fas fa-check"></i></span>
                                <span class="bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 px-2 py-1 rounded-lg"><?= $s['jml_salah'] ?> <i class="fas fa-times"></i></span>
                            </div>
                            <?php else: ?> <div class="text-center text-slate-300 dark:text-slate-600">-</div> <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="font-black text-slate-800 dark:text-white"><?= ($s['nilai_sementara'] > 0) ? number_format($s['nilai_sementara'], 0) : '-' ?></span>
                        </td>
                        <td class="px-4 py-4">
                            <?php if($s['status'] == 'SELESAI'): ?>
                                <span class="bg-emerald-500 text-white text-[9px] px-2 py-1 rounded-md font-black uppercase">Selesai</span>
                            <?php elseif($s['status']): ?>
                                <span class="bg-blue-500 text-white text-[9px] px-2 py-1 rounded-md font-black uppercase animate-pulse">Online</span>
                                <?php if($s['is_locked']): ?> <i class="fas fa-lock text-rose-500 ml-1 text-xs"></i><?php endif; ?>
                            <?php else: ?>
                                <span class="bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 text-[9px] px-2 py-1 rounded-md font-black uppercase">Offline</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center gap-1">
                                <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'unlock')" class="w-8 h-8 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Unlock"><i class="fas fa-unlock-alt text-[11px]"></i></button>
                                <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'stop')" class="w-8 h-8 bg-rose-50 dark:bg-rose-900/30 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all shadow-sm" title="Stop"><i class="fas fa-flag-checkered text-[11px]"></i></button>
                                <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'reset')" class="w-8 h-8 bg-teal-50 dark:bg-teal-900/30 text-teal-500 rounded-xl hover:bg-teal-500 hover:text-white transition-all shadow-sm" title="Reset"><i class="fas fa-sync-alt text-[11px]"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="timeModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-8 w-full max-w-sm border border-slate-100 dark:border-slate-700">
        <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6 uppercase tracking-tight text-center">Tambah Waktu</h3>
        <input type="number" id="menit" class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-center font-black text-2xl focus:ring-blue-500 dark:text-white mb-6" placeholder="Menit">
        <div class="flex gap-3">
            <button onclick="closeTimeModal()" class="flex-1 py-4 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 font-bold rounded-2xl uppercase">Batal</button>
            <button onclick="submitTime()" class="flex-1 py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-lg uppercase">Simpan</button>
        </div>
    </div>
</div>

<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-8 w-full max-w-sm text-center border border-slate-100 dark:border-slate-700">
        <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/30 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-6"><i class="fas fa-exclamation-triangle"></i></div>
        <h3 id="confirmTitle" class="text-lg font-black text-slate-800 dark:text-white mb-8 uppercase tracking-tight">Konfirmasi?</h3>
        <div class="flex gap-3">
            <button onclick="closeConfirmModal()" class="flex-1 py-4 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 font-bold rounded-2xl uppercase">Tidak</button>
            <button id="btnConfirmYes" onclick="executeAction()" class="flex-1 py-4 bg-rose-600 text-white font-bold rounded-2xl shadow-lg uppercase">Ya, Proses</button>
        </div>
    </div>
</div>

<script>
    // JS Logic Identik Seperti Sebelumnya (Update URL Ajax Ke Guru)
    let currentAction = '';
    function openModal(id) { const el = document.getElementById(id); el.classList.remove('hidden'); el.classList.add('flex'); }
    function closeModal(id) { el = document.getElementById(id); el.classList.add('hidden'); el.classList.remove('flex'); }
    function openTimeModal() { openModal('timeModal'); }
    function closeTimeModal() { closeModal('timeModal'); }
    function closeConfirmModal() { closeModal('confirmModal'); }

    function confirmAction(action) {
        if (getSelectedIds().length === 0) return alert('Pilih siswa dulu!');
        currentAction = action;
        document.getElementById('confirmTitle').innerText = "YAKIN " + action.toUpperCase() + " DATA?";
        openModal('confirmModal');
    }

    function checkAndAction(id, action) {
        document.querySelectorAll('.chk-siswa').forEach(c => c.checked = false);
        const target = document.querySelector(`.chk-siswa[value="${id}"]`);
        if(target) target.checked = true;
        confirmAction(action);
    }

    function executeAction() { kirimData(getSelectedIds(), currentAction, 0); closeConfirmModal(); }
    function submitTime() { kirimData(getSelectedIds(), 'add_time', document.getElementById('menit').value); closeTimeModal(); }

    function getSelectedIds() {
        let ids = [];
        document.querySelectorAll('.chk-siswa:checked').forEach(c => ids.push(c.value));
        return ids;
    }

    document.getElementById('checkbox-all').addEventListener('change', function() {
        document.querySelectorAll('.chk-siswa').forEach(c => c.checked = this.checked);
    });

    function kirimData(ids, aksi, menit) {
        document.body.style.cursor = 'wait';
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

    setInterval(() => { if(document.getElementById('toggleRefresh').checked) location.reload(); }, 15000);
    setInterval(() => { document.getElementById('liveClock').innerText = new Date().toLocaleTimeString('id-ID'); }, 1000);
</script>

<?= $this->endSection() ?>