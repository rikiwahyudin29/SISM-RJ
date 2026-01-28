<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Log Aktivitas Keuangan
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Memantau siapa, kapan, dan dimana transaksi dilakukan.</p>
        </div>
        
        <div class="bg-blue-50 dark:bg-blue-900/20 px-4 py-2 rounded-xl border border-blue-100 dark:border-blue-800 flex items-center gap-2 text-blue-700 dark:text-blue-300 text-xs font-bold">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
            </span>
            Realtime Recording
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="p-4 w-40">Waktu Kejadian</th>
                        <th class="p-4 w-48">Pelaku (User)</th>
                        <th class="p-4">Aksi / Aktivitas</th>
                        <th class="p-4">Jejak Digital (IP & Lokasi)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php if(empty($logs)): ?>
                        <tr><td colspan="4" class="p-8 text-center text-slate-400 italic">Belum ada aktivitas tercatat.</td></tr>
                    <?php else: ?>
                        <?php foreach($logs as $l): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors group">
                            <td class="p-4 align-top">
                                <div class="font-bold text-slate-700 dark:text-slate-200">
                                    <?= date('d M Y', strtotime($l['created_at'])) ?>
                                </div>
                                <div class="text-xs font-mono text-slate-400 mt-1 bg-slate-100 dark:bg-slate-700 inline-block px-2 py-0.5 rounded">
                                    <?= date('H:i:s', strtotime($l['created_at'])) ?> WIB
                                </div>
                            </td>

                            <td class="p-4 align-top">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-500 uppercase shrink-0">
                                        <?= substr($l['nama_user'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 dark:text-white text-sm line-clamp-1">
                                            <?= esc($l['nama_user']) ?>
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded border 
                                                <?= ($l['role'] == 'admin') ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' ?>">
                                                <?= strtoupper($l['role']) ?>
                                            </span>
                                            <span class="text-[10px] text-slate-400">ID: <?= $l['user_id'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4 align-top">
                                <?php 
                                    // Highlight Merah jika ada kata MENGHAPUS / BATAL
                                    $isDanger = (stripos($l['aksi'], 'hapus') !== false || stripos($l['aksi'], 'batal') !== false);
                                    // Highlight Hijau jika kata SYSTEM / TRIPAY
                                    $isSystem = (stripos($l['aksi'], 'system') !== false || stripos($l['aksi'], 'tripay') !== false);
                                ?>
                                <div class="font-medium text-sm leading-relaxed 
                                    <?= $isDanger ? 'text-rose-600 font-bold' : ($isSystem ? 'text-blue-600 font-bold' : 'text-slate-700 dark:text-slate-300') ?>">
                                    
                                    <?php if($isDanger): ?>
                                        <span class="inline-block w-2 h-2 rounded-full bg-rose-500 mr-1 animate-pulse"></span>
                                    <?php endif; ?>
                                    
                                    <?= esc($l['aksi']) ?>
                                </div>
                            </td>

                            <td class="p-4 align-top">
                                <div class="flex flex-col gap-1.5 text-xs">
                                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                        <span class="font-mono font-bold bg-slate-100 dark:bg-slate-700 px-1.5 rounded"><?= esc($l['ip_address']) ?></span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 text-slate-500">
                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span><?= esc($l['lokasi']) ?></span>
                                    </div>

                                    <div class="flex items-center gap-2 text-slate-400 italic text-[10px] mt-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        <span class="truncate max-w-[150px]" title="<?= esc($l['device_info']) ?>">
                                            <?= esc(substr($l['device_info'], 0, 25)) ?>...
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-700 text-center text-xs text-slate-400">
            Menampilkan 200 aktivitas terakhir untuk keamanan sistem.
        </div>
    </div>
</div>
<?= $this->endSection() ?>