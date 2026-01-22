<?php 
$roles = session()->get('roles') ?? []; 
if (!is_array($roles)) $roles = [$roles]; 
$roleActive = session()->get('role_active');
?>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-[#0f172a] dark:border-slate-800 shadow-xl" aria-label="Sidebar">
    
    <div class="absolute top-4 right-4 sm:hidden">
        <button type="button" data-drawer-hide="logo-sidebar" aria-controls="logo-sidebar" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    </div>

    <div class="h-full px-3 pb-4 overflow-y-auto custom-scrollbar flex flex-col justify-between" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        
        <div>
            <div class="flex items-center gap-3 mb-4 mt-2 px-3 py-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-700">
                <div class="relative shrink-0">
                    <img class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-slate-600" 
                        src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('nama_lengkap')) ?>&background=0f172a&color=3b82f6&bold=true" alt="User">
                    <span class="absolute bottom-0 right-0 w-2 h-2 bg-emerald-500 border-2 border-white dark:border-[#0f172a] rounded-full"></span>
                </div>
                <div class="overflow-hidden min-w-0">
                    <h2 class="text-xs font-bold text-gray-800 dark:text-white truncate leading-tight"><?= session()->get('nama_lengkap') ?></h2>
                    <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider"><?= $roleActive ?></span>
                </div>
            </div>

            <ul class="space-y-1">
                
                <li>
                    <a href="<?= base_url($roleActive . '/dashboard') ?>" 
                       class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm
                              <?= (uri_string() == $roleActive.'/dashboard') 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                <?php if (in_array('admin', $roles)) : ?>
                
                <li class="pt-3 pb-1 px-3">
                    <span class="text-[10px] font-extrabold text-gray-400 dark:text-slate-600 uppercase tracking-widest">Master Data</span>
                </li>

                <li>
                    <button type="button" class="flex items-center w-full px-3 py-2 text-gray-600 rounded-lg transition-all group hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white font-bold text-sm" aria-controls="dropdown-master" data-collapse-toggle="dropdown-master">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Data Sekolah</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-master" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/master/tahun_ajaran') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Tahun Ajaran</a></li>
                        
                        <li><a href="<?= base_url('admin/jenis_ujian') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Jenis Ujian</a></li>
                        
                        <li><a href="<?= base_url('admin/master/jurusan') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Jurusan</a></li>
                        <li><a href="<?= base_url('admin/master/ruangan') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Ruangan</a></li>
                        <li><a href="<?= base_url('admin/master/kelas') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Kelas</a></li>
                        <li><a href="<?= base_url('admin/master/mapel') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Mata Pelajaran</a></li>
                        <li><a href="<?= base_url('admin/jam') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Jam Belajar (Bel)</a></li>
                    </ul>
                </li>

                <li class="pt-3 pb-1 px-3">
                    <span class="text-[10px] font-extrabold text-gray-400 dark:text-slate-600 uppercase tracking-widest">Akademik</span>
                </li>

                <li>
                    <a href="<?= base_url('admin/jadwal') ?>" 
                       class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm
                              <?= (uri_string() == 'admin/jadwal' || strpos(uri_string(), 'admin/jadwal/') !== false) 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="ml-3">Jadwal Pelajaran</span>
                    </a>
                </li>
