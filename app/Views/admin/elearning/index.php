<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php 
    // Ambil database untuk cek role langsung (lebih aman dari session)
    $db = \Config\Database::connect();
    $id_user = session()->get('id_user');

    // Cek apakah dia Guru (ID 8) atau Admin (ID 1)
    $checkGuru = $db->table('user_roles')
                    ->where('user_id', $id_user)
                    ->whereIn('role_id', [1, 8]) 
                    ->countAllResults();
?>

<div class="p-4 sm:ml-2">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 mt-14">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Kelas Virtual
            </h1>
            <p class="text-sm text-gray-500 font-medium">Generate otomatis link Google Meet untuk kelas.</p>
        </div>

        <?php if($checkGuru > 0): ?>
            <div class="flex gap-2 mt-4 md:mt-0">
                <?php if(empty($is_connected)): ?>
                    <a href="<?= base_url('admin/google/connect') ?>" class="group flex items-center bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 px-5 py-2.5 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 transition-all duration-200">
                        <div class="mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24s.92 7.54 2.56 10.78l7.97-6.19z"/>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                <path fill="none" d="M0 0h48v48H0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-tight">Hubungkan Google</span>
                    </a>
                <?php else: ?>
                    <button onclick="document.getElementById('modalJadwal').classList.remove('hidden')" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all">
                        + BUAT KELAS OTOMATIS
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded shadow-sm font-bold flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-rose-100 border-l-4 border-rose-500 text-rose-700 p-4 mb-6 rounded shadow-sm font-bold flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <?php if(empty($jadwal)): ?>
            <div class="col-span-1 md:col-span-3 py-16 flex flex-col items-center justify-center text-center border-2 border-dashed border-gray-300 rounded-3xl bg-gray-50/50">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-400 rounded-full flex items-center justify-center mb-4 shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-700">Belum Ada Jadwal Aktif</h3>
                <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">
                    <?php if($role == 'guru'): ?>
                        Silakan buat jadwal kelas virtual baru untuk memulai pembelajaran.
                    <?php else: ?>
                        Belum ada kelas yang dijadwalkan oleh Guru kamu saat ini.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>

        <?php foreach($jadwal as $j): ?>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-0 border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-xl transition-all group overflow-hidden relative">
                
                <div class="h-24 bg-gradient-to-r from-blue-600 to-indigo-600 relative p-4 flex justify-between items-start">
                    <span class="bg-white/20 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider border border-white/10">
                        <?= $j['hari'] ?> • <?= date('H:i', strtotime($j['tgl_pertemuan'])) ?> - <?= date('H:i', strtotime($j['jam_selesai'])) ?>
                    </span>
                    
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/9/9b/Google_Meet_icon_%282020%29.svg" class="w-5 h-5">
                    </div>
                </div>

                <div class="p-5 relative">
                    <div class="absolute -top-6 left-5 w-12 h-12 bg-white rounded-xl border-4 border-white shadow-md flex items-center justify-center text-xl">
                        🏫
                    </div>

                    <h3 class="text-lg font-black text-gray-800 dark:text-white line-clamp-1" title="<?= $j['judul_pertemuan'] ?>">
    <?= $j['judul_pertemuan'] ?>
</h3>
                        
                        <?php if($role == 'siswa'): ?>
                            <p class="text-xs text-gray-500 font-bold mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Guru: <?= $j['nama_guru'] ?>
                            </p>
                        <?php else: ?>
                            <p class="text-xs text-gray-500 font-bold mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Kelas: <?= $j['nama_kelas'] ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="mt-6 pt-4 border-t border-dashed border-gray-100">
                        <?php if($role == 'siswa' || $role == 'admin'): ?>
                            <a href="<?= $j['link_meet'] ?>" target="_blank" class="flex items-center justify-center w-full py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30 group-hover:scale-[1.02]">
                                <svg class="w-5 h-5 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                MASUK MEET
                            </a>
                        <?php endif; ?>

                        <?php if($role == 'guru' || $role == 'admin'): ?>
                            <div class="flex gap-2">
                               <button onclick="bukaMeet('<?= $j['link_meet'] ?>', '<?= $j['judul_pertemuan'] ?>')" class="flex items-center justify-center w-full py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30">
    <svg class="w-5 h-5 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
    MULAI KELAS DI SINI
