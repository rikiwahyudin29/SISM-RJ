<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Ajukan Izin / Sakit</h1>
        <p class="text-slate-500 text-sm">Upload bukti surat dokter atau keterangan izin.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="p-4 mb-6 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="p-4 mb-6 bg-rose-100 text-rose-700 rounded-xl font-bold text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 mb-8">
        <form action="<?= base_url('siswa/presensi/ajukan') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 rounded-xl border bg-slate-50 dark:bg-slate-900 dark:border-slate-600 font-bold text-sm focus:border-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Pengajuan</label>
                    <select name="status" class="w-full px-4 py-3 rounded-xl border bg-slate-50 dark:bg-slate-900 dark:border-slate-600 font-bold text-sm focus:border-blue-500 outline-none">
                        <option value="Sakit">😷 Sakit (Surat Dokter)</option>
                        <option value="Izin">📩 Izin (Kepentingan Keluarga)</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Bukti Foto / Surat</label>
                <input type="file" name="bukti" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                <p class="text-[10px] text-slate-400 mt-1">*Wajib upload foto surat atau bukti lain.</p>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan Detail</label>
                <textarea name="keterangan" rows="2" class="w-full px-4 py-3 rounded-xl border bg-slate-50 dark:bg-slate-900 dark:border-slate-600 font-bold text-sm focus:border-blue-500 outline-none" placeholder="Contoh: Demam tinggi sejak semalam..." required></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-500/30 transition-all">
                Kirim Pengajuan
            </button>
        </form>
    </div>

    <h3 class="font-bold text-slate-800 dark:text-white mb-4 text-lg">Riwayat Pengajuan</h3>
    <div class="space-y-4">
        <?php if(empty($riwayat)): ?>
            <div class="p-8 text-center bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
                <p class="text-slate-400 italic text-sm">Belum ada riwayat pengajuan.</p>
            </div>
        <?php else: ?>
            <?php foreach($riwayat as $r): ?>
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700 flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl <?= ($r['status_kehadiran']=='Sakit') ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' ?>">
                        <?= ($r['status_kehadiran']=='Sakit') ? '😷' : '📩' ?>
                    </div>
                    <div>
                        <div class="font-bold text-slate-800 dark:text-white"><?= date('d F Y', strtotime($r['tanggal'])) ?></div>
                        <div class="text-xs text-slate-500 line-clamp-1">"<?= esc($r['keterangan']) ?>"</div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                    <?php if($r['bukti_izin']): ?>
                        <a href="<?= base_url('uploads/surat_izin/'.$r['bukti_izin']) ?>" target="_blank" class="text-xs font-bold text-blue-500 hover:underline">Lihat Bukti</a>
                    <?php endif; ?>
                    
                    <?php 
                        $badge = 'bg-slate-100 text-slate-500';
                        if($r['status_verifikasi'] == 'Disetujui') $badge = 'bg-emerald-100 text-emerald-600';
                        if($r['status_verifikasi'] == 'Ditolak') $badge = 'bg-red-100 text-red-600';
                    ?>
                    <span class="px-3 py-1 rounded-full text-xs font-bold <?= $badge ?>">
                        <?= $r['status_verifikasi'] ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>