<li>
                    <a href="<?= base_url('admin/jadwalujian') ?>" 
                       class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm
                              <?= (strpos(uri_string(), 'admin/jadwalujian') !== false) 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="ml-3">Jadwal Ujian</span>
                    </a>
                </li>
                <li class="pt-3 pb-1 px-3">
                    <span class="text-[10px] font-extrabold text-gray-400 dark:text-slate-600 uppercase tracking-widest">Administrasi</span>
                </li>

                <li>
                    <button type="button" class="flex items-center w-full px-3 py-2 text-gray-600 rounded-lg transition-all group hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white font-bold text-sm" aria-controls="dropdown-users" data-collapse-toggle="dropdown-users">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Pengguna</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-users" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/guru') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Guru</a></li>
                        <li><a href="<?= base_url('admin/siswa') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Siswa</a></li>
                        <li><a href="<?= base_url('admin/ortu') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Wali</a></li>
                        <li><a href="<?= base_url('admin/users') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Hak Akses</a></li>
                    </ul>
                </li>

                <li>
                    <button type="button" class="flex items-center w-full px-3 py-2 text-gray-600 rounded-lg transition-all group hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white font-bold text-sm" aria-controls="dropdown-kesiswaan" data-collapse-toggle="dropdown-kesiswaan">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Kesiswaan</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-kesiswaan" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/rombel') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Manajemen Rombel</a></li>
                        <li><a href="<?= base_url('admin/rombel/alumni') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Alumni</a></li>
                    </ul>
                </li>

                <li>
                    <button type="button" class="flex items-center w-full px-3 py-2 text-gray-600 rounded-lg transition-all group hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white font-bold text-sm" aria-controls="dropdown-web" data-collapse-toggle="dropdown-web">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        <span class="ml-3 flex-1 text-left whitespace-nowrap">Konfigurasi</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-web" class="hidden py-1 space-y-0.5">
                        <li><a href="<?= base_url('admin/pengaturan') ?>" class="flex items-center w-full p-2 pl-10 text-xs font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Identitas Sekolah</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if (in_array('guru', $roles)) : ?>
                <li class="pt-3 pb-1 px-3">
                    <span class="text-[10px] font-extrabold text-gray-400 dark:text-slate-600 uppercase tracking-widest">Area Guru</span>
                </li>
                
                <li>
                    <a href="<?= base_url('guru/jadwal') ?>" 
                       class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm
                              <?= (strpos(uri_string(), 'guru/jadwal') !== false) 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="ml-3">Jadwal Mengajar</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('guru/bank_soal') ?>" 
                       class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm
                              <?= (strpos(uri_string(), 'guru/bank_soal') !== false) 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="ml-3 flex-1">Bank Soal</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('guru/ujian') ?>" 
                       class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm
                              <?= (strpos(uri_string(), 'guru/ujian') !== false) 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="ml-3 flex-1">Jadwal Ujian</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('guru/monitoring') ?>" 
                       class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm
                              <?= (strpos(uri_string(), 'guru/monitoring') !== false) 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <i class="fas fa-desktop w-4 h-4 flex-shrink-0 text-center transition-colors"></i>
                        <span class="ml-3 flex-1">Mengawas</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('guru/nilai') ?>" 
                       class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm
                              <?= (strpos(uri_string(), 'guru/nilai') !== false) 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <svg class="w-4 h-4 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="ml-3 flex-1">Input Nilai</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($roleActive == 'siswa') : ?>
                <li class="pt-3 pb-1 px-3">
                    <span class="text-[10px] font-extrabold text-gray-400 dark:text-slate-600 uppercase tracking-widest">Area Siswa</span>
                </li>

                <li>
                    <a href="<?= base_url('siswa/ujian') ?>" 
                       class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm
                              <?= (strpos(uri_string(), 'siswa/ujian') !== false) 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <i class="fas fa-pen-alt w-4 h-4 text-center transition-colors"></i>
                        <span class="ml-3 flex-1">Ujian Sekolah</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('siswa/nilai') ?>" 
                       class="flex items-center px-3 py-2 rounded-lg transition-all group font-bold text-sm
                              <?= (strpos(uri_string(), 'siswa/nilai') !== false) 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <i class="fas fa-chart-line w-4 h-4 text-center transition-colors"></i>
                        <span class="ml-3 flex-1">Riwayat Nilai</span>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </div>

        <div class="mt-2 pt-3 border-t border-gray-100 dark:border-slate-800/50">
             <a href="<?= base_url('logout') ?>" class="flex items-center px-3 py-2 text-gray-500 rounded-lg hover:bg-red-50 hover:text-red-600 dark:text-slate-500 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition-all group">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="ml-3 text-sm font-bold">Keluar Sistem</span>
            </a>
        </div>
    </div>
</aside>