</button>
                                <a href="<?= base_url('admin/elearning/delete/'.$j['id']) ?>" onclick="return confirm('Yakin hapus jadwal ini? Link Meet di Google Calendar tidak akan terhapus otomatis.')" class="px-3 py-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if($role == 'guru'): ?>
<div id="modalJadwal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-opacity">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 relative">
        <button onclick="document.getElementById('modalJadwal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-rose-500">✕</button>

        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="font-bold text-lg text-gray-800 uppercase">Jadwal Kelas Baru</h3>
            <p class="text-xs text-gray-500">Isi form di bawah, sistem akan membuatkan Link Meet.</p>
        </div>

        <form action="<?= base_url('admin/elearning/save') ?>" method="POST" class="space-y-4">
            <?= csrf_field(); ?>
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Mata Pelajaran</label>
                <input type="text" name="mapel" class="w-full p-3 border border-gray-200 rounded-xl font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Contoh: Matematika Wajib" required>
            </div>
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Target Kelas</label>
                <select name="kelas_id" class="w-full p-3 border border-gray-200 rounded-xl font-bold focus:ring-2 focus:ring-indigo-500 text-sm" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach($kelas as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Hari</label>
                    <select name="hari" class="w-full p-2 border border-gray-200 rounded-xl font-bold text-sm">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Mulai</label>
                    <input type="time" name="jam_mulai" class="w-full p-2 border border-gray-200 rounded-xl text-sm font-bold" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Selesai</label>
                    <input type="time" name="jam_selesai" class="w-full p-2 border border-gray-200 rounded-xl text-sm font-bold" required>
                </div>
            </div>

            <div class="bg-indigo-50 p-3 rounded-xl border border-indigo-100 flex items-start gap-3 mt-2">
                <div class="mt-0.5">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/9/9b/Google_Meet_icon_%282020%29.svg" class="w-5 h-5">
                </div>
                <div>
                    <p class="text-xs text-indigo-800 font-bold">Link Google Meet Otomatis</p>
                    <p class="text-[10px] text-indigo-600 leading-tight mt-0.5">Sistem akan menghubungi Google Calendar Anda untuk membuat room meeting secara otomatis.</p>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-black rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30 uppercase text-xs tracking-widest mt-2">
                GENERATE LINK & SIMPAN
            </button>
        </form>
    </div>
</div>
<?php endif; ?>

<div id="modalMeet" class="fixed inset-0 z-[999] hidden bg-black flex flex-col">
    <div class="bg-gray-900 p-4 flex justify-between items-center text-white border-b border-gray-800">
        <div>
            <h3 id="judulMeet" class="font-bold text-lg text-indigo-400">Loading Meet...</h3>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest">SIAKAD Virtual Classroom</p>
        </div>
        <button onclick="tutupMeet()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg flex items-center gap-2 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            KELUAR KELAS
        </button>
    </div>

    <div class="flex-1 w-full h-full bg-slate-900 relative">
        <iframe 
            id="iframeMeet" 
            src="" 
            class="w-full h-full border-none"
            allow="camera; microphone; fullscreen; display-capture; autoplay"
            allowfullscreen>
        </iframe>
    </div>
</div>

<script>
   function bukaMeet(url, judul) {
    // Tentukan ukuran jendela pop-up
    const lebar = 1000;
    const tinggi = 700;
    const kiri = (screen.width / 2) - (lebar / 2);
    const atas = (screen.height / 2) - (tinggi / 2);

    // Buka Google Meet di jendela mandiri tanpa toolbar browser
    window.open(
        url, 
        'GoogleMeetSIAKAD', 
        `width=${lebar},height=${tinggi},top=${atas},left=${kiri},toolbar=no,menubar=no,scrollbars=yes,resizable=yes`
    );
}

    function tutupMeet() {
        const modal = document.getElementById('modalMeet');
        const iframe = document.getElementById('iframeMeet');

        if(confirm("Apakah Anda ingin keluar dari pertemuan ini?")) {
            iframe.src = ""; // Stop audio/video di background
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }
</script>

<?= $this->endSection(); ?>