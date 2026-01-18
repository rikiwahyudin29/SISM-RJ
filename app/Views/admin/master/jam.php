<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Master Jam Pelajaran (Bel Sekolah)</h1>
        <p class="text-sm text-gray-500">Atur durasi per jam pelajaran dan waktu istirahat disini.</p>
    </div>
    
    <button type="button" onclick="resetForm()" data-modal-target="modal-jam" data-modal-toggle="modal-jam" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        + Tambah Jam
    </button>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 m-4">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="p-4">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3 text-center">Urutan</th>
                    <th class="px-6 py-3">Nama Sesi</th>
                    <th class="px-6 py-3">Waktu</th>
                    <th class="px-6 py-3 text-center">Tipe</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($jam)): ?>
                    <tr><td colspan="5" class="text-center p-4">Belum ada data jam.</td></tr>
                <?php else: ?>
                    <?php foreach($jam as $j): ?>
                    <tr class="<?= $j['is_istirahat'] ? 'bg-orange-50 dark:bg-orange-900/20' : 'bg-white dark:bg-gray-800' ?> border-b dark:border-gray-700 hover:bg-gray-50">
                        <td class="px-6 py-4 text-center font-bold"><?= $j['urutan'] ?></td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white"><?= $j['nama_jam'] ?></td>
                        <td class="px-6 py-4 font-mono">
                            <?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($j['is_istirahat']): ?>
                                <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded">ISTIRAHAT</span>
                            <?php else: ?>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">KBM (Belajar)</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick='editJam(<?= json_encode($j) ?>)' data-modal-target="modal-jam" data-modal-toggle="modal-jam" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                            <span class="mx-2">|</span>
                            <a href="<?= base_url('admin/jam/hapus/' . $j['id']) ?>" onclick="return confirm('Hapus data ini?')" class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modal-jam" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center bg-gray-900/50 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modal-title">
                    Tambah Jam
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="modal-jam">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            
            <form action="<?= base_url('admin/jam/simpan') ?>" method="POST" class="p-6 space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="id_jam">

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Urutan Ke-</label>
                    <input type="number" name="urutan" id="urutan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="1" required>
                </div>
                
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Sesi (Contoh: JP 1 / Istirahat)</label>
                    <input type="text" name="nama_jam" id="nama_jam" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                    </div>
                </div>

                <div class="flex items-center mb-4">
                    <input id="is_istirahat" name="is_istirahat" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="is_istirahat" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Set sebagai Jam Istirahat</label>
                </div>
                <p class="text-xs text-gray-500 mb-4">*Jika dicentang, jam ini tidak akan dihitung sebagai Jam Pelajaran (JP).</p>

                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Data</button>
            </form>
        </div>
    </div>
</div>

<script>
    function resetForm() {
        document.getElementById('id_jam').value = '';
        document.getElementById('urutan').value = '';
        document.getElementById('nama_jam').value = '';
        document.getElementById('jam_mulai').value = '';
        document.getElementById('jam_selesai').value = '';
        document.getElementById('is_istirahat').checked = false;
        document.getElementById('modal-title').innerText = 'Tambah Jam';
    }

    function editJam(data) {
        document.getElementById('id_jam').value = data.id;
        document.getElementById('urutan').value = data.urutan;
        document.getElementById('nama_jam').value = data.nama_jam;
        document.getElementById('jam_mulai').value = data.jam_mulai;
        document.getElementById('jam_selesai').value = data.jam_selesai;
        document.getElementById('is_istirahat').checked = (data.is_istirahat == 1);
        document.getElementById('modal-title').innerText = 'Edit Jam';
    }
</script>

<?= $this->endSection() ?>