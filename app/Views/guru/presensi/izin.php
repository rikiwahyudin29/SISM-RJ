<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Izin & Dinas Luar</h1>
        <p class="text-slate-500 text-sm">Formulir ketidakhadiran guru.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-4 mb-6 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-sm">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="p-4 mb-6 bg-rose-100 text-rose-700 rounded-xl font-bold text-sm">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 mb-8">
        <form action="<?= base_url('guru/presensi/ajukan') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 rounded-xl border bg-slate-50 dark:bg-slate-900 font-bold text-sm outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-3 rounded-xl border bg-slate-50 dark:bg-slate-900 font-bold text-sm outline-none">
                        <option value="Sakit">😷 Sakit</option>
                        <option value="Izin">📩 Izin Pribadi</option>
                        <option value="Dinas Luar">💼 Dinas Luar</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Bukti / Surat Tugas</label>
                <input type="file" name="bukti" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-4 py-3 rounded-xl border bg-slate-50 dark:bg-slate-900 font-bold text-sm outline-none" placeholder="Keterangan..." required></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-500/30">
                Kirim Laporan
            </button>
        </form>
    </div>

    <h3 class="font-bold text-slate-800 dark:text-white mb-4 text-lg">Riwayat Anda</h3>
    <div class="space-y-4">
        <?php foreach($riwayat as $r): ?>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div>
                <div class="font-bold text-slate-800 dark:text-white"><?= date('d F Y', strtotime($r['tanggal'])) ?></div>
                <div class="text-xs text-slate-500">Status: <b><?= $r['status_kehadiran'] ?></b> • "<?= esc($r['keterangan']) ?>"</div>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold <?= ($r['status_verifikasi'] == 'Disetujui') ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' ?>">
                    <?= $r['status_verifikasi'] ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>