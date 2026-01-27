<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Keuangan Saya</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Cek tagihan sekolah dan riwayat pembayaran.</p>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-4 p-4 bg-rose-50 text-rose-600 rounded-xl border border-rose-100 flex items-center gap-2 font-bold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-indigo-600 to-blue-500 rounded-3xl p-6 text-white shadow-xl shadow-blue-500/30 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4">
                     <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39h-2.01c-.06-.89-.48-1.54-2.79-1.54-2.12 0-2.33 1.02-2.33 1.55 0 .77.57 1.51 2.67 2.07 2.87.75 4.19 1.97 4.19 3.71 0 1.13-1.39 2.8-4.04 3.22z"></path></svg>
                </div>
                
                <p class="text-indigo-100 text-sm font-medium mb-1">Total Tunggakan Saya</p>
                <h2 class="text-3xl font-black mb-6">Rp <?= number_format($tunggakan, 0, ',', '.') ?></h2>
                
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-xs text-indigo-200 uppercase font-bold tracking-wider">Siswa</p>
                        <p class="font-bold text-lg"><?= esc($siswa['nama_lengkap']) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="p-4 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-slate-800 dark:text-white">Tagihan Belum Lunas</h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700 max-h-[400px] overflow-y-auto">
                    <?php if(empty($tagihan)): ?>
                        <div class="p-8 text-center text-slate-400">Tidak ada tagihan.</div>
                    <?php else: ?>
                        <?php foreach($tagihan as $t): 
                            $sisa = $t['nominal_tagihan'] - $t['nominal_terbayar'];
                        ?>
                        <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-xs font-bold text-blue-600 uppercase"><?= $t['nama_pos'] ?></p>
                                    <p class="font-bold text-sm text-slate-800 dark:text-white"><?= $t['keterangan'] ?></p>
                                </div>
                                <span class="text-xs font-bold text-rose-500">BELUM LUNAS</span>
                            </div>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm text-slate-500">Sisa Bayar</span>
                                <span class="font-bold text-lg text-slate-700 dark:text-white">Rp <?= number_format($sisa, 0, ',', '.') ?></span>
                            </div>
                            <button onclick="openPaymentModal(<?= $t['id'] ?>, '<?= $t['nama_pos'] ?> - <?= $t['keterangan'] ?>', <?= $sisa ?>)" 
                                    class="w-full py-2 bg-slate-800 text-white text-sm font-bold rounded-xl hover:bg-slate-900 transition-all flex items-center justify-center gap-2">
                                <span>Bayar Online</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
             <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 h-full">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Riwayat Transaksi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 uppercase text-xs font-bold">
                            <tr>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4">Info</th>
                                <th class="p-4 text-right">Total</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            <?php foreach($riwayat as $r): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 text-slate-500"><?= date('d M Y H:i', strtotime($r['created_at'])) ?></td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-800 dark:text-white"><?= $r['nama_pos'] ?></div>
                                    <div class="text-xs text-slate-500"><?= $r['payment_type'] ?></div>
                                </td>
                                <td class="p-4 text-right font-bold text-slate-700">Rp <?= number_format($r['total_bayar'] ?? $r['jumlah_bayar'], 0, ',', '.') ?></td>
                                <td class="p-4 text-center">
                                    <?php if($r['status_transaksi'] == 'SUCCESS'): ?>
                                        <span class="bg-emerald-100 text-emerald-600 px-2 py-1 rounded text-[10px] font-bold">LUNAS</span>
                                    <?php elseif($r['status_transaksi'] == 'UNPAID'): ?>
                                        <span class="bg-amber-100 text-amber-600 px-2 py-1 rounded text-[10px] font-bold">MENUNGGU</span>
                                    <?php else: ?>
                                        <span class="bg-rose-100 text-rose-600 px-2 py-1 rounded text-[10px] font-bold"><?= $r['status_transaksi'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-center">
                                    <?php if($r['status_transaksi'] == 'UNPAID' && !empty($r['checkout_url'])): ?>
                                        <a href="<?= $r['checkout_url'] ?>" target="_blank" class="text-blue-600 font-bold text-xs underline">Bayar</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4 transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md border border-slate-100 dark:border-slate-700 transform scale-95 transition-transform overflow-hidden flex flex-col max-h-[90vh]" id="paymentContent">
        
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex justify-between items-center shrink-0">
            <div>
                <h3 class="font-bold text-lg text-slate-800 dark:text-white">Pilih Metode Pembayaran</h3>
                <p class="text-xs text-slate-500" id="modalSubtitle">Total: Rp 0</p>
            </div>
            <button onclick="closePaymentModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="<?= base_url('siswa/keuangan/bayar_online') ?>" method="post" class="flex flex-col flex-1 overflow-hidden">
            <input type="hidden" name="id_tagihan" id="payIdTagihan">
            
            <div class="overflow-y-auto p-2 space-y-2 flex-1 custom-scrollbar bg-slate-50/50">
                <?php if(empty($channels)): ?>
                    <p class="text-center p-4 text-rose-500 text-xs">Gagal memuat channel pembayaran. Cek konfigurasi API Key.</p>
                <?php else: ?>
                    <?php foreach($channels as $ch): ?>
                        <label class="flex items-center gap-4 p-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:border-blue-500 hover:shadow-md transition-all group relative">
                            <input type="radio" name="method" value="<?= $ch['code'] ?>" required class="peer sr-only">
                            
                            <div class="w-12 h-8 flex items-center justify-center bg-white rounded border border-slate-100 p-1">
                                <img src="<?= $ch['icon_url'] ?>" alt="<?= $ch['name'] ?>" class="max-w-full max-h-full">
                            </div>

                            <div class="flex-1">
                                <p class="font-bold text-sm text-slate-700 dark:text-white group-hover:text-blue-600"><?= $ch['name'] ?></p>
                                <p class="text-[10px] text-slate-400">Biaya Admin: Rp <?= number_format($ch['total_fee']['flat'], 0, ',', '.') ?></p>
                            </div>

                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center text-white opacity-0 peer-checked:opacity-100 transition-opacity absolute right-3 top-1/2 -translate-y-1/2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            
                            <div class="absolute inset-0 border-2 border-blue-600 rounded-xl opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 shrink-0">
                <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all">
                    Lanjut Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('paymentModal');
    const modalContent = document.getElementById('paymentContent');

    function openPaymentModal(id, title, nominal) {
        document.getElementById('payIdTagihan').value = id;
        document.getElementById('modalSubtitle').innerText = title + ' - Rp ' + new Intl.NumberFormat('id-ID').format(nominal);

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => { modal.classList.remove('opacity-0'); modalContent.classList.remove('scale-95'); modalContent.classList.add('scale-100'); }, 10);
    }

    function closePaymentModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
    }
</script>

<?= $this->endSection() ?>