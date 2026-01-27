<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= base_url('admin/keuangan/jenis') ?>" class="w-10 h-10 flex items-center justify-center bg-white border rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white">Kelola Tagihan</h1>
                <p class="text-sm text-slate-500"><?= esc($info['nama_pos']) ?> • <?= esc($info['tahun_ajaran']) ?></p>
            </div>
        </div>
        
        <form method="get" class="flex items-center gap-2">
            <select name="kelas" onchange="this.form.submit()" class="border-2 border-slate-200 rounded-xl px-4 py-2 font-bold text-sm text-slate-700 focus:border-blue-500 outline-none">
                <option value="">-- Semua Kelas --</option>
                <?php foreach($kelas as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= ($filter == $k['id']) ? 'selected' : '' ?>>Kelas <?= esc($k['nama_kelas']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100 flex items-center gap-3 font-bold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="p-4">Nama Siswa</th>
                        <th class="p-4">Kelas</th>
                        <th class="p-4">Keterangan</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right bg-amber-50 dark:bg-amber-900/10 border-b border-amber-100 dark:border-amber-800/30 text-amber-700 w-48">Nominal (Edit)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php if(empty($tagihan)): ?>
                        <tr><td colspan="5" class="p-8 text-center text-slate-400">Belum ada tagihan. Silakan generate dulu.</td></tr>
                    <?php else: ?>
                        <?php foreach($tagihan as $t): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="p-4">
                                <div class="font-bold text-slate-800 dark:text-white"><?= esc($t['nama_lengkap']) ?></div>
                                <div class="text-xs text-slate-400 font-mono"><?= esc($t['nis']) ?></div>
                            </td>
                            <td class="p-4 text-slate-600"><?= esc($t['nama_kelas']) ?></td>
                            <td class="p-4 text-slate-600">
                                <span class="bg-slate-100 px-2 py-1 rounded text-xs font-bold text-slate-500">
                                    <?= esc($t['keterangan']) ?>
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <?php if($t['status_bayar'] == 'LUNAS'): ?>
                                    <span class="text-emerald-600 font-bold text-xs bg-emerald-50 px-2 py-1 rounded">LUNAS</span>
                                <?php else: ?>
                                    <span class="text-rose-600 font-bold text-xs bg-rose-50 px-2 py-1 rounded">BELUM</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="p-2 text-right bg-amber-50/50 dark:bg-amber-900/5 group-hover:bg-amber-100/50 transition-colors">
                                <?php if($t['status_bayar'] == 'LUNAS'): ?>
                                    <span class="font-bold text-slate-400 cursor-not-allowed" title="Sudah lunas, tidak bisa diubah">
                                        <?= number_format($t['nominal_tagihan'], 0, ',', '.') ?>
                                    </span>
                                <?php else: ?>
                                    <input type="text" 
                                           value="<?= number_format($t['nominal_tagihan'], 0, ',', '.') ?>" 
                                           class="nominal-edit w-full text-right bg-transparent border border-transparent hover:border-amber-300 focus:border-blue-500 focus:bg-white rounded px-2 py-1 font-bold text-slate-700 dark:text-slate-200 outline-none transition-all"
                                           data-id="<?= $t['id'] ?>"
                                           onkeyup="formatRupiah(this)"
                                           onchange="updateNominal(this)">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateNominal(input) {
        const id = input.getAttribute('data-id');
        const newVal = input.value;

        // Visual Feedback (Loading)
        input.classList.add('opacity-50', 'cursor-wait');
        
        const formData = new FormData();
        formData.append('pk', id);
        formData.append('value', newVal);

        fetch('<?= base_url('admin/keuangan/tagihan/update_nominal') ?>', {
            method: 'POST',
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => {
            if(!response.ok) throw new Error('Gagal update');
            return response.json();
        })
        .then(data => {
            // Sukses
            input.classList.remove('opacity-50', 'cursor-wait');
            input.classList.add('text-emerald-600');
            setTimeout(() => input.classList.remove('text-emerald-600'), 1000);
            
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 1500
            });
            Toast.fire({ icon: 'success', title: 'Tersimpan' });
        })
        .catch(error => {
            input.classList.remove('opacity-50', 'cursor-wait');
            input.classList.add('text-rose-600', 'border-rose-500');
            Swal.fire('Gagal', 'Gagal update nominal. Cek koneksi.', 'error');
        });
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