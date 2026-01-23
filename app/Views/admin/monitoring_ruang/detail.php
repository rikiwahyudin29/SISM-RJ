<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="p-1 sm:ml-1">
    
    <div class="flex justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $ruang['nama_ruangan'] ?></h1>
            <p class="text-sm text-gray-500">Monitoring Realtime • <?= date('d M Y') ?></p>
        </div>
        <a href="<?= base_url('admin/monitoringruang') ?>" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-lg dark:text-blue-100 dark:bg-blue-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Belum Ujian</p>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white"><?= $stats['belum'] ?></h3>
            </div>
        </div>

        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-lg dark:text-purple-100 dark:bg-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Sedang Ujian</p>
                <h3 class="text-xl font-bold text-purple-600 dark:text-purple-400"><?= $stats['sedang'] ?></h3>
            </div>
        </div>

        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-lg dark:text-green-100 dark:bg-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</p>
                <h3 class="text-xl font-bold text-green-600 dark:text-green-400"><?= $stats['selesai'] ?></h3>
            </div>
        </div>

        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-lg dark:text-orange-100 dark:bg-orange-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <div class="overflow-hidden">
                <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Mapel / Ujian</p>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate" title="<?= $siswa[0]['judul_ujian'] ?? '-' ?>">
                    <?= $siswa[0]['judul_ujian'] ?? '-' ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 bg-white dark:bg-slate-800 p-4 rounded-t-xl border-b border-gray-200 dark:border-slate-700">
        
        <div class="flex items-center">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="toggleRefresh" class="sr-only peer" checked>
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Auto Refresh</span>
            </label>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openTimeModal()" type="button" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:focus:ring-yellow-900" title="Tambah Waktu">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </button>
            
            <button onclick="confirmAction('unlock')" type="button" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Buka Kunci Login">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
            </button>

            <button onclick="confirmAction('stop')" type="button" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" title="Paksa Selesai">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
            </button>

            <button onclick="confirmAction('reset')" type="button" class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" title="Reset Ujian">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
    </div>

    <div class="relative overflow-x-auto bg-white dark:bg-slate-800 shadow-md rounded-b-xl pb-4 min-h-[400px]">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4 w-4">
                        <div class="flex items-center">
                            <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-all" class="sr-only">checkbox</label>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">No PC</th>
                    <th scope="col" class="px-6 py-3">Identitas Siswa</th>
                    <th scope="col" class="px-6 py-3">Jawaban</th>
                    <th scope="col" class="px-6 py-3">Nilai</th>
                    <th scope="col" class="px-6 py-3">Lama Ujian</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($siswa)): ?>
                    <tr><td colspan="8" class="text-center py-6 text-gray-400">Ruangan ini kosong.</td></tr>
                <?php else: ?>
                    <?php foreach($siswa as $s): ?>
                        <tr class="bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors">
                            <td class="w-4 p-4">
                                <div class="flex items-center">
                                    <input type="checkbox" value="<?= $s['siswa_id'] ?>" class="chk-siswa w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                <?= $s['no_komputer'] ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 dark:text-white"><?= $s['nama_lengkap'] ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?= $s['nis'] ?> • <?= $s['nama_kelas'] ?></div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <?php if($s['status']): ?>
                                    <div class="flex space-x-2">
                                        <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                            <?= $s['jml_benar'] ?> <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </span>
                                        <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                            <?= $s['jml_salah'] ?> <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-300">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                <?= $s['nilai_sementara'] > 0 ? number_format($s['nilai_sementara'], 2) : '-' ?>
                            </td>

                            <td class="px-6 py-4 text-xs font-mono">
                                <?php 
                                    if($s['waktu_mulai'] && $s['waktu_selesai']) {
                                        $diff = strtotime($s['waktu_selesai']) - strtotime($s['waktu_mulai']);
                                        echo floor($diff / 60) . 'm ' . ($diff % 60) . 'd';
                                    } elseif($s['waktu_mulai']) {
                                        echo '<span class="text-blue-600 dark:text-blue-400 animate-pulse">Berjalan...</span>';
                                    } else {
                                        echo '-';
                                    }
                                ?>
                            </td>

                            <td class="px-6 py-4">
                                <?php if($s['status'] == 'SELESAI'): ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Selesai</span>
                                    <div class="text-[9px] text-gray-400 mt-0.5"><?= $s['ip_address'] ?? 'IP: -' ?></div>
                                <?php elseif($s['status']): ?>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 animate-pulse">Mengerjakan</span>
                                    <?php if($s['is_locked']): ?>
                                        <svg class="inline w-3 h-3 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Belum</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'reset')" class="text-white bg-green-500 hover:bg-green-600 rounded p-1.5" title="Reset"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></button>
                                    <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'stop')" class="text-white bg-red-500 hover:bg-red-600 rounded p-1.5" title="Stop"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg></button>
                                    <button onclick="checkAndAction(<?= $s['siswa_id'] ?>, 'unlock')" class="text-white bg-blue-500 hover:bg-blue-600 rounded p-1.5" title="Unlock"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="timeModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Tambah Waktu Ujian</h3>
                <form class="space-y-6" action="#" onsubmit="event.preventDefault(); submitTime();">
                    <div>
                        <label for="menit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Menit</label>
                        <input type="number" name="menit" id="menit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: 10" required min="1">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Simpan</button>
                        <button type="button" onclick="closeTimeModal()" class="w-full text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="confirmModal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="closeConfirmModal()">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                <span class="sr-only">Tutup</span>
            </button>
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400" id="confirmTitle">Apakah Anda yakin?</h3>
                <button id="btnConfirmYes" onclick="executeAction()" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Ya, Saya Yakin
                </button>
                <button onclick="closeConfirmModal()" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // ========= 1. LOGIKA MODAL (Manual Toggle Class) =========
    // Kita pakai toggle class 'hidden' & 'flex' untuk kontrol modal
    
    // VARIABEL GLOBAL
    let currentAction = '';
    
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // Tambah backdrop jika perlu (opsional, disini kita pakai style default)
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Modal Time
    function openTimeModal() { openModal('timeModal'); }
    function closeTimeModal() { closeModal('timeModal'); }

    // Modal Confirm
    function closeConfirmModal() { closeModal('confirmModal'); }
    function confirmAction(action) {
        // Cek apakah ada yang dicentang
        if (getSelectedIds().length === 0) {
            alert('Pilih siswa terlebih dahulu!');
            return;
        }

        currentAction = action;
        const titleEl = document.getElementById('confirmTitle');
        const btnEl = document.getElementById('btnConfirmYes');
        
        // Ubah teks sesuai aksi
        if(action === 'reset') {
            titleEl.textContent = "Reset ujian siswa yang dipilih?";
            btnEl.className = "text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2";
        } else if (action === 'stop') {
            titleEl.textContent = "Paksa selesai ujian siswa yang dipilih?";
            btnEl.className = "text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2";
        } else if (action === 'unlock') {
            titleEl.textContent = "Buka kunci login siswa yang dipilih?";
            btnEl.className = "text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2";
        }

        openModal('confirmModal');
    }

    // ========= 2. LOGIKA CHECKBOX =========
    const checkAll = document.getElementById('checkbox-all');
    const checkItems = document.querySelectorAll('.chk-siswa');

    checkAll.addEventListener('change', function() {
        checkItems.forEach(chk => chk.checked = this.checked);
    });

    function getSelectedIds() {
        let ids = [];
        document.querySelectorAll('.chk-siswa:checked').forEach(c => ids.push(c.value));
        return ids;
    }

    // Helper: Button Aksi per Siswa (Otomatis centang 1 siswa & buka modal)
    function checkAndAction(id, action) {
        document.querySelectorAll('.chk-siswa').forEach(c => c.checked = false); // uncheck all
        const target = document.querySelector(`.chk-siswa[value="${id}"]`);
        if(target) target.checked = true;
        confirmAction(action);
    }

    // ========= 3. EKSEKUSI AJAX =========
    
    // Aksi Masal (Reset, Stop, Unlock)
    function executeAction() {
        const ids = getSelectedIds();
        kirimData(ids, currentAction, 0);
        closeConfirmModal();
    }

    // Tambah Waktu
    function submitTime() {
        const menit = document.getElementById('menit').value;
        const ids = getSelectedIds();
        if(ids.length === 0) { alert('Pilih siswa!'); return; }
        
        kirimData(ids, 'add_time', menit);
        closeTimeModal();
    }

    function kirimData(ids, aksi, menit) {
        // Tampilkan Loading Sederhana (Cursor Wait)
        document.body.style.cursor = 'wait';

        // Pake Fetch API bawaan JS (Biar gak tergantung jQuery)
        const formData = new FormData();
        ids.forEach(id => formData.append('ids[]', id));
        formData.append('aksi', aksi);
        formData.append('menit', menit);

        fetch('<?= base_url('admin/monitoringruang/aksi_masal') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.body.style.cursor = 'default';
            if(data.status === 'success') {
                // Refresh halaman otomatis
                location.reload();
            } else {
                alert('Gagal: ' + data.msg);
            }
        })
        .catch(error => {
            document.body.style.cursor = 'default';
            alert('Terjadi kesalahan koneksi.');
            console.error(error);
        });
    }

    // Auto Refresh
    setInterval(() => {
        if(document.getElementById('toggleRefresh').checked) {
            location.reload();
        }
    }, 10000); // 10 detik

</script>

<?= $this->endSection() ?>