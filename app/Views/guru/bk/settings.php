<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="p-4 sm:ml-2">
    <div class="mt-14 max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">⚙️ Pengaturan Ambang Batas SP</h2>
            <form action="<?= base_url('guru/bk/save-settings') ?>" method="POST" class="space-y-6">
                <?= csrf_field(); ?>
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl mb-6">
                    <p class="text-sm text-blue-700 dark:text-blue-300 italic">Siswa memulai dengan 100 poin. Atur sisa poin minimal untuk memicu status SP.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Batas SP 1</label>
        <input type="number" name="sp_1" value="<?= $set->sp_1 ?>" class="w-full p-3 border rounded-xl dark:bg-slate-900 dark:text-white" required>
        <span class="text-[10px] text-gray-500 mt-1 block">Contoh: Sisa 75 Poin</span>
    </div>
    <div>
        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Batas SP 2</label>
        <input type="number" name="sp_2" value="<?= $set->sp_2 ?>" class="w-full p-3 border rounded-xl dark:bg-slate-900 dark:text-white" required>
    </div>
    <div>
        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Batas SP 3</label>
        <input type="number" name="sp_3" value="<?= $set->sp_3 ?>" class="w-full p-3 border rounded-xl dark:bg-slate-900 dark:text-white" required>
    </div>
</div>

                <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-all">SIMPAN PENGATURAN</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>