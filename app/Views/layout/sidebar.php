<?php 
$roles = session()->get('roles') ?? []; 
if (!is_array($roles)) $roles = [$roles]; 
$roleActive = session()->get('role_active');
?>

<aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-72 h-screen pt-20 transition-transform -translate-x-full bg-slate-50 border-r border-gray-200 sm:translate-x-0 dark:bg-[#0b1120] dark:border-slate-800 shadow-2xl shadow-blue-500/5" aria-label="Sidebar">
   <div class="h-full px-4 pb-6 overflow-y-auto custom-scrollbar">
      <ul class="space-y-2 font-medium" style="font-family: 'Plus Jakarta Sans', sans-serif;">
         
         <li class="mb-8 mt-2 px-4 py-5 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2rem] shadow-xl shadow-blue-500/20 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all duration-700"></div>
            
            <div class="flex items-center gap-4 relative z-10">
                <div class="relative">
                    <img class="w-12 h-12 rounded-2xl object-cover border-2 border-white/30 shadow-md" src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('nama_lengkap')) ?>&background=fff&color=4F46E5" alt="">
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-[#4F46E5] rounded-full"></div>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[11px] font-black text-blue-100 uppercase tracking-widest leading-none mb-1 opacity-80">Logged in as</p>
                    <p class="text-sm font-black text-white truncate uppercase tracking-tighter leading-tight"><?= session()->get('nama_lengkap') ?></p>
                    <div class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full bg-white/20 backdrop-blur-md">
                        <span class="text-[9px] font-bold text-white uppercase tracking-tighter"><?= $roleActive ?></span>
                    </div>
                </div>
            </div>
         </li>

         <li>
            <a href="<?= base_url($roleActive . '/dashboard') ?>" class="flex items-center p-3.5 text-slate-600 rounded-2xl dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 group transition-all duration-300 shadow-sm hover:shadow-md border border-transparent hover:border-blue-500/10">
               <div class="flex-shrink-0 p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 group-hover:rotate-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
               </div>
               <span class="ml-3.5 text-[13px] font-bold uppercase tracking-widest">Dashboard</span>
            </a>
         </li>

         <?php if (in_array('admin', $roles)) : ?>
         <li class="pt-8 pb-3">
            <div class="flex items-center px-4 gap-3">
                <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.25em]">Administrator Zone</span>
                <div class="h-[1px] flex-1 bg-gradient-to-r from-slate-200 to-transparent dark:from-slate-800"></div>
            </div>
         </li>

         <li>
            <button type="button" class="flex items-center w-full p-3.5 text-slate-600 transition-all duration-300 rounded-2xl group hover:bg-white dark:text-slate-400 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 border border-transparent hover:border-blue-500/10" aria-controls="dropdown-master" data-collapse-toggle="dropdown-master">
                <div class="flex-shrink-0 p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="flex-1 ms-3.5 text-left text-[13px] font-bold uppercase tracking-widest">Akademik</span>
                <svg class="w-4 h-4 transition-transform duration-300 group-aria-expanded:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <ul id="dropdown-master" class="hidden py-2 space-y-1.5 ml-8 border-l-2 border-slate-200 dark:border-slate-800">
                <li><a href="<?= base_url('admin/master/tahun_ajaran') ?>" class="group flex items-center w-full py-2 pl-6 text-[12px] font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all"><span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 mr-3 group-hover:bg-blue-500 transition-colors"></span> Tahun Ajaran</a></li>
                <li><a href="<?= base_url('admin/master/jurusan') ?>" class="group flex items-center w-full py-2 pl-6 text-[12px] font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all"><span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 mr-3 group-hover:bg-blue-500 transition-colors"></span> Data Jurusan</a></li>
                <li><a href="<?= base_url('admin/master/ruangan') ?>" class="group flex items-center w-full py-2 pl-6 text-[12px] font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all"><span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 mr-3 group-hover:bg-blue-500 transition-colors"></span> Data Ruangan</a></li>
                <li><a href="<?= base_url('admin/master/kelas') ?>" class="group flex items-center w-full py-2 pl-6 text-[12px] font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all"><span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 mr-3 group-hover:bg-blue-500 transition-colors"></span> Data Kelas</a></li>
                <li><a href="<?= base_url('admin/master/mapel') ?>" class="group flex items-center w-full py-2 pl-6 text-[12px] font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all"><span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 mr-3 group-hover:bg-blue-500 transition-colors"></span> Mata Pelajaran</a></li>
            </ul>
         </li>

         <li>
            <button type="button" class="flex items-center w-full p-3.5 text-slate-600 transition-all duration-300 rounded-2xl group hover:bg-white dark:text-slate-400 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 border border-transparent hover:border-blue-500/10" aria-controls="dropdown-personel" data-collapse-toggle="dropdown-personel">
                <div class="flex-shrink-0 p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <span class="flex-1 ms-3.5 text-left text-[13px] font-bold uppercase tracking-widest">Personel</span>
                <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <ul id="dropdown-personel" class="hidden py-2 space-y-1.5 ml-8 border-l-2 border-slate-200 dark:border-slate-800">
                <li><a href="<?= base_url('admin/guru') ?>" class="group flex items-center w-full py-2 pl-6 text-[12px] font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all"><span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 mr-3 group-hover:bg-blue-500 transition-colors"></span> Data Guru</a></li>
                <li><a href="<?= base_url('admin/siswa') ?>" class="group flex items-center w-full py-2 pl-6 text-[12px] font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all"><span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 mr-3 group-hover:bg-blue-500 transition-colors"></span> Data Siswa</a></li>
                <li><a href="<?= base_url('admin/ortu') ?>" class="group flex items-center w-full py-2 pl-6 text-[12px] font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all"><span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 mr-3 group-hover:bg-blue-500 transition-colors"></span> Data Wali</a></li>
            </ul>
         </li>

         <li>
            <a href="<?= base_url('admin/users') ?>" class="flex items-center p-3.5 text-slate-600 rounded-2xl dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 group transition-all duration-300 shadow-sm border border-transparent hover:border-blue-500/20">
               <div class="flex-shrink-0 p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
               </div>
               <span class="ml-3.5 text-[13px] font-bold uppercase tracking-widest">Hak Akses</span>
            </a>
         </li>

         <li>
            <button type="button" class="flex items-center w-full p-3.5 text-slate-600 transition-all duration-300 rounded-2xl group hover:bg-white dark:text-slate-400 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 border border-transparent hover:border-blue-500/10" aria-controls="dropdown-web" data-collapse-toggle="dropdown-web">
                <div class="flex-shrink-0 p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                </div>
                <span class="flex-1 ms-3.5 text-left text-[13px] font-bold uppercase tracking-widest">Konfigurasi</span>
                <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <ul id="dropdown-web" class="hidden py-2 space-y-1.5 ml-8 border-l-2 border-slate-200 dark:border-slate-800">
                <li><a href="<?= base_url('admin/pengaturan') ?>" class="group flex items-center w-full py-2 pl-6 text-[12px] font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all"><span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 mr-3 group-hover:bg-blue-500 transition-colors"></span> Identitas Sekolah</a></li>
            </ul>
         </li>
         <?php endif; ?>

         <?php if (in_array('guru', $roles)) : ?>
         <li class="pt-8 pb-3">
            <div class="flex items-center px-4 gap-3">
                <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.25em]">Instruktur Area</span>
                <div class="h-[1px] flex-1 bg-gradient-to-r from-slate-200 to-transparent dark:from-slate-800"></div>
            </div>
         </li>
         <li>
            <a href="#" class="flex items-center p-3.5 text-slate-600 rounded-2xl dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 group transition-all duration-300 shadow-sm border border-transparent hover:border-indigo-500/20">
               <div class="flex-shrink-0 p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
               </div>
               <span class="ml-3.5 text-[13px] font-bold uppercase tracking-widest">Input Nilai</span>
            </a>
         </li>
         <?php endif; ?>

         <li class="pt-12">
            <a href="<?= base_url('logout') ?>" class="flex items-center p-4 bg-red-500/5 hover:bg-red-600 text-red-600 hover:text-white rounded-[1.5rem] transition-all duration-300 group shadow-sm hover:shadow-red-500/40 border border-red-500/10">
               <div class="flex-shrink-0 p-2 bg-red-100 dark:bg-red-900/30 rounded-lg group-hover:bg-white group-hover:text-red-600 transition-all">
                    <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
               </div>
               <span class="ml-4 text-[13px] font-black uppercase tracking-[0.2em]">Keluar Sistem</span>
            </a>
         </li>
      </ul>
   </div>
</aside>