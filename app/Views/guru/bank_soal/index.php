<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="mb-4 md:mb-0">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Bank Soal & Ujian</h1>
            <p class="text-sm text-gray-500">Kelola paket soal dan target peserta ujian.</p>
        </div>
        
        <button type="button" data-modal-target="modal-bank" data-modal-toggle="modal-bank" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-lg dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <i class="fas fa-plus mr-2"></i> Buat Bank Soal Baru
        </button>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 m-4 shadow-sm">
        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(empty($bank)): ?>
            <div class="col-span-full flex flex-col items-center justify-center p-10 text-gray-500 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-4 rounded-full bg-blue-50 dark:bg-blue-900/20 mb-4">
                    <i class="fas fa-folder-open text-4xl text-blue-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada Bank Soal</h3>
                <p class="mb-4">Silakan buat paket soal ujian baru untuk memulai.</p>
            </div>
        <?php else: ?>
            <?php foreach($bank as $b): ?>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition-shadow flex flex-col justify-between h-full">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                            <?= $b['nama_mapel'] ?>
                        </span>
                        <?php if($b['status'] == 'Aktif'): ?>
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded border border-green-400">AKTIF</span>
                        <?php else: ?>
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-0.5 rounded border border-gray-400">DRAFT</span>
                        <?php endif; ?>
                    </div>
                    
                    <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white leading-tight">
                        <?= $b['judul_ujian'] ?>
                    </h5>
                    
                    <div class="space-y-2 mt-4">
                        <div class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-users mt-1 mr-2 w-4 text-center"></i>
                            <span class="line-clamp-2" title="<?= $b['nama_kelas'] ?>">
                                <?= !empty($b['nama_kelas']) ? $b['nama_kelas'] : '<span class="italic text-red-500">Belum set target kelas</span>' ?>
                            </span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-list-ol mr-2 w-4 text-center"></i>
                            <span><b><?= $b['jumlah_soal'] ?></b> Butir Soal</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 pb-5 pt-0 mt-auto">
                    <hr class="h-px mb-4 bg-gray-200 border-0 dark:bg-gray-700">
                    <div class="grid grid-cols-5 gap-2">
                        <a href="<?= base_url('guru/bank_soal/kelola/' . $b['id']) ?>" class="col-span-4 text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-3 py-2 text-center flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i> Kelola Soal
                        </a>
                        
                       <a href="<?= base_url('guru/bank_soal/hapus/' . $b['id']) ?>" 
   onclick="return confirm('Yakin hapus? Semua data soal di dalamnya akan hilang permanen!')" 
   class="col-span-1 text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-2 text-center flex items-center justify-center shadow-sm transition-colors" 
   title="Hapus Bank Soal">
    <i class="fas fa-trash-alt mr-1"></i> 
    <span class="hidden md:inline">Hapus</span> </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div id="modal-bank" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center bg-gray-900/50 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-4xl h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow-2xl dark:bg-gray-800">
            <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-layer-group text-blue-600"></i> Setting Ujian Baru
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="modal-bank">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            
            <form action="<?= base_url('guru/bank_soal/simpan') ?>" method="POST" class="p-6">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="space-y-5">
                        <div class="border-b pb-2 mb-2">
                            <h4 class="font-bold text-gray-800 dark:text-white">1. Informasi Umum</h4>
                            <p class="text-xs text-gray-500">Identitas paket soal ujian.</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Judul Ujian / Nama Paket</label>
                            <input type="text" name="judul_ujian" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Contoh: Penilaian Harian Matematika Bab 1" required>
                        </div>
                        
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Mata Pelajaran</label>
                            <select name="id_mapel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <?php foreach($mapel as $m): ?>
                                    <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Status Publikasi</label>
                            <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="Tidak Aktif">Draft (Edit Dulu)</option>
                                <option value="Aktif">Aktif (Siap Ujian)</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Pilih 'Draft' jika soal belum selesai dibuat.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="border-b pb-2 mb-2 flex justify-between items-end">
                            <div>
                                <h4 class="font-bold text-gray-800 dark:text-white">2. Target Peserta</h4>
                                <p class="text-xs text-gray-500">Siapa saja yang ikut ujian ini?</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 bg-blue-50 p-3 rounded-lg border border-blue-100 dark:bg-gray-700 dark:border-gray-600">
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Filter Jurusan</label>
                                <select id="filterJurusan" class="bg-white border text-xs rounded p-1.5 w-full dark:bg-gray-600 dark:text-white dark:border-gray-500">
                                    <option value="all">Semua Jurusan</option>
                                    <?php foreach($jurusan as $j): ?>
                                        <option value="<?= $j['id'] ?>"><?= $j['nama_jurusan'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Filter Angkatan</label>
                                <select id="filterAngkatan" class="bg-white border text-xs rounded p-1.5 w-full dark:bg-gray-600 dark:text-white dark:border-gray-500">
                                    <option value="all">Semua Kelas</option>
                                    <option value="X ">Kelas X</option>
                                    <option value="XI ">Kelas XI</option>
                                    <option value="XII ">Kelas XII</option>
                                </select>
                            </div>
                        </div>

                        <div class="h-60 overflow-y-auto border border-gray-300 rounded-lg p-3 bg-gray-50 custom-scrollbar dark:bg-gray-700 dark:border-gray-600">
                            <div class="grid grid-cols-2 gap-2" id="containerKelas">
                                <?php foreach($kelas as $k): ?>
                                    <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600 item-kelas transition-colors cursor-pointer" 
                                         data-jurusan="<?= $k['id_jurusan'] ?>" 
                                         data-nama="<?= $k['nama_kelas'] ?>"
                                         onclick="document.getElementById('chk_<?= $k['id'] ?>').click()">
                                         
                                        <input id="chk_<?= $k['id'] ?>" type="checkbox" name="target_kelas[]" value="<?= $k['id'] ?>" 
                                               class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                               onclick="event.stopPropagation()">
                                               
                                        <label for="chk_<?= $k['id'] ?>" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer select-none">
                                            <?= $k['nama_kelas'] ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div id="noDataMsg" class="hidden flex flex-col items-center justify-center h-full text-gray-400">
                                <i class="fas fa-search mb-2"></i>
                                <span class="text-xs">Tidak ada kelas yang sesuai filter.</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 italic">Klik nama kelas untuk mencentang.</span>
                            <div class="space-x-2">
                                <button type="button" id="btnPilihSemua" class="text-blue-600 hover:underline font-bold">Pilih Semua yg Tampil</button>
                                <span class="text-gray-300">|</span>
                                <button type="button" id="btnResetPilih" class="text-red-600 hover:underline">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <button data-modal-toggle="modal-bank" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Batal</button>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow-lg">
                        <i class="fas fa-save mr-2"></i> Simpan & Lanjut Kelola Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const filterJurusan = document.getElementById('filterJurusan');
        const filterAngkatan = document.getElementById('filterAngkatan');
        const itemsKelas = document.querySelectorAll('.item-kelas');
        const noDataMsg = document.getElementById('noDataMsg');
        const btnPilihSemua = document.getElementById('btnPilihSemua');
        const btnResetPilih = document.getElementById('btnResetPilih');

        function filterKelas() {
            const valJurusan = filterJurusan.value;
            const valAngkatan = filterAngkatan.value;
            let visibleCount = 0;

            itemsKelas.forEach(item => {
                const itemJurusan = item.getAttribute('data-jurusan');
                const itemNama = item.getAttribute('data-nama'); // Misal "X RPL 1"
                
                let showJurusan = (valJurusan === 'all' || itemJurusan === valJurusan);
                let showAngkatan = (valAngkatan === 'all' || itemNama.includes(valAngkatan));

                if (showJurusan && showAngkatan) {
                    item.classList.remove('hidden');
                    item.classList.add('flex');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                    item.classList.remove('flex');
                }
            });

            if(visibleCount === 0) {
                noDataMsg.classList.remove('hidden');
            } else {
                noDataMsg.classList.add('hidden');
            }
        }

        // Event Listener Filter
        filterJurusan.addEventListener('change', filterKelas);
        filterAngkatan.addEventListener('change', filterKelas);

        // Event Pilih Semua (Hanya yang sedang tampil/terfilter)
        btnPilihSemua.addEventListener('click', function() {
            itemsKelas.forEach(item => {
                if (!item.classList.contains('hidden')) {
                    const checkbox = item.querySelector('input[type="checkbox"]');
                    checkbox.checked = true;
                }
            });
        });

        // Event Reset
        btnResetPilih.addEventListener('click', function() {
            itemsKelas.forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                checkbox.checked = false;
            });
        });
    });
</script>

<?= $this->endSection() ?>