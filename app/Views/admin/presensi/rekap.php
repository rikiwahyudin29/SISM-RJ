<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Rekapitulasi Absensi</h1>
            <p class="text-sm text-slate-500">Persentase kehadiran siswa per kelas.</p>
        </div>
        
        <form action="" method="get" class="flex flex-wrap gap-2">
            <input type="month" name="bulan" value="<?= $bulan ?>" class="px-4 py-2 border rounded-xl font-bold text-sm outline-none focus:border-blue-500">
            
            <select name="id_kelas" required class="px-4 py-2 border rounded-xl font-bold text-sm outline-none focus:border-blue-500">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach($kelas as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= $filter_kelas == $k['id'] ? 'selected' : '' ?>><?= $k['nama_kelas'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/30">
                Tampilkan
            </button>
            
            <?php if(!empty($filter_kelas)): ?>
                <a href="<?= base_url('admin/presensi/cetak_rekap?bulan='.$bulan.'&id_kelas='.$filter_kelas) ?>" target="_blank" class="bg-rose-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-lg shadow-rose-500/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print PDF
                </a>
            <?php endif; ?>
        </form>
    </div>

    <?php if(empty($filter_kelas)): ?>
        <div class="p-10 text-center border-2 border-dashed border-slate-300 rounded-2xl">
            <p class="text-slate-400 font-bold text-lg">Silakan pilih Kelas dan Bulan terlebih dahulu.</p>
        </div>
    <?php else: ?>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-100 dark:bg-slate-900 text-slate-600 font-bold uppercase">
                    <tr>
                        <th class="p-3 border sticky left-0 bg-slate-100 dark:bg-slate-900 z-10 w-10">No</th>
                        <th class="p-3 border sticky left-10 bg-slate-100 dark:bg-slate-900 z-10 w-48">Nama Siswa</th>
                        
                        <?php for($d=1; $d<=$jml_hari; $d++): ?>
                            <th class="p-1 border text-center w-8"><?= $d ?></th>
                        <?php endfor; ?>
                        
                        <th class="p-2 border text-center bg-emerald-50 text-emerald-700">H</th>
                        <th class="p-2 border text-center bg-rose-50 text-rose-700">S</th>
                        <th class="p-2 border text-center bg-blue-50 text-blue-700">I</th>
                        <th class="p-2 border text-center bg-red-50 text-red-700">A</th>
                        <th class="p-2 border text-center bg-yellow-50 text-yellow-700">T</th>
                        <th class="p-2 border text-center bg-slate-200">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php foreach($data_rekap as $i => $s): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="p-3 border sticky left-0 bg-white dark:bg-slate-800 z-10 text-center font-bold"><?= $i+1 ?></td>
                        <td class="p-3 border sticky left-10 bg-white dark:bg-slate-800 z-10">
                            <div class="font-bold text-slate-800 dark:text-white"><?= esc($s['nama']) ?></div>
                        </td>

                        <?php for($d=1; $d<=$jml_hari; $d++): ?>
                            <?php 
                                $st = $s['harian'][$d];
                                $kode = ''; $cls = '';

                                if($st == 'Hadir')      { $kode = '•'; $cls = 'text-emerald-500 font-bold text-lg'; }
                                elseif($st == 'Sakit')  { $kode = 'S'; $cls = 'bg-rose-100 text-rose-600 font-bold'; }
                                elseif($st == 'Izin')   { $kode = 'I'; $cls = 'bg-blue-100 text-blue-600 font-bold'; }
                                elseif($st == 'Alpha')  { $kode = 'A'; $cls = 'bg-red-100 text-red-600 font-bold'; }
                                elseif($st == 'Terlambat') { $kode = 'T'; $cls = 'bg-yellow-100 text-yellow-600 font-bold'; }
                                else { $kode = '-'; $cls = 'text-slate-200'; }
                            ?>
                            <td class="p-1 border text-center <?= ($st!='Hadir' && $st!='-') ? 'p-0' : '' ?>">
                                <div class="w-full h-full flex items-center justify-center <?= ($st!='Hadir' && $st!='-') ? 'py-1 rounded '.$cls : $cls ?>">
                                    <?= $kode ?>
                                </div>
                            </td>
                        <?php endfor; ?>

                        <td class="p-2 border text-center font-bold bg-emerald-50"><?= $s['total']['H'] ?></td>
                        <td class="p-2 border text-center font-bold bg-rose-50"><?= $s['total']['S'] ?></td>
                        <td class="p-2 border text-center font-bold bg-blue-50"><?= $s['total']['I'] ?></td>
                        <td class="p-2 border text-center font-bold bg-red-50"><?= $s['total']['A'] ?></td>
                        <td class="p-2 border text-center font-bold bg-yellow-50"><?= $s['total']['T'] ?></td>
                        
                        <td class="p-2 border text-center font-black">
                            <?php 
                                $persen = $s['persen'];
                                $warnaPersen = 'text-emerald-600';
                                if($persen < 90) $warnaPersen = 'text-yellow-600';
                                if($persen < 75) $warnaPersen = 'text-red-600';
                            ?>
                            <span class="<?= $warnaPersen ?>"><?= $persen ?>%</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4 flex gap-4 text-xs font-bold text-slate-500">
        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> • : Hadir</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-yellow-100 border border-yellow-200"></span> T : Terlambat</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-rose-100 border border-rose-200"></span> S : Sakit</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-blue-100 border border-blue-200"></span> I : Izin</span>
        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-red-100 border border-red-200"></span> A : Alpha</span>
    </div>

    <?php endif; ?>
</div>
<?= $this->endSection() ?>