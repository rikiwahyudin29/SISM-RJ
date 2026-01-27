<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Pengeluaran Operasional</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Catat setiap uang keluar agar laporan harian seimbang.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="toggleMaster()" class="px-4 py-2 bg-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-300 transition-all">
                + Divisi / Jenis
            </button>
            <button onclick="openModal()" class="px-5 py-2 bg-rose-600 text-white rounded-xl font-bold text-sm hover:bg-rose-700 transition-all shadow-lg shadow-rose-500/30">
                <i class="fas fa-plus mr-1"></i> Catat Pengeluaran
            </button>
        </div>
    </div>

    <div id="masterArea" class="hidden grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <form action="<?= base_url('admin/keuangan/pengeluaran/master/simpan_divisi') ?>" method="post" class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 flex gap-2">
            <input type="text" name="nama" placeholder="Nama Divisi Baru..." required class="w-full border-slate-200 rounded-lg text-sm">
            <button type="submit" class="bg-slate-800 text-white px-3 py-2 rounded-lg text-xs font-bold">Simpan</button>
        </form>
        <form action="<?= base_url('admin/keuangan/pengeluaran/master/simpan_jenis') ?>" method="post" class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 flex gap-2">
            <input type="text" name="nama" placeholder="Jenis Pengeluaran Baru..." required class="w-full border-slate-200 rounded-lg text-sm">
            <button type="submit" class="bg-slate-800 text-white px-3 py-2 rounded-lg text-xs font-bold">Simpan</button>
        </form>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100 flex items-center gap-3 font-bold text-sm">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Divisi & Jenis</th>
                    <th class="p-4">Keterangan</th>
                    <th class="p-4 text-right">Nominal</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if(empty($pengeluaran)): ?>
                    <tr><td colspan="5" class="p-8 text-center text-slate-400">Belum ada data pengeluaran.</td></tr>
                <?php else: ?>
                    <?php foreach($pengeluaran as $p): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="p-4 text-slate-500 font-mono"><?= date('d/m/Y', strtotime($p['tanggal'])) ?></td>
                        <td class="p-4">
                            <div class="font-bold text-slate-800 dark:text-white"><?= esc($p['nama_divisi']) ?></div>
                            <div class="text-xs text-slate-400"><?= esc($p['nama_jenis']) ?></div>
                        </td>
                        <td class="p-4 text-slate-600">
                            <span class="font-bold"><?= esc($p['judul_pengeluaran']) ?></span>
                            <br><span class="text-xs text-slate-400"><?= esc($p['keterangan']) ?></span>
                        </td>
                        <td class="p-4 text-right font-bold text-rose-600">
                            Rp <?= number_format($p['nominal'], 0, ',', '.') ?>
                        </td>
                        <td class="p-4 text-center">
                            <button onclick="hapus(<?= $p['id'] ?>)" class="text-slate-400 hover:text-rose-600 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalForm" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg border border-slate-100 dark:border-slate-700 transform scale-100">
        <form action="<?= base_url('admin/keuangan/pengeluaran/simpan') ?>" method="post">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-t-2xl">
                <h3 class="font-black text-lg text-slate-800 dark:text-white">Input Pengeluaran</h3>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" class="w-full border-slate-200 rounded-lg text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nominal (Rp)</label>
                        <input type="text" name="nominal" onkeyup="formatRupiah(this)" required class="w-full border-slate-200 rounded-lg text-sm font-bold text-right text-rose-600" placeholder="0">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Divisi</label>
                        <select name="id_divisi" required class="w-full border-slate-200 rounded-lg text-sm">
                            <?php foreach($divisi as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= esc($d['nama_divisi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Pengeluaran</label>
                        <select name="id_jenis" required class="w-full border-slate-200 rounded-lg text-sm">
                            <?php foreach($jenis as $j): ?>
                                <option value="<?= $j['id'] ?>"><?= esc($j['nama_jenis']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Pengeluaran</label>
                    <input type="text" name="judul" required class="w-full border-slate-200 rounded-lg text-sm" placeholder="Contoh: Beli Kertas A4 5 Rim">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="2" class="w-full border-slate-200 rounded-lg text-sm" placeholder="Opsional..."></textarea>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 py-2 bg-slate-100 text-slate-600 rounded-lg font-bold">Batal</button>
                <button type="submit" class="flex-1 py-2 bg-rose-600 text-white rounded-lg font-bold hover:bg-rose-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<form id="formHapus" action="<?= base_url('admin/keuangan/pengeluaran/hapus') ?>" method="post" class="hidden">
    <input type="hidden" name="id" id="hapusId">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleMaster() {
        const el = document.getElementById('masterArea');
        el.classList.toggle('hidden');
        el.classList.toggle('grid');
    }

    function openModal() {
        document.getElementById('modalForm').classList.remove('hidden');
        document.getElementById('modalForm').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('modalForm').classList.add('hidden');
        document.getElementById('modalForm').classList.remove('flex');
    }

    function hapus(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data tidak bisa dikembalikan.",
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