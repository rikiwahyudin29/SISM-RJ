<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-2">
                👮‍♂️ Monitoring Guru Piket
            </h1>
            <p class="text-sm text-slate-500">
                Jadwal Hari Ini: <span class="font-bold text-blue-600"><?= $hari_ini ?>, <?= date('d M Y') ?></span> 
                | Jam: <span class="font-mono bg-slate-100 px-2 rounded"><?= $jam_sekarang ?></span>
            </p>
        </div>
        
        <button onclick="window.location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-blue-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Refresh Data
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="p-4 w-24 text-center">Jam</th>
                        <th class="p-4 w-20">Kelas</th>
                        <th class="p-4">Mata Pelajaran & Guru</th>
                        <th class="p-4 text-center">Status Kehadiran Guru</th>
                        <th class="p-4 text-center">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php if(empty($monitoring)): ?>
                        <tr><td colspan="5" class="p-8 text-center text-slate-400 italic">Tidak ada jadwal KBM hari ini. Libur? 🤔</td></tr>
                    <?php else: ?>
                        <?php foreach($monitoring as $m): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="p-4 text-center">
                                <div class="font-mono font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-lg text-xs inline-block">
                                    <?= substr($m['jam_mulai'], 0, 5) ?> - <?= substr($m['jam_selesai'], 0, 5) ?>
                                </div>
                            </td>
                            
                            <td class="p-4">
                                <span class="font-black text-lg text-slate-800 dark:text-white"><?= $m['nama_kelas'] ?></span>
                            </td>

                            <td class="p-4">
                                <div class="font-bold text-slate-800 dark:text-white"><?= $m['nama_mapel'] ?></div>
                                <div class="flex items-center gap-2 mt-1">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="text-xs text-slate-500"><?= $m['nama_guru_fix'] ?></span>
                                </div>
                            </td>

                            <td class="p-4 text-center">
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold border <?= $m['badge_color'] ?>">
                                    <?= $m['status_kbm'] ?>
                                </span>
                            </td>

                            <td class="p-4 text-center">
                                <?php if($m['data_jurnal']): ?>
                                    <button type="button" onclick="showEvidence('<?= $m['data_jurnal']['materi'] ?>', '<?= $m['data_jurnal']['foto_kegiatan'] ?>')" class="text-blue-600 hover:text-blue-800 font-bold text-xs underline">
                                        Lihat
                                    </button>
                                <?php else: ?>
                                    <span class="text-slate-300 text-2xl">•</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalBukti" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4" onclick="closeModal()">
    <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-1 shadow-2xl transform transition-all scale-100" onclick="event.stopPropagation()">
        <img id="imgBukti" src="" class="w-full h-64 object-cover rounded-xl bg-slate-100">
        <div class="p-4">
            <h4 class="font-bold text-slate-800 dark:text-white mb-1">Materi:</h4>
            <p id="txtMateri" class="text-sm text-slate-600 dark:text-slate-300 italic"></p>
            <button onclick="closeModal()" class="mt-4 w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-lg text-sm">Tutup</button>
        </div>
    </div>
</div>

<script>
    function showEvidence(materi, foto) {
        document.getElementById('txtMateri').innerText = materi;
        document.getElementById('imgBukti').src = '<?= base_url('uploads/jurnal/') ?>' + foto;
        document.getElementById('modalBukti').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('modalBukti').classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>