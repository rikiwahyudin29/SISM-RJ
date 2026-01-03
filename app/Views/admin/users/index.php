<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Manajemen Hak Akses</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total terdaftar: <?= count($users) ?> Pengguna</p>
        </div>
        
        <div class="relative w-full md:w-80">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="user-search" class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Cari nama atau username...">
        </div>
    </div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4">Nama Lengkap</th>
                    <th class="px-6 py-4">Username</th>
                    <th class="px-6 py-4">Roles</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u) : ?>
                <tr class="user-row bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white search-name"><?= $u['nama_lengkap'] ?></td>
                    <td class="px-6 py-4 search-username"><?= $u['username'] ?></td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            <?php foreach ($u['roles'] as $r) : ?>
                                <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    <?= strtoupper($r['role_name']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button type="button" 
                                data-modal-target="modal-role-<?= $u['id'] ?>" 
                                data-modal-toggle="modal-role-<?= $u['id'] ?>" 
                                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-lg text-xs px-4 py-2 transition-all shadow-md active:scale-95">
                            Atur Role
                        </button>
                    </td>
                </tr>

                <div id="modal-role-<?= $u['id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 border dark:border-gray-700">
                            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Setting Role: <?= $u['nama_lengkap'] ?></h3>
                                <button type="button" data-modal-hide="modal-role-<?= $u['id'] ?>" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                </button>
                            </div>
                            <form action="<?= base_url('admin/users/simpan_role') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                <div class="p-5 space-y-3">
                                    <?php 
                                        $userRoleIds = array_column($u['roles'], 'role_id'); 
                                        $allRoles = (new \App\Models\UserModel())->db->table('roles')->get()->getResultArray();
                                    ?>
                                    <?php foreach ($allRoles as $role) : ?>
                                    <div class="flex items-center p-3 border border-gray-100 rounded-xl dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <input id="role-<?= $u['id'] ?>-<?= $role['id'] ?>" type="checkbox" name="roles[]" value="<?= $role['id'] ?>" <?= in_array($role['id'], $userRoleIds) ? 'checked' : '' ?> class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="role-<?= $u['id'] ?>-<?= $role['id'] ?>" class="w-full ms-3 text-sm font-bold text-gray-900 dark:text-gray-300"><?= $role['role_name'] ?></label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="p-4 border-t dark:border-gray-700 flex gap-2">
                                    <button type="submit" class="flex-1 text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-sm px-5 py-3 shadow-lg shadow-blue-500/30">Simpan Perubahan</button>
                                    <button type="button" data-modal-hide="modal-role-<?= $u['id'] ?>" class="px-5 py-3 text-sm font-bold text-gray-900 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">Batal</button>
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

<<?php if (session()->getFlashdata('success')) : ?>
<div id="success-popup" tabindex="-1" class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm">
    <div class="relative w-full max-w-sm max-h-full">
        <div class="relative bg-white rounded-3xl shadow-2xl dark:bg-gray-800 p-8 text-center animate-scale-up">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 dark:bg-green-900/30 mb-6">
                <svg class="h-12 w-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Berhasil!</h3>
            <p class="text-gray-500 dark:text-gray-400 font-medium">
                <?= session()->getFlashdata('success') ?>
            </p>
        </div>
    </div>
</div>

<style>
    /* Animasi biar popup munculnya keren */
    .animate-scale-up {
        animation: scaleUp 0.3s ease-out forwards;
    }
    @keyframes scaleUp {
        0% { opacity: 0; transform: scale(0.8); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>

<script>
    // Hilangkan popup otomatis setelah 2.5 detik
    setTimeout(() => {
        const popup = document.getElementById('success-popup');
        if (popup) {
            popup.style.transition = "opacity 0.5s ease";
            popup.style.opacity = "0";
            setTimeout(() => popup.remove(), 500);
        }
    }, 2500);
</script>
<?php endif; ?>

<script>
    // Search Filter
    document.getElementById('user-search').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('.user-row').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    });

    // Auto hide toast
    setTimeout(() => {
        const t = document.getElementById('toast-success');
        if(t) t.remove();
    }, 3000);
</script>
<?= $this->endSection() ?>