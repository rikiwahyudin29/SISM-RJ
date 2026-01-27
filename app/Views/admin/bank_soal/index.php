<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Monitoring Bank Soal</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Pantau kelengkapan & target soal dari guru.</p>
        </div>
        <button onclick="location.reload()" class="group flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
            <i class="fas fa-sync-alt text-slate-400 group-hover:rotate-180 transition-transform duration-500"></i> Refresh Data
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 transform group-hover:scale-110 transition-transform"><i class="fas fa-book text-6xl"></i></div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Bank Soal</p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= $stats['total_bank'] ?></h3>
            <div class="mt-4 h-1 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 w-full"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 transform group-hover:scale-110 transition-transform"><i class="fas fa-check-circle text-6xl"></i></div>
            <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider mb-1">Siap Digunakan</p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= $stats['soal_siap'] ?></h3>
            <div class="mt-4 h-1 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 w-3/4"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 transform group-hover:scale-110 transition-transform"><i class="fas fa-exclamation-triangle text-6xl"></i></div>
            <p class="text-xs font-bold text-amber-500 uppercase tracking-wider mb-1">Belum Lengkap</p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= $stats['soal_kurang'] ?></h3>
            <div class="mt-4 h-1 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-amber-500 w-1/2"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 transform group-hover:scale-110 transition-transform"><i class="fas fa-chalkboard-teacher text-6xl"></i></div>
            <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider mb-1">Guru Aktif</p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white"><?= $stats['guru_aktif'] ?></h3>
            <div class="mt-4 h-1 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 w-2/3"></div>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-xl border border-emerald-100 dark:border-emerald-800 flex items-center gap-3 font-bold text-sm">
            <i class="fas fa-check-circle text-lg"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 dark:text-white text-lg">Daftar Bank Soal</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 font-bold uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4 w-12 text-center">No</th>
                        <th class="p-4">Guru & Mapel</th>
                        <th class="p-4">Detail Ujian</th>
                        <th class="p-4 text-center">Target Soal</th>
                        <th class="p-4 text-center">Progress</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php foreach($bank as $i => $b): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="p-4 text-center font-mono text-slate-400"><?= $i + 1 ?></td>
                        
                        <td class="p-4">
                            <div class="font-bold text-slate-800 dark:text-white"><?= esc($b['nama_guru']) ?></div>
                            <div class="text-xs font-bold text-slate-400 mt-1 flex items-center gap-1">
                                <span class="bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500 dark:text-slate-300">
                                    <?= esc($b['nama_mapel']) ?>
                                </span>
                            </div>
                        </td>

                        <td class="p-4">
                            <div class="font-bold text-blue-600 dark:text-blue-400 truncate max-w-[200px]" title="<?= esc($b['judul_ujian']) ?>">
                                <?= esc($b['judul_ujian']) ?>
                            </div>
                            <div class="text-xs text-slate-400 mt-1 font-mono">ID: BS-<?= esc($b['id']) ?></div>
                            <?php $tgl = $b['tanggal_pembuatan'] ?? $b['created_at'] ?? null; ?>
                            <?php if($tgl): ?>
                                <div class="text-[10px] text-slate-400 mt-1 italic">
                                    <i class="far fa-clock mr-1"></i><?= date('d M Y', strtotime($tgl)) ?>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td class="p-4">
                            <div class="flex flex-col items-center justify-center gap-1 group cursor-pointer" 
                                 onclick="openModalTarget(<?= $b['id'] ?>, <?= $b['jumlah_soal_pg'] ?>, <?= $b['jumlah_soal_esai'] ?>, '<?= esc($b['judul_ujian']) ?>')">
                                <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 px-3 py-1.5 rounded-lg hover:border-blue-400 transition-colors">
                                    <div class="text-center">
                                        <div class="text-[10px] text-slate-400 font-bold uppercase">PG</div>
                                        <div class="font-black text-slate-700 dark:text-slate-200"><?= $b['jumlah_soal_pg'] ?></div>
                                    </div>
                                    <div class="h-6 w-px bg-slate-200 dark:bg-slate-700"></div>
                                    <div class="text-center">
                                        <div class="text-[10px] text-slate-400 font-bold uppercase">Esai</div>
                                        <div class="font-black text-slate-700 dark:text-slate-200"><?= $b['jumlah_soal_esai'] ?></div>
                                    </div>
                                    <div class="ml-1 text-slate-300 group-hover:text-blue-500 transition-colors">
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="p-4">
                            <?php 
                                $totalTarget = $b['jumlah_soal_pg'] + $b['jumlah_soal_esai'];
                                $totalAsli = $b['total_soal_asli'];
                                $persen = ($totalTarget > 0) ? round(($totalAsli / $totalTarget) * 100) : 0;
                                
                                // Warna Progress
                                if($persen >= 100) { $col = 'bg-emerald-500'; $txt = 'text-emerald-600'; $bg = 'bg-emerald-100'; }
                                elseif($persen >= 50) { $col = 'bg-amber-500'; $txt = 'text-amber-600'; $bg = 'bg-amber-100'; }
                                else { $col = 'bg-rose-500'; $txt = 'text-rose-600'; $bg = 'bg-rose-100'; }
                            ?>
                            <div class="w-full max-w-[140px] mx-auto">
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-xs font-black <?= $txt ?>"><?= $persen ?>%</span>
                                    <span class="text-[10px] font-bold text-slate-400"><?= $totalAsli ?>/<?= $totalTarget ?></span>
                                </div>
                                <div class="h-2 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full <?= $col ?> rounded-full transition-all duration-1000" style="width: <?= $persen ?>%"></div>
                                </div>
                            </div>
                        </td>

                        <td class="p-4 text-center">
                            <a href="<?= base_url('admin/bank-soal/detail/'.$b['id']) ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Lihat Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTarget" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md border border-slate-100 dark:border-slate-700 transform scale-95 transition-transform duration-300" id="modalContent">
        
        <form action="<?= base_url('admin/bank-soal/update-target') ?>" method="post">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50 rounded-t-2xl">
                <h3 class="font-black text-lg text-slate-800 dark:text-white uppercase tracking-tight">
                    <i class="fas fa-bullseye text-blue-500 mr-2"></i> Target Soal
                </h3>
                <button type="button" onclick="closeModalTarget()" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <input type="hidden" name="id" id="editId">
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Judul Ujian</label>
                    <input type="text" id="editJudul" readonly class="w-full bg-slate-100 dark:bg-slate-700 border-0 rounded-xl px-4 py-3 font-bold text-slate-600 dark:text-slate-300 text-sm focus:ring-0">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Target PG</label>
                        <input type="number" name="jml_pg" id="editPg" min="0" required class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-black text-center text-xl focus:border-blue-500 focus:ring-0 dark:bg-slate-900 dark:text-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Target Esai</label>
                        <input type="number" name="jml_esai" id="editEsai" min="0" required class="w-full border-2 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-black text-center text-xl focus:border-blue-500 focus:ring-0 dark:bg-slate-900 dark:text-white transition-colors">
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-xl flex gap-3 items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium leading-relaxed">
                        Perubahan target akan mempengaruhi persentase progress guru di dashboard.
                    </p>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-700 flex gap-3">
                <button type="button" onclick="closeModalTarget()" class="flex-1 px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform active:scale-95">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modalTarget');
    const modalContent = document.getElementById('modalContent');

    function openModalTarget(id, pg, esai, judul) {
        document.getElementById('editId').value = id;
        document.getElementById('editPg').value = pg;
        document.getElementById('editEsai').value = esai;
        document.getElementById('editJudul').value = judul;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // Animasi Masuk
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeModalTarget() {
        // Animasi Keluar
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
</script>

<?= $this->endSection() ?>