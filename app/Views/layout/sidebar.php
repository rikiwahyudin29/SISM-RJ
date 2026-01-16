<?php 
$roles = session()->get('roles') ?? []; 
if (!is_array($roles)) $roles = [$roles]; 
$roleActive = session()->get('role_active');
?>

<aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-72 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-[#0f172a] dark:border-slate-800 shadow-xl" aria-label="Sidebar">
    
    <div class="h-full px-4 pb-6 overflow-y-auto custom-scrollbar flex flex-col justify-between" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        
        <div>
            <div class="flex items-center gap-4 mb-6 mt-2 px-2 py-4 border-b border-gray-100 dark:border-slate-800/50">
                <div class="relative">
                    <img class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 dark:border-slate-600 shadow-sm" 
                        src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('nama_lengkap')) ?>&background=0f172a&color=3b82f6" alt="User">
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white dark:border-[#0f172a] rounded-full"></span>
                </div>
                <div class="overflow-hidden">
                    <h2 class="text-sm font-bold text-gray-800 dark:text-white truncate leading-tight"><?= session()->get('nama_lengkap') ?></h2>
                    <div class="flex items-center mt-1">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 uppercase tracking-wider"><?= $roleActive ?></span>
                    </div>
                </div>
            </div>

            <ul class="space-y-1">
                
                <li>
                    <a href="<?= base_url($roleActive . '/dashboard') ?>" 
                       class="flex items-center p-3 rounded-lg transition-all group border border-transparent
                              <?= (uri_string() == $roleActive.'/dashboard') 
                                  ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' 
                                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' ?>">
                        <svg class="w-5 h-5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span class="ml-3 text-sm font-bold flex-1">Dashboard</span>
                    </a>
                </li>

                <?php if (in_array('admin', $roles)) : ?>
                
                <li class="pt-5 pb-2 px-3">
                    <span class="text-[11px] font-extrabold text-gray-400 dark:text-slate-500 uppercase tracking-widest">Main Menu</span>
                </li>

                <li>
                    <button type="button" class="flex items-center w-full p-3 text-gray-600 rounded-lg transition-all group hover:bg-gray-50 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white" aria-controls="dropdown-master" data-collapse-toggle="dropdown-master">
                        <svg class="w-5 h-5 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="ml-3 flex-1 text-left text-sm font-bold whitespace-nowrap">Akademik</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-master" class="hidden py-1 space-y-1">
                        <li><a href="<?= base_url('admin/master/tahun_ajaran') ?>" class="flex items-center w-full p-2 pl-11 text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Tahun Ajaran</a></li>
                        <li><a href="<?= base_url('admin/master/jurusan') ?>" class="flex items-center w-full p-2 pl-11 text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Jurusan</a></li>
                        <li><a href="<?= base_url('admin/master/ruangan') ?>" class="flex items-center w-full p-2 pl-11 text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Ruangan</a></li>
                        <li><a href="<?= base_url('admin/master/kelas') ?>" class="flex items-center w-full p-2 pl-11 text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Kelas</a></li>
                        <li><a href="<?= base_url('admin/master/mapel') ?>" class="flex items-center w-full p-2 pl-11 text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Mata Pelajaran</a></li>
                    </ul>
                </li>

                <li>
                    <button type="button" class="flex items-center w-full p-3 text-gray-600 rounded-lg transition-all group hover:bg-gray-50 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white" aria-controls="dropdown-personel" data-collapse-toggle="dropdown-personel">
                        <svg class="w-5 h-5 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="flex-1 ms-3 text-left whitespace-nowrap">Personel</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-personel" class="hidden py-1 space-y-1">
                        <li><a href="<?= base_url('admin/guru') ?>" class="flex items-center w-full p-2 pl-11 text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Guru</a></li>
                        <li><a href="<?= base_url('admin/siswa') ?>" class="flex items-center w-full p-2 pl-11 text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Siswa</a></li>
                        <li><a href="<?= base_url('admin/ortu') ?>" class="flex items-center w-full p-2 pl-11 text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Data Wali</a></li>
                    </ul>
                </li>

                <li>
                    <a href="<?= base_url('admin/users') ?>" class="flex items-center p-3 text-gray-600 rounded-lg transition-all group hover:bg-gray-50 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white">
                        <svg class="w-5 h-5 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span class="ml-3 text-sm font-bold flex-1">Hak Akses</span>
                    </a>
                </li>

                <li>
                    <button type="button" class="flex items-center w-full p-3 text-gray-600 rounded-lg transition-all group hover:bg-gray-50 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white" aria-controls="dropdown-web" data-collapse-toggle="dropdown-web">
                        <svg class="w-5 h-5 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        <span class="ml-3 flex-1 text-left text-sm font-bold whitespace-nowrap">Konfigurasi</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul id="dropdown-web" class="hidden py-1 space-y-1">
                        <li><a href="<?= base_url('admin/pengaturan') ?>" class="flex items-center w-full p-2 pl-11 text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white transition-colors">Identitas Sekolah</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if (in_array('guru', $roles)) : ?>
                <li class="pt-5 pb-2 px-3">
                    <span class="text-[11px] font-extrabold text-gray-400 dark:text-slate-500 uppercase tracking-widest">Area Guru</span>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 text-gray-600 rounded-lg transition-all group hover:bg-gray-50 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white">
                        <svg class="w-5 h-5 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="ml-3 text-sm font-bold flex-1">Input Nilai</span>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-800/50">
             <a href="<?= base_url('logout') ?>" class="flex items-center p-3 text-gray-500 rounded-lg hover:bg-red-50 hover:text-red-600 dark:text-slate-500 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition-all group">
                <svg class="w-5 h-5 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="ml-3 text-sm font-bold">Keluar Sistem</span>
            </a>
        </div>
    </div>
</aside>