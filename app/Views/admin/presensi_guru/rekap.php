<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Rekap Presensi Guru</h1>
        
        <form action="" method="get" class="flex gap-2">
            <input type="month" name="bulan" value="<?= $bulan ?>" class="px-4 py-2 border rounded-xl font-bold text-sm" onchange="this.form.submit()">
            <a href="<?= base_url('admin/presensi_guru/cetak_rekap?bulan='.$bulan) ?>" target="_blank" class="bg-rose-600 text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print PDF
            </a>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-100 dark:bg-slate-900 text-slate-600 font-bold uppercase">
                    <tr>
                        <th class="p-3 border sticky left-0 bg-slate-100 z-10 w-10">No</th>
                        <th class="p-3 border sticky left-10 bg-slate-100 z-10 w-48">Nama Guru</th>
                        <?php for($d=1; $d<=$jml_hari; $d++): ?>
                            <th class="p-1 border text-center w-8"><?= $d ?></th>
                        <?php endfor; ?>
                        <th class="p-2 border bg-emerald-50">H</th>
                        <th class="p-2 border bg-blue-50">I</th>
                        <th class="p-2 border bg-rose-50">S</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($guru as $i => $g): 
                        $h=0; $s=0; $iz=0; $a=0;
                    ?>
                    <tr class="hover:bg-slate-50">
                        <td class="p-3 border sticky left-0 bg-white z-10 text-center"><?= $i+1 ?></td>
                        <td class="p-3 border sticky left-10 bg-white z-10 font-bold"><?= $g['nama_guru'] ?></td>
                        
                        <?php for($d=1; $d<=$jml_hari; $d++): 
                            $st = $map[$g['id']][$d] ?? '-';
                            if($st == 'Hadir') $h++;
                            if($st == 'Sakit') $s++;
                            if($st == 'Izin') $iz++;
                            
                            $bg = ''; $txt = '';
                            if($st == 'Hadir') { $txt='•'; $bg='text-emerald-500 font-bold text-lg'; }
                            elseif($st == 'Sakit') { $txt='S'; $bg='bg-rose-100 text-rose-600 font-bold'; }
                            elseif($st == 'Izin') { $txt='I'; $bg='bg-blue-100 text-blue-600 font-bold'; }
                            elseif($st == 'Terlambat') { $txt='T'; $bg='bg-yellow-100 text-yellow-600 font-bold'; }
                        ?>
                            <td class="p-1 border text-center <?= ($txt!='•' && $txt!='') ? 'p-0' : '' ?>">
                                <div class="w-full h-full flex items-center justify-center <?= ($txt!='•') ? 'py-1 rounded '.$bg : $bg ?>">
                                    <?= $txt ?>
                                </div>
                            </td>
                        <?php endfor; ?>

                        <td class="p-2 border text-center font-bold bg-emerald-50"><?= $h ?></td>
                        <td class="p-2 border text-center font-bold bg-blue-50"><?= $iz ?></td>
                        <td class="p-2 border text-center font-bold bg-rose-50"><?= $s ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>