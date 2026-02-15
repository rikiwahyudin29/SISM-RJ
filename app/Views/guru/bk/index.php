<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">⚖️ Dashboard Kedisiplinan</h1>
            <p class="text-sm text-gray-500 font-medium">Monitoring saldo poin dan status SP siswa secara real-time.</p>
        </div>
        
        <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
            <a href="<?= base_url('guru/bk/settings') ?>" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all flex items-center shadow-sm border border-slate-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                SETTING SP
            </a>

            <a href="<?= base_url('guru/bk/master') ?>" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-100 transition-all flex items-center border border-indigo-100 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                MASTER DATA
            </a>

            <button onclick="document.getElementById('modalLapor').classList.remove('hidden')" class="px-4 py-2 bg-rose-600 text-white rounded-xl font-bold hover:bg-rose-700 shadow-lg shadow-rose-600/30 transition-all flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                LAPOR KASUS
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-t-2xl border-t border-x border-gray-100 dark:border-slate-700 p-4 shadow-sm">
        <form action="<?= base_url('guru/bk') ?>" method="GET" class="flex flex-col md:flex-row justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 font-bold">Tampilkan</span>
                <select name="perPage" onchange="this.form.submit()" class="p-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-blue-500 font-bold">
                    <option value="10" <?= ($perPage == 10) ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= ($perPage == 25) ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= ($perPage == 50) ? 'selected' : '' ?>>50</option>
                </select>
                <span class="text-sm text-gray-500 font-bold">Data</span>
            </div>
            
            <div class="flex items-center gap-2">
                <select name="kelas" onchange="this.form.submit()" class="p-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-blue-500 w-full md:w-48 font-black uppercase">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($list_kelas as $kls): ?>
                        <option value="<?= $kls['id'] ?>" <?= ($filter_kelas == $kls['id']) ? 'selected' : '' ?>><?= $kls['nama_kelas'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="relative w-full md:w-64">
                    <input type="text" name="keyword" value="<?= $keyword ?>" placeholder="Cari nama atau NIS..." class="w-full p-2 pl-8 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-blue-500 font-bold">
                    <svg class="w-4 h-4 absolute left-2.5 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 uppercase text-[10px] font-black border-b dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4">Informasi Siswa</th>
                        <th class="px-6 py-4 text-center">Jumlah Kasus</th>
                        <th class="px-6 py-4 text-center">Saldo Poin</th>
                        <th class="px-6 py-4 text-center">Status Peringatan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    <?php if(!$filter_kelas && !$keyword): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <p class="text-lg font-bold uppercase tracking-tight">Pilih Kelas Terlebih Dahulu</p>
                                    <p class="text-sm font-medium">Gunakan filter kelas di atas untuk memantau data ribuan siswa Anda secara efisien.</p>
                                </div>
                            </td>
                        </tr>
                    <?php elseif(empty($rekap_siswa)): ?>
                        <tr><td colspan="5" class="px-6 py-10 text-center font-bold text-gray-400 uppercase">Data siswa tidak ditemukan.</td></tr>
                    <?php else: ?>
                        <?php foreach($rekap_siswa as $s): 
                            $saldo = 100 - $s['total_minus'];
                            $status_sp = "AMAN";
                            $badge_color = "bg-emerald-50 text-emerald-600 border-emerald-100";
                            
                            if($saldo <= $setSp->sp_3) {
                                $status_sp = "SP 3 (DO / KELUAR)";
                                $badge_color = "bg-rose-600 text-white border-rose-700 shadow-lg shadow-rose-600/30";
                            } elseif($saldo <= $setSp->sp_2) {
                                $status_sp = "SP 2 (PANGGIL ORTU)";
                                $badge_color = "bg-rose-100 text-rose-600 border-rose-200";
                            } elseif($saldo <= $setSp->sp_1) {
                                $status_sp = "SP 1 (TEGURAN)";
                                $badge_color = "bg-amber-100 text-amber-600 border-amber-200";
                            }
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-all">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800 dark:text-white"><?= $s['nama_lengkap'] ?></div>
                                <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1"><?= $s['nis'] ?> • <?= $s['nama_kelas'] ?></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-black text-gray-700 dark:text-gray-300"><?= $s['jumlah_kasus'] ?></span>
                                <span class="text-[10px] text-gray-400 font-black ml-1 uppercase">KASUS</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-xl font-black <?= $saldo <= $setSp->sp_2 ? 'text-rose-600' : 'text-emerald-600' ?>"><?= $saldo ?></span>
                                    <span class="text-[8px] uppercase font-black text-gray-400 tracking-tighter">POIN TERSISA</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase border <?= $badge_color ?>">
                                    <?= $status_sp ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="lihatDetailSiswa('<?= $s['id'] ?>')" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all border border-blue-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($pager): ?>
    <div class="bg-white dark:bg-slate-800 rounded-b-2xl border-b border-x border-gray-100 dark:border-slate-700 p-4 shadow-sm">
        <?= $pager->links('rekap', 'custom_pagination') ?>
    </div>
    <?php endif; ?>
</div>

<div id="modalLapor" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up border border-gray-100 dark:border-slate-700">
        <div class="px-6 py-4 border-b dark:border-slate-700 flex justify-between items-center bg-rose-50 dark:bg-rose-900/20">
            <h3 class="font-bold text-rose-800 dark:text-white flex items-center uppercase tracking-tight">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Input Pelanggaran Baru
            </h3>
            <button onclick="document.getElementById('modalLapor').classList.add('hidden')" class="text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="<?= base_url('guru/bk/save') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field(); ?>
            <div>
                <label class="text-[10px] font-black text-gray-500 uppercase mb-1.5 block">Nama Siswa Bermasalah</label>
                <select name="siswa_id" required class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-rose-500 font-bold uppercase">
                    <?php if(empty($all_siswa)): ?>
                        <option value="">-- Pilih Kelas Dahulu Di Dashboard --</option>
                    <?php else: ?>
                        <option value="">-- Pilih Nama Siswa --</option>
                        <?php foreach($all_siswa as $as): ?>
                            <option value="<?= $as['id'] ?>"><?= $as['nama_lengkap'] ?> (<?= $as['nis'] ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-500 uppercase mb-1.5 block">Kategori Pelanggaran</label>
                <select name="pelanggaran_id" required class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-rose-500 font-bold uppercase">
                    <option value="">-- Pilih Jenis Pelanggaran --</option>
                    <?php foreach($jenis as $j): ?>
                        <option value="<?= $j['id'] ?>">[<?= $j['kategori'] ?>] <?= $j['nama_pelanggaran'] ?> (Kurangi <?= $j['poin'] ?> Poin)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-500 uppercase mb-1.5 block">Kronologi / Catatan Guru</label>
                <textarea name="catatan" rows="3" class="w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-rose-500 font-bold" placeholder="Jelaskan secara singkat kronologi kejadian..."></textarea>
            </div>

            <button type="submit" class="w-full py-4 bg-rose-600 text-white font-black rounded-xl shadow-lg hover:bg-rose-700 transition-all flex justify-center items-center uppercase text-xs tracking-widest">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4h3m-3-4V5a2 2 0 012-2h1a2 2 0 012 2v2m-6 16h6m-6-4h6m-6-4h6"></path></svg>
                SIMPAN & KURANGI POIN
            </button>
        </form>
    </div>
</div>
<div id="modalDetail" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up border border-gray-100 dark:border-slate-700">
        <div class="px-6 py-4 border-b dark:border-slate-700 flex justify-between items-center bg-blue-50 dark:bg-blue-900/20">
            <h3 class="font-bold text-blue-800 dark:text-white flex items-center uppercase tracking-tight">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Detail Riwayat Pelanggaran
            </h3>
            <button onclick="document.getElementById('modalDetail').classList.add('hidden')" class="text-gray-400 hover:text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6 max-h-[60vh] overflow-y-auto">
            <table class="w-full text-sm text-left">
               <thead class="text-xs text-gray-400 uppercase font-black border-b dark:border-slate-700 bg-gray-50/50">
    <tr>
        <th class="px-4 py-3">Tanggal</th>
        <th class="px-4 py-3">Pelanggaran</th>
        <th class="px-4 py-3 text-center">Poin</th>
        <th class="px-4 py-3 text-center">Aksi</th> </tr>
</thead>
                <tbody id="isiDetail" class="divide-y divide-gray-100 dark:divide-slate-700">
    </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function lihatDetailSiswa(id) {
    const modal = document.getElementById('modalDetail');
    const tableBody = document.getElementById('isiDetail');
    
    tableBody.innerHTML = '<tr><td colspan="4" class="py-10 text-center font-bold text-gray-400">Memuat data...</td></tr>';
    modal.classList.remove('hidden');

    fetch('<?= base_url('guru/bk/detail-siswa') ?>/' + id)
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = '';
            
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="py-10 text-center font-bold text-emerald-500 uppercase">Belum ada riwayat.</td></tr>';
            } else {
                data.forEach(item => {
                    const row = `
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 border-b dark:border-slate-700 transition-colors">
                            <td class="px-4 py-4 font-bold text-gray-600 dark:text-gray-400 text-[10px]">${item.tanggal}</td>
                            <td class="px-4 py-4">
                                <div class="font-black text-gray-800 dark:text-white uppercase text-xs">${item.nama_pelanggaran}</div>
                                <div class="text-[10px] text-gray-400 font-bold">${item.catatan || '-'}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2 py-1 bg-rose-50 text-rose-600 rounded font-black text-xs border border-rose-100">-${item.poin}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="<?= base_url('guru/bk/delete') ?>/${item.id}" onclick="return confirm('Hapus pelanggaran ini? Sisa poin siswa akan dikembalikan.')" class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all border border-rose-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            }
        });
}
</script>

<?= $this->endSection(); ?>