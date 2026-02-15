<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="mt-14 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">⚖️ Kedisiplinan Saya</h1>
        <p class="text-sm text-gray-500 font-medium">Pantau saldo poin dan riwayat perilaku Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-6 rounded-2xl shadow-lg text-white">
            <p class="text-xs font-bold uppercase tracking-wider opacity-80">Saldo Poin Saat Ini</p>
            <h2 class="text-5xl font-black mt-2"><?= $saldo ?></h2>
            <p class="text-[10px] mt-2 font-bold uppercase tracking-tighter text-blue-100">DARI TOTAL 100 POIN AWAL</p>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col justify-center">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Peringatan</p>
            <?php 
                $status = "AMAN"; $color = "text-emerald-500";
                if($saldo <= $setSp->sp_3) { $status = "SP 3 (DO)"; $color = "text-rose-600 animate-pulse"; }
                elseif($saldo <= $setSp->sp_2) { $status = "SP 2"; $color = "text-rose-500"; }
                elseif($saldo <= $setSp->sp_1) { $status = "SP 1"; $color = "text-amber-500"; }
            ?>
            <h2 class="text-2xl font-black mt-1 <?= $color ?> uppercase"><?= $status ?></h2>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col justify-center">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pelanggaran</p>
            <h2 class="text-2xl font-black mt-1 text-gray-800 dark:text-white"><?= $rekap->total_kasus ?> <span class="text-sm font-bold text-gray-400">Kejadian</span></h2>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="p-4 border-b dark:border-slate-700 bg-gray-50/50">
            <h3 class="font-bold text-gray-700 dark:text-white uppercase text-xs tracking-widest">Detail Riwayat Pelanggaran</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-white dark:bg-slate-800 text-gray-400 uppercase text-[10px] font-black border-b dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Jenis Pelanggaran</th>
                        <th class="px-6 py-4 text-center">Poin Berkurang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    <?php if(empty($riwayat)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-emerald-500 font-bold uppercase tracking-widest">
                                Selamat! Anda belum memiliki catatan pelanggaran. Pertahankan prestasimu! 🌟
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach($riwayat as $r): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                        <td class="px-6 py-4 text-gray-500 font-bold"><?= date('d M Y, H:i', strtotime($r['tanggal'])) ?></td>
                        <td class="px-6 py-4">
                            <div class="font-black text-gray-800 dark:text-white uppercase text-xs"><?= $r['nama_pelanggaran'] ?></div>
                            <div class="text-[10px] text-gray-400 font-bold mt-1 tracking-tight"><?= $r['catatan'] ?: 'Tanpa catatan' ?></div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-rose-50 text-rose-600 rounded font-black text-xs border border-rose-100">-<?= $r['poin'] ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>