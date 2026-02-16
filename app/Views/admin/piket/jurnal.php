<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="p-4 sm:ml-2">
    <div class="flex justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📒 Jurnal Piket Harian</h1>
            <p class="text-sm text-gray-500">Input guru berhalangan hadir dan informasi tugas kelas.</p>
        </div>
        <button onclick="document.getElementById('modalJurnal').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700">
            + TAMBAH JURNAL
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black border-b">
                <tr>
                    <th class="px-6 py-4">Guru Absen</th>
                    <th class="px-6 py-4">Keterangan</th>
                    <th class="px-6 py-4">Tugas</th>
                    <th class="px-6 py-4">Guru Pengganti</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($jurnal as $j): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-gray-800"><?= $j['nama_guru'] ?></td>
                    <td class="px-6 py-4 uppercase"><span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-[10px] font-bold"><?= $j['keterangan'] ?></span></td>
                    <td class="px-6 py-4 text-gray-600"><?= $j['tugas'] ?></td>
                    <td class="px-6 py-4 font-bold text-blue-600"><?= $j['nama_pengganti'] ?? '-' ?></td>
                    <td class="px-6 py-4 text-center">
                        <button class="text-rose-600 font-bold hover:underline">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($jurnal)): ?>
                    <tr><td colspan="5" class="py-10 text-center text-gray-400 font-bold">Belum ada laporan jurnal hari ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalJurnal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b flex justify-between items-center bg-indigo-50">
            <h3 class="font-bold text-indigo-800 uppercase text-sm">Input Guru Berhalangan</h3>
            <button onclick="document.getElementById('modalJurnal').classList.add('hidden')" class="text-gray-400 hover:text-red-500">✕</button>
        </div>
        <form action="<?= base_url('admin/piket/saveJurnal') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field(); ?>
            <div>
                <label class="text-[10px] font-black text-gray-500 uppercase block mb-1">Pilih Guru Absen</label>
                <select name="guru_id" class="w-full p-3 bg-gray-50 border rounded-xl text-sm font-bold" required>
                    <option value="">-- Cari Nama Guru --</option>
                    <?php foreach($guru as $g): ?>
                        <option value="<?= $g['id'] ?>"><?= $g['nama_lengkap'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase block mb-1">Keterangan</label>
                    <select name="keterangan" class="w-full p-3 bg-gray-50 border rounded-xl text-sm font-bold">
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Dinas Luar">Dinas Luar</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase block mb-1">Guru Pengganti</label>
                    <select name="guru_pengganti_id" class="w-full p-3 bg-gray-50 border rounded-xl text-sm font-bold">
                        <option value="">-- Tanpa Pengganti --</option>
                        <?php foreach($guru as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= $g['nama_lengkap'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-500 uppercase block mb-1">Tugas Untuk Siswa</label>
                <textarea name="tugas" rows="3" class="w-full p-3 bg-gray-50 border rounded-xl text-sm font-bold" placeholder="Tuliskan tugas yang harus dikerjakan siswa..."></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-black rounded-xl shadow-lg hover:bg-indigo-700 uppercase text-xs tracking-widest">Simpan Jurnal</button>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>