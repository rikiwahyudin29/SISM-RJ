<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Monitoring Guru</h1>
            <p class="text-sm text-slate-500">Data kehadiran Guru & Pegawai hari ini.</p>
        </div>
        
        <div class="flex gap-2">
            <form action="" method="get">
                <input type="date" name="tanggal" value="<?= $tanggal ?>" class="px-4 py-2 border rounded-xl font-bold text-sm outline-none focus:border-blue-500" onchange="this.form.submit()">
            </form>
            
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-blue-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Input Manual
            </button>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-4 mb-4 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-sm">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="p-4 mb-4 bg-rose-100 text-rose-700 rounded-xl font-bold text-sm">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border-b border-emerald-100 dark:border-emerald-800 flex justify-between items-center">
                <h3 class="font-bold text-emerald-700 dark:text-emerald-400">Sudah Hadir / Absen (<?= count($hadir) ?>)</h3>
            </div>
            <table class="w-full text-sm text-left">
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php if(empty($hadir)): ?>
                        <tr><td class="p-6 text-center text-slate-400 italic">Belum ada yang absen hari ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($hadir as $h): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                            <td class="p-4">
                                <div class="font-bold text-slate-800 dark:text-white"><?= esc($h['nama_guru']) ?></div>
                                <div class="text-xs text-slate-400"><?= esc($h['nip']) ?></div>
                            </td>
                            <td class="p-4 text-right">
                                <span class="block font-mono font-bold text-slate-600 dark:text-slate-300"><?= $h['jam_masuk'] ?></span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full 
                                    <?= ($h['status_kehadiran']=='Hadir') ? 'bg-emerald-100 text-emerald-600' : 
                                       (($h['status_kehadiran']=='Terlambat') ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600') ?>">
                                    <?= $h['status_kehadiran'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-4 bg-slate-50 dark:bg-slate-700 border-b border-slate-100 dark:border-slate-600 flex justify-between items-center">
                <h3 class="font-bold text-slate-600 dark:text-slate-300">Belum Hadir (<?= count($alpha) ?>)</h3>
            </div>
            <table class="w-full text-sm text-left">
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php if(empty($alpha)): ?>
                        <tr><td class="p-6 text-center text-slate-400 italic">Semua guru sudah hadir! Mantap.</td></tr>
                    <?php else: ?>
                        <?php foreach($alpha as $a): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                            <td class="p-4">
                                <div class="font-bold text-slate-600 dark:text-slate-400"><?= esc($a['nama_guru']) ?></div>
                                <div class="text-xs text-slate-400"><?= esc($a['nip']) ?></div>
                            </td>
                            <td class="p-4 text-right">
                                <button onclick="absenkan(<?= $a['id'] ?>, '<?= esc($a['nama_guru']) ?>')" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100">
                                    Input Status
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalManual" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/70 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-2xl p-6">
        <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Input Presensi Manual</h3>
        
        <form action="<?= base_url('admin/presensi_guru/simpan_manual') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="tanggal" value="<?= $tanggal ?>">
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Guru</label>
                <select name="id_guru" id="pilihGuru" class="w-full px-4 py-2 border rounded-xl font-bold bg-slate-100" readonly>
                    </select>
                <select id="listSemuaGuru" class="hidden">
                    <?php foreach($alpha as $a): ?>
                        <option value="<?= $a['id'] ?>"><?= $a['nama_guru'] ?></option>
                    <?php endforeach; ?>
                     <?php foreach($hadir as $h): ?>
                        <option value="<?= $h['user_id'] ?>"><?= $h['nama_guru'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status Kehadiran</label>
                <select name="status" class="w-full px-4 py-2 border rounded-xl font-bold outline-none focus:border-blue-500">
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                    <option value="Dinas Luar">Dinas Luar</option>
                    <option value="Hadir">Hadir (Lupa Scan)</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan</label>
                <textarea name="keterangan" class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500" rows="2" placeholder="Cth: Menghadiri rapat MGMP..."></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg font-bold text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-bold text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        // Tampilkan dropdown full jika tombol "Input Manual" atas ditekan
        const select = document.getElementById('pilihGuru');
        const sumber = document.getElementById('listSemuaGuru');
        select.innerHTML = '<option value="">-- Pilih Guru --</option>' + sumber.innerHTML;
        select.removeAttribute('readonly');
        select.classList.remove('bg-slate-100');
        
        document.getElementById('modalManual').classList.remove('hidden');
        document.getElementById('modalManual').classList.add('flex');
    }

    function absenkan(id, nama) {
        // Set otomatis guru yang dipilih
        const select = document.getElementById('pilihGuru');
        select.innerHTML = `<option value="${id}" selected>${nama}</option>`;
        select.setAttribute('readonly', true);
        select.classList.add('bg-slate-100');

        document.getElementById('modalManual').classList.remove('hidden');
        document.getElementById('modalManual').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('modalManual').classList.add('hidden');
        document.getElementById('modalManual').classList.remove('flex');
    }
</script>

<?= $this->endSection() ?>