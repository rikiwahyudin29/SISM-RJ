<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Pengeluaran Operasional</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Catat dan pantau arus kas keluar sekolah.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openModal('divisi')" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs hover:bg-slate-200 transition-colors">
                + Divisi
            </button>
            <button onclick="openModal('jenis')" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs hover:bg-slate-200 transition-colors">
                + Jenis
            </button>
            
            <button onclick="openModal('tambah')" class="group flex items-center gap-2 px-5 py-2.5 bg-rose-600 text-white rounded-xl font-bold text-sm hover:bg-rose-700 transition-all shadow-lg shadow-rose-500/30 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Catat Pengeluaran
            </button>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-xl border border-emerald-100 dark:border-emerald-800 flex items-center gap-3 font-bold text-sm">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 uppercase text-xs font-bold">
                <tr>
                    <th class="p-4 w-12 text-center">No</th>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Judul & Keterangan</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4 text-right">Nominal</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if(empty($pengeluaran)): ?>
                    <tr><td colspan="6" class="p-8 text-center text-slate-400 italic">Belum ada data pengeluaran.</td></tr>
                <?php else: ?>
                    <?php foreach($pengeluaran as $i => $p): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors group">
                        <td class="p-4 text-center font-mono text-slate-400"><?= $i + 1 ?></td>
                        <td class="p-4 font-mono text-slate-600 dark:text-slate-300">
                            <?= date('d/m/Y', strtotime($p['tanggal'])) ?>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-slate-800 dark:text-white"><?= esc($p['judul_pengeluaran']) ?></div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-xs"><?= esc($p['keterangan']) ?></div>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                <?= esc($p['nama_divisi']) ?>
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100 ml-1">
                                <?= esc($p['nama_jenis']) ?>
                            </span>
                        </td>
                        <td class="p-4 text-right font-bold text-rose-600 dark:text-rose-400">
                            Rp <?= number_format($p['nominal'], 0, ',', '.') ?>
                        </td>
                        <td class="p-4 text-center">
                            <button onclick="hapusPengeluaran(<?= $p['id'] ?>)" class="text-slate-400 hover:text-rose-600 transition-colors" title="Hapus Data">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg border border-slate-100 dark:border-slate-700 transform scale-95 transition-transform" id="modalContent">
        <form action="<?= base_url('admin/keuangan/pengeluaran/simpan') ?>" method="post">
            
            <?= csrf_field() ?>
            
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-t-2xl flex justify-between items-center">
                <h3 class="font-black text-lg text-slate-800 dark:text-white">Catat Pengeluaran</h3>
                <button type="button" onclick="closeModal('tambah')" class="text-slate-400 hover:text-rose-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full border-2 border-slate-200 rounded-xl px-4 py-2 font-bold focus:border-rose-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nominal (Rp)</label>
                        <input type="text" name="nominal" onkeyup="formatRupiah(this)" required class="w-full border-2 border-slate-200 rounded-xl px-4 py-2 font-bold text-right focus:border-rose-500 outline-none" placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Pengeluaran</label>
                    <input type="text" name="judul" required class="w-full border-2 border-slate-200 rounded-xl px-4 py-2 font-bold focus:border-rose-500 outline-none" placeholder="Contoh: Beli ATK Kantor">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Divisi</label>
                        <select name="id_divisi" required class="w-full border-2 border-slate-200 rounded-xl px-4 py-2 font-bold focus:border-rose-500 outline-none">
                            <option value="">Pilih...</option>
                            <?php foreach($divisi as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= $d['nama_divisi'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis</label>
                        <select name="id_jenis" required class="w-full border-2 border-slate-200 rounded-xl px-4 py-2 font-bold focus:border-rose-500 outline-none">
                            <option value="">Pilih...</option>
                            <?php foreach($jenis as $j): ?>
                                <option value="<?= $j['id'] ?>"><?= $j['nama_jenis'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan Detail</label>
                    <textarea name="keterangan" rows="2" class="w-full border-2 border-slate-200 rounded-xl px-4 py-2 font-medium focus:border-rose-500 outline-none" placeholder="Opsional..."></textarea>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 flex gap-3">
                <button type="button" onclick="closeModal('tambah')" class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 shadow-lg shadow-rose-500/30">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalMaster" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity opacity-0">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm border border-slate-100 transform scale-95 transition-transform" id="modalMasterContent">
        <form action="" method="post" id="formMaster">
            <?= csrf_field() ?>
            <div class="p-6">
                <h3 class="font-bold text-lg mb-4" id="titleMaster">Tambah Data</h3>
                <input type="text" name="nama" required class="w-full border-2 border-slate-200 rounded-xl px-4 py-2 font-bold focus:border-blue-500 outline-none" placeholder="Nama baru...">
                <div class="mt-4 flex gap-2">
                    <button type="button" onclick="closeModal('master')" class="flex-1 py-2 bg-slate-100 rounded-lg font-bold text-slate-500">Batal</button>
                    <button type="submit" class="flex-1 py-2 bg-blue-600 text-white rounded-lg font-bold">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<form id="formHapus" action="<?= base_url('admin/keuangan/pengeluaran/hapus') ?>" method="post" class="hidden">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="hapusId">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openModal(type) {
        if(type === 'tambah') {
            const m = document.getElementById('modalTambah');
            const c = document.getElementById('modalContent');
            m.classList.remove('hidden', 'flex'); m.classList.add('flex');
            setTimeout(() => { m.classList.remove('opacity-0'); c.classList.remove('scale-95'); c.classList.add('scale-100'); }, 10);
        } else {
            const m = document.getElementById('modalMaster');
            const c = document.getElementById('modalMasterContent');
            const f = document.getElementById('formMaster');
            const t = document.getElementById('titleMaster');
            
            m.classList.remove('hidden', 'flex'); m.classList.add('flex');
            setTimeout(() => { m.classList.remove('opacity-0'); c.classList.remove('scale-95'); c.classList.add('scale-100'); }, 10);

            if(type === 'divisi') {
                f.action = '<?= base_url('admin/keuangan/pengeluaran/simpan_divisi') ?>';
                t.innerText = 'Tambah Divisi Baru';
            } else {
                f.action = '<?= base_url('admin/keuangan/pengeluaran/simpan_jenis') ?>';
                t.innerText = 'Tambah Jenis Pengeluaran';
            }
        }
    }

    function closeModal(type) {
        if(type === 'tambah') {
            document.getElementById('modalTambah').classList.add('hidden', 'opacity-0');
        } else {
            document.getElementById('modalMaster').classList.add('hidden', 'opacity-0');
        }
    }

    function hapusPengeluaran(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data pengeluaran akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapusId').value = id;
                document.getElementById('formHapus').submit();
            }
        })
    }

    function formatRupiah(input) {
        let value = input.value.replace(/[^,\d]/g, '').toString();
        let split = value.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) { let separator = sisa ? '.' : ''; rupiah += separator + ribuan.join('.'); }
        input.value = rupiah;
    }
</script>

<?= $this->endSection() ?>