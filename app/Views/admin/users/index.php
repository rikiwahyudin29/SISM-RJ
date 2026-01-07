<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700 transition-all duration-300">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase italic tracking-tight">Manajemen Hak Akses</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total terdaftar: <span class="text-blue-600"><?= count($users) ?></span> Pengguna Terverifikasi</p>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="user-search" class="block w-full p-2.5 pl-10 text-xs font-bold text-gray-900 border border-gray-300 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="CARI USERNAME / NAMA...">
                </div>

                <button type="button" data-modal-target="modal-tambah-user" data-modal-toggle="modal-tambah-user" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-black rounded-xl text-xs px-5 py-3 shadow-lg shadow-blue-500/30 transition-all active:scale-95 flex items-center gap-2 uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    TAMBAH USER
                </button>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-[10px] text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 font-black tracking-widest">
                <tr>
                    <th class="px-6 py-4 border-b dark:border-gray-600">Informasi Pengguna</th>
                    <th class="px-6 py-4 border-b dark:border-gray-600">Username</th>
                    <th class="px-6 py-4 border-b dark:border-gray-600">Jabatan (Roles)</th>
                    <th class="px-6 py-4 text-center border-b dark:border-gray-600">Opsi Kendali</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u) : ?>
                <tr class="user-row bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <?php 
                                $namaFileFoto = $u['foto'] ?? '';
                                $pathFoto = 'uploads/guru/' . $namaFileFoto;
                            ?>
                            <?php if (!empty($namaFileFoto) && file_exists(FCPATH . $pathFoto)): ?>
                                <img class="w-10 h-10 rounded-full border-2 border-blue-500/20 object-cover shadow-sm" src="<?= base_url($pathFoto) ?>" alt="Foto">
                            <?php else: ?>
                                <img class="w-10 h-10 rounded-full border-2 border-gray-200 object-cover shadow-sm" src="https://ui-avatars.com/api/?name=<?= urlencode($u['nama_lengkap']) ?>&background=random" alt="Avatar">
                            <?php endif; ?>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white search-name"><?= $u['nama_lengkap'] ?></p>
                                <p class="text-[10px] text-gray-400 font-medium italic uppercase">ID: #USR-<?= $u['id'] ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-100 text-gray-600 text-[10px] font-black px-2 py-1 rounded-lg dark:bg-gray-700 dark:text-gray-300 border dark:border-gray-600">@<?= $u['username'] ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            <?php foreach ($u['roles'] as $r) : ?>
                                <span class="bg-blue-100 text-blue-800 text-[9px] font-black px-2.5 py-0.5 rounded-full dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200 dark:border-blue-800 uppercase">
                                    <?= $r['role_name'] ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button type="button" data-modal-target="modal-role-<?= $u['id'] ?>" data-modal-toggle="modal-role-<?= $u['id'] ?>" class="text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 p-2 rounded-xl transition-all hover:scale-110" title="Atur Jabatan">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </button>
                            
                            <button type="button" data-modal-target="modal-edit-user-<?= $u['id'] ?>" data-modal-toggle="modal-edit-user-<?= $u['id'] ?>" class="text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/30 p-2 rounded-xl transition-all hover:rotate-12 group" title="Edit Profil & Password">
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>

                <div id="modal-edit-user-<?= $u['id'] ?>" tabindex="-1" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-gray-900/60 backdrop-blur-sm p-4">
                    <div class="relative w-full max-w-md">
                        <div class="bg-white rounded-3xl shadow-2xl dark:bg-gray-800 border dark:border-gray-700 overflow-hidden animate-scale-up">
                            <div class="p-6 border-b dark:border-gray-700 bg-gradient-to-r from-amber-500 to-orange-600">
                                <h3 class="text-white font-black uppercase text-xs tracking-widest flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Akun: <?= explode(' ', $u['nama_lengkap'])[0] ?>
                                </h3>
                            </div>
                            <form action="<?= base_url('admin/users/update/' . $u['id']) ?>" method="POST" class="p-6 space-y-4">
                                <?= csrf_field() ?>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" value="<?= $u['nama_lengkap'] ?>" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl p-3 text-sm font-bold focus:ring-amber-500 text-gray-900 dark:text-white" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Username</label>
                                    <input type="text" name="username" value="<?= $u['username'] ?>" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl p-3 text-sm font-bold focus:ring-amber-500 text-gray-900 dark:text-white" required>
                                </div>
                                <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-100 dark:border-amber-900/30">
                                    <label class="block text-[10px] font-black text-amber-600 uppercase mb-1 tracking-widest italic">Reset Password (Opsional)</label>
                                    <input type="password" name="password" class="w-full bg-white dark:bg-gray-700 border-amber-200 dark:border-gray-500 rounded-xl p-3 text-sm focus:ring-amber-500 text-gray-900 dark:text-white" placeholder="Isi hanya jika ingin ganti...">
                                    <p class="text-[9px] text-amber-500 mt-2 font-medium">*Kosongkan jika tidak ingin merubah password.</p>
                                </div>
                                <div class="pt-4 flex gap-3">
                                    <button type="button" data-modal-hide="modal-edit-user-<?= $u['id'] ?>" class="flex-1 py-4 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-gray-600">Batal</button>
                                    <button type="submit" class="flex-1 py-4 bg-amber-500 text-white font-black rounded-2xl text-xs uppercase shadow-lg shadow-amber-500/20 active:scale-95 transition-all tracking-widest">Update Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="modal-role-<?= $u['id'] ?>" tabindex="-1" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-gray-900/60 backdrop-blur-sm p-4">
                    <div class="relative w-full max-w-md">
                        <div class="bg-white rounded-3xl shadow-2xl dark:bg-gray-800 border dark:border-gray-700 overflow-hidden animate-scale-up">
                            <div class="p-5 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/50">
                                <h3 class="font-black text-gray-900 dark:text-white uppercase text-xs tracking-widest italic">Jabatan: <?= $u['nama_lengkap'] ?></h3>
                                <button data-modal-hide="modal-role-<?= $u['id'] ?>" class="text-gray-400 hover:text-gray-900 dark:hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <form action="<?= base_url('admin/users/simpan_role') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                <div class="p-6 space-y-3">
                                    <?php 
                                        $userRoleIds = array_column($u['roles'], 'role_id'); 
                                        $allRoles = (new \App\Models\UserModel())->db->table('roles')->get()->getResultArray();
                                    ?>
                                    <?php foreach ($allRoles as $role) : ?>
                                    <label class="flex items-center p-4 border border-gray-100 rounded-2xl dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 cursor-pointer transition-all group">
                                        <input type="checkbox" name="roles[]" value="<?= $role['id'] ?>" <?= in_array($role['id'], $userRoleIds) ? 'checked' : '' ?> class="w-5 h-5 text-blue-600 rounded-lg focus:ring-blue-500 border-gray-300">
                                        <span class="ms-3 text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider group-hover:text-blue-600 transition-colors"><?= $role['role_name'] ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                                <div class="p-6 border-t dark:border-gray-700 flex gap-3">
                                    <button type="submit" class="flex-1 text-white bg-blue-600 hover:bg-blue-700 font-black rounded-xl text-xs px-5 py-4 shadow-xl shadow-blue-500/20 uppercase tracking-widest">Update Jabatan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modal-tambah-user" tabindex="-1" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-lg">
        <div class="bg-white rounded-3xl shadow-2xl dark:bg-gray-800 border dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b dark:border-gray-700 bg-blue-600">
                <h3 class="text-white font-black uppercase text-sm tracking-widest flex items-center gap-2 italic">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Pendaftaran Akun Staf Baru
                </h3>
            </div>
            <form action="<?= base_url('admin/users/tambah') ?>" method="POST" class="p-6 space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Nama Lengkap Sesuai Dapodik</label>
                    <input type="text" name="nama_lengkap" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm font-bold focus:ring-blue-500 focus:border-blue-500" required placeholder="Contoh: Rian Alviana, S.Kom">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Username Akses</label>
                        <input type="text" name="username" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm font-bold focus:ring-blue-500 focus:border-blue-500" required placeholder="rian.alviana">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Password Default</label>
                        <input type="password" name="password" class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-sm font-bold focus:ring-blue-500 focus:border-blue-500" required placeholder="••••••••">
                    </div>
                </div>
                <div class="pt-4 border-t dark:border-gray-700 flex gap-3">
                    <button type="button" data-modal-hide="modal-tambah-user" class="flex-1 py-4 text-xs font-black text-gray-400 uppercase hover:text-gray-600 transition-colors tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-blue-600 text-white font-black rounded-2xl text-xs uppercase shadow-lg shadow-blue-500/20 active:scale-95 transition-all tracking-widest">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
<div id="success-popup" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
    <div class="relative w-full max-w-sm animate-scale-up">
        <div class="relative bg-white rounded-3xl shadow-2xl dark:bg-gray-800 p-8 text-center border dark:border-gray-700">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 dark:bg-green-900/30 mb-6">
                <svg class="h-12 w-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 uppercase tracking-tight">Berhasil!</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium italic"><?= session()->getFlashdata('success') ?></p>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        const p = document.getElementById('success-popup');
        if (p) { p.style.transition = "opacity 0.5s"; p.style.opacity = "0"; setTimeout(() => p.remove(), 500); }
    }, 2500);
</script>
<?php endif; ?>

<style>
    .animate-scale-up { animation: scaleUp 0.3s ease-out forwards; }
    @keyframes scaleUp { 0% { opacity: 0; transform: scale(0.9); } 100% { opacity: 1; transform: scale(1); } }
</style>

<script>
    document.getElementById('user-search').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('.user-row').forEach(row => {
            const name = row.querySelector('.search-name').innerText.toLowerCase();
            row.style.display = name.includes(val) ? '' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>