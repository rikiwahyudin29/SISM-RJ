<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= base_url('admin/bank-soal') ?>" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-500 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Detail Bank Soal</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Preview konten soal yang diupload guru.</p>
            </div>
        </div>
        
        <div class="flex gap-2">
             <div class="px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg text-xs font-bold uppercase tracking-wider border border-blue-100 dark:border-blue-800">
                <i class="fas fa-database mr-2"></i> ID: BS-<?= $bank['id'] ?>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
            <i class="fas fa-file-alt text-9xl text-slate-800 dark:text-white"></i>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Judul Ujian</p>
                <h3 class="text-lg font-black text-slate-800 dark:text-white leading-tight"><?= esc($bank['judul_ujian']) ?></h3>
            </div>
            
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Mata Pelajaran</p>
                <div class="flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-sm font-bold border border-indigo-200">
                        <?= esc($bank['nama_mapel']) ?>
                    </span>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Guru Pembuat</p>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold">
                        <?= substr($bank['nama_lengkap'], 0, 1) ?>
                    </div>
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-sm"><?= esc($bank['nama_lengkap']) ?></span>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Soal</p>
                <div class="flex items-center gap-3">
                    <div class="text-center">
                        <span class="block text-xl font-black text-slate-800 dark:text-white"><?= count($soal) ?></span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">Butir</span>
                    </div>
                    <div class="h-8 w-px bg-slate-200 dark:bg-slate-700"></div>
                    <div>
                         <span class="block text-xs font-bold text-slate-500">Target PG: <span class="text-blue-600"><?= $bank['jumlah_soal_pg'] ?></span></span>
                         <span class="block text-xs font-bold text-slate-500">Target Esai: <span class="text-pink-600"><?= $bank['jumlah_soal_esai'] ?></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <?php if(empty($soal)): ?>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-12 text-center border border-slate-200 border-dashed">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <i class="fas fa-folder-open text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-600 dark:text-slate-300">Belum Ada Soal</h3>
                <p class="text-slate-400 text-sm">Guru belum mengupload butir soal untuk bank ini.</p>
            </div>
        <?php else: ?>
            
            <?php foreach($soal as $index => $s): ?>
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 transition-all hover:shadow-md">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <span class="w-10 h-10 flex items-center justify-center bg-blue-600 text-white rounded-lg font-bold text-lg shadow-lg shadow-blue-500/30">
                                <?= $index + 1 ?>
                            </span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                    <?= $s['tipe_soal'] ?>
                                </span>
                                <?php if($s['bobot'] > 0): ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-200">
                                        Bobot: <?= $s['bobot'] ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="prose prose-sm dark:prose-invert max-w-none text-slate-800 dark:text-slate-200 mb-4 font-medium text-base">
                                <?= $s['pertanyaan'] ?>
                            </div>

                            <?php if(!empty($s['file_gambar'])): ?>
                                <div class="mb-4">
                                    <img src="<?= base_url('uploads/bank_soal/' . $s['file_gambar']) ?>" class="max-h-64 rounded-lg border border-slate-200 shadow-sm">
                                </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($s['file_audio'])): ?>
                                <div class="mb-4 bg-slate-50 dark:bg-slate-700/50 p-2 rounded-lg inline-block border border-slate-200 dark:border-slate-600">
                                    <audio controls class="h-8 w-64">
                                        <source src="<?= base_url('uploads/bank_soal/' . $s['file_audio']) ?>">
                                    </audio>
                                </div>
                            <?php endif; ?>

                            <?php 
                                // Ambil Opsi dari DB untuk soal ini
                                $db = \Config\Database::connect();
                                $opsi = $db->table('tbl_opsi_soal')->where('id_soal', $s['id'])->orderBy('id', 'ASC')->get()->getResultArray();
                            ?>

                            <?php if(in_array($s['tipe_soal'], ['PG', 'PG_KOMPLEKS'])): ?>
                                <div class="space-y-2 mt-4">
                                    <?php foreach($opsi as $o): ?>
                                        <div class="flex items-start gap-3 p-3 rounded-lg border <?= ($o['is_benar'] == 1) ? 'bg-emerald-50 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800' : 'bg-slate-50 border-slate-100 dark:bg-slate-900 dark:border-slate-700' ?>">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <?php if($o['is_benar'] == 1): ?>
                                                    <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                                                <?php else: ?>
                                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600"></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-sm font-medium <?= ($o['is_benar'] == 1) ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400' ?>">
                                                <?= $o['teks_opsi'] ?>
                                                <?php if(!empty($o['gambar'])): ?>
                                                    <br><img src="<?= base_url('uploads/bank_soal/'.$o['gambar']) ?>" class="h-16 mt-1 rounded border">
                                                <?php endif; ?>
                                            </div>
                                            <?php if($o['is_benar'] == 1): ?>
                                                <div class="ml-auto">
                                                    <span class="text-[10px] font-bold bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded uppercase">Kunci</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            
                            <?php elseif($s['tipe_soal'] == 'MENJODOHKAN'): ?>
                                <div class="mt-4">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pasangan Jawaban Benar:</p>
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm">
                                        <table class="w-full text-sm text-left">
                                            <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 font-bold uppercase text-[10px]">
                                                <tr>
                                                    <th class="p-3 border-r dark:border-slate-700 w-1/2">Pernyataan (Kiri)</th>
                                                    <th class="p-3 w-1/2">Pasangan (Kanan)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                                <?php foreach($opsi as $o): ?>
                                                <tr>
                                                    <td class="p-3 bg-white dark:bg-slate-800 border-r dark:border-slate-700 font-medium text-slate-700 dark:text-slate-300">
                                                        <?= $o['kode_opsi'] ?>
                                                        <?php if(!empty($o['gambar'])): ?>
                                                            <br><img src="<?= base_url('uploads/bank_soal/'.$o['gambar']) ?>" class="h-12 mt-1 rounded border">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="p-3 bg-emerald-50 dark:bg-emerald-900/10 text-emerald-700 dark:text-emerald-400 font-bold">
                                                        <i class="fas fa-link mr-2 text-emerald-400"></i>
                                                        <?= $o['teks_opsi'] ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 italic">*Siswa harus memasangkan pernyataan di kolom kiri dengan jawaban yang tepat di kolom kanan.</p>
                                </div>

                            <?php elseif(in_array($s['tipe_soal'], ['ISIAN_SINGKAT', 'URAIAN'])): ?>
                                <?php 
                                    $kunci = $db->table('tbl_opsi_soal')->where('id_soal', $s['id'])->where('is_benar', 1)->get()->getRowArray();
                                ?>
                                <?php if($kunci): ?>
                                    <div class="mt-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                                        <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider mb-1">Kunci Jawaban:</p>
                                        <p class="text-emerald-800 dark:text-emerald-300 font-bold"><?= $kunci['teks_opsi'] ?></p>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>