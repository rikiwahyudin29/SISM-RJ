<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Laporan & Rekap Keuangan</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Pantau arus kas masuk dan status tunggakan siswa.</p>
    </div>

    <div class="flex gap-4 mb-6 border-b border-slate-200 dark:border-slate-700">
        <button onclick="switchTab('pemasukan')" id="tab-pemasukan" class="px-4 py-2 text-sm font-bold text-blue-600 border-b-2 border-blue-600 active-tab">Laporan Pemasukan</button>
        <button onclick="switchTab('tunggakan')" id="tab-tunggakan" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 dark:text-slate-400 border-b-2 border-transparent">Rekap Lunas & Tunggakan</button>
    </div>

    <div id="panel-pemasukan" class="space-y-6">
        
        <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
            <form action="" method="get" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Periode</label>
                    <select name="filter" class="w-full md:w-40 border-slate-200 rounded-lg text-sm font-bold" onchange="this.form.submit()">
                        <option value="harian" <?= $filter=='harian'?'selected':'' ?>>Harian</option>
                        <option value="mingguan" <?= $filter=='mingguan'?'selected':'' ?>>Mingguan</option>
                        <option value="bulanan" <?= $filter=='bulanan'?'selected':'' ?>>Bulanan</option>
                        <option value="tahunan" <?= $filter=='tahunan'?'selected':'' ?>>Tahunan</option>
                    </select>
                </div>
                
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="start" value="<?= $start ?>" class="w-full border-slate-200 rounded-lg text-sm font-bold">
                </div>
                
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" name="end" value="<?= $end ?>" class="w-full border-slate-200 rounded-lg text-sm font-bold">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-bold text-sm">Filter</button>
                    <a href="<?= base_url('admin/keuangan/laporan/cetak_transaksi?start='.$start.'&end='.$end) ?>" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg font-bold text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak
                    </a>
                </div>
            </form>
        </div>

       <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-emerald-500 rounded-2xl p-6 shadow-lg text-white">
                <p class="text-emerald-100 font-bold text-xs uppercase">Total Pemasukan</p>
                <h2 class="text-2xl font-black mt-1">Rp <?= number_format($total_masuk, 0, ',', '.') ?></h2>
                <div class="mt-2 text-xs bg-white/20 inline-block px-2 py-1 rounded"><?= count($transaksi) ?> Transaksi</div>
            </div>

            <div class="bg-rose-500 rounded-2xl p-6 shadow-lg text-white">
                <p class="text-rose-100 font-bold text-xs uppercase">Total Pengeluaran</p>
                <h2 class="text-2xl font-black mt-1">Rp <?= number_format($total_keluar, 0, ',', '.') ?></h2>
                <div class="mt-2 text-xs bg-white/20 inline-block px-2 py-1 rounded"><?= count($pengeluaran) ?> Transaksi</div>
            </div>

            <div class="bg-blue-600 rounded-2xl p-6 shadow-lg text-white relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-2 -translate-y-2">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39h-2.01c-.06-.89-.48-1.54-2.79-1.54-2.12 0-2.33 1.02-2.33 1.55 0 .77.57 1.51 2.67 2.07 2.87.75 4.19 1.97 4.19 3.71 0 1.13-1.39 2.8-4.04 3.22z"></path></svg>
                </div>
                <p class="text-blue-100 font-bold text-xs uppercase">Saldo Akhir (Surplus/Defisit)</p>
                <h2 class="text-3xl font-black mt-1">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></h2>
                <p class="text-xs text-blue-200 mt-1">Periode: <?= date('d/m/y', strtotime($start)) ?> - <?= date('d/m/y', strtotime($end)) ?></p>
            </div>
        </div>
            <div class="text-right">
                <span class="text-2xl font-bold"><?= count($transaksi) ?></span>
                <p class="text-xs text-emerald-100 uppercase">Transaksi</p>
            </div>
        </div>
        <div class="mt-8">
            <h3 class="font-bold text-slate-700 dark:text-white mb-4 flex items-center gap-2">
                <span class="w-2 h-6 bg-rose-500 rounded-full"></span>
                Rincian Pengeluaran
            </h3>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Divisi & Jenis</th>
                            <th class="p-4">Keterangan</th>
                            <th class="p-4 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <?php if(empty($pengeluaran)): ?>
                            <tr><td colspan="4" class="p-8 text-center text-slate-400">Tidak ada pengeluaran pada periode ini.</td></tr>
                        <?php else: ?>
                            <?php foreach($pengeluaran as $p): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="p-4 text-slate-500"><?= date('d/m/y', strtotime($p['tanggal'])) ?></td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-700 dark:text-white"><?= esc($p['nama_divisi']) ?></div>
                                    <div class="text-xs text-slate-400"><?= esc($p['nama_jenis']) ?></div>
                                </td>
                                <td class="p-4 text-slate-600">
                                    <span class="font-bold"><?= esc($p['judul_pengeluaran']) ?></span>
                                </td>
                                <td class="p-4 text-right font-bold text-rose-600">
                                    - Rp <?= number_format($p['nominal'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
<h3 class="font-bold text-slate-700 dark:text-white mb-4 flex items-center gap-2">
                <span class="w-2 h-6 bg-green-500 rounded-full"></span>
                Rincian pemasukan
            </h3>
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Siswa</th>
                        <th class="p-4">Pembayaran</th>
                        <th class="p-4 text-right">Jumlah Masuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($transaksi)): ?>
                        <tr><td colspan="4" class="p-8 text-center text-slate-400">Tidak ada transaksi pada periode ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($transaksi as $t): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 text-slate-500"><?= date('d/m/y H:i', strtotime($t['created_at'])) ?></td>
                            <td class="p-4">
                                <div class="font-bold text-slate-700 dark:text-white"><?= esc($t['nama_lengkap']) ?></div>
                                <div class="text-xs text-slate-400"><?= esc($t['nama_kelas']) ?></div>
                            </td>
                            <td class="p-4 text-slate-600">
                                <?= esc($t['nama_pos']) ?> - <?= esc($t['keterangan']) ?>
                            </td>
                            <td class="p-4 text-right font-bold text-emerald-600">
                                + Rp <?= number_format($t['jumlah_bayar'], 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="panel-tunggakan" class="space-y-6 hidden">
        
        <div class="flex justify-between items-center bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
            <div>
                <h3 class="font-bold text-slate-700 dark:text-white">Status Pembayaran Per Pos</h3>
                <p class="text-xs text-slate-400">Rekapitulasi total tagihan, uang masuk, dan tunggakan.</p>
            </div>
            <a href="<?= base_url('admin/keuangan/laporan/cetak_tunggakan') ?>" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg font-bold text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Rekap
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <?php foreach($rekap as $r): ?>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h4 class="font-black text-lg text-slate-800 dark:text-white"><?= esc($r['nama_pos']) ?></h4>
                        <span class="text-xs font-bold text-slate-400 uppercase"><?= esc($r['tipe_bayar']) ?> • <?= esc($r['tahun_ajaran']) ?></span>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs text-slate-400">Total Potensi</span>
                        <span class="font-black text-slate-700 dark:text-white">Rp <?= number_format($r['total_potensi_rupiah'], 0, ',', '.') ?></span>
                    </div>
                </div>
                
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-emerald-50 dark:bg-emerald-900/10 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800">
                        <p class="text-xs font-bold text-emerald-600 uppercase mb-2">Sudah Masuk (Lunas)</p>
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="text-2xl font-black text-emerald-700 dark:text-emerald-400"><?= $r['siswa_lunas'] ?></span>
                                <span class="text-xs font-bold text-emerald-500">Siswa</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-sm font-bold text-emerald-700 dark:text-emerald-400">Rp <?= number_format($r['uang_masuk'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-rose-50 dark:bg-rose-900/10 p-4 rounded-xl border border-rose-100 dark:border-rose-800">
                        <p class="text-xs font-bold text-rose-600 uppercase mb-2">Belum Bayar / Tunggakan</p>
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="text-2xl font-black text-rose-700 dark:text-rose-400"><?= $r['siswa_belum_lunas'] ?></span>
                                <span class="text-xs font-bold text-rose-500">Siswa</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-sm font-bold text-rose-700 dark:text-rose-400">Rp <?= number_format($r['uang_tunggakan'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 pb-4 text-center">
                    <a href="<?= base_url('admin/keuangan/tagihan/kelola/'.$r['id']) ?>" class="block w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-lg transition-colors">
                        Lihat Detail Siswa
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
    function switchTab(tabName) {
        // Hide All
        document.getElementById('panel-pemasukan').classList.add('hidden');
        document.getElementById('panel-tunggakan').classList.add('hidden');
        
        // Reset Style
        document.getElementById('tab-pemasukan').classList.remove('text-blue-600', 'border-blue-600');
        document.getElementById('tab-pemasukan').classList.add('text-slate-500', 'border-transparent');
        
        document.getElementById('tab-tunggakan').classList.remove('text-blue-600', 'border-blue-600');
        document.getElementById('tab-tunggakan').classList.add('text-slate-500', 'border-transparent');

        // Show Selected
        document.getElementById('panel-' + tabName).classList.remove('hidden');
        document.getElementById('tab-' + tabName).classList.add('text-blue-600', 'border-blue-600');
        document.getElementById('tab-' + tabName).classList.remove('text-slate-500', 'border-transparent');
    }
</script>

<?= $this->endSection() ?>