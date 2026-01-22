<?= $this->extend('layout/template_guru') ?>
<?= $this->section('content') ?>

<style>
    .badge-status { padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
    .st-belum { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }
    .st-sedang { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; animation: pulse 2s infinite; }
    .st-selesai { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .st-blokir { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.7; } 100% { opacity: 1; } }
</style>

<div class="container mx-auto px-4 py-6" id="monitoringApp">

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800"><?= $jadwal->nama_ujian ?></h1>
                <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                    <span class="flex items-center gap-1"><i class="fas fa-clock"></i> <?= $jadwal->durasi ?> Menit</span>
                    <span class="flex items-center gap-1"><i class="fas fa-calendar"></i> <?= date('d M Y H:i', strtotime($jadwal->waktu_mulai)) ?></span>
                </div>
            </div>
            
            <div class="bg-blue-600 text-white px-6 py-3 rounded-xl shadow-lg shadow-blue-600/30 text-center">
                <p class="text-xs uppercase opacity-80 mb-1 font-bold tracking-wider">Token Ujian</p>
                <h2 class="text-3xl font-mono font-bold tracking-widest" id="tokenDisplay">
                    <?= !empty($jadwal->token) ? $jadwal->token : '-' ?>
                </h2>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <?php
                // Hitung Statistik Sederhana dari Data Peserta
                $total = count($peserta);
                $selesai = 0; $sedang = 0; $blokir = 0; $belum = 0;
                foreach($peserta as $p) {
                    if($p['is_blocked'] == 1) $blokir++;
                    elseif($p['status'] == 1) $selesai++;
                    elseif($p['id_ujian_siswa']) $sedang++;
                    else $belum++;
                }
            ?>
            <div class="bg-gray-50 p-3 rounded-lg border text-center">
                <span class="block text-2xl font-bold text-gray-700"><?= $total ?></span>
                <span class="text-xs text-gray-500 uppercase font-bold">Total Siswa</span>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 text-center">
                <span class="block text-2xl font-bold text-blue-600"><?= $sedang ?></span>
                <span class="text-xs text-blue-600 uppercase font-bold">Mengerjakan</span>
            </div>
            <div class="bg-green-50 p-3 rounded-lg border border-green-100 text-center">
                <span class="block text-2xl font-bold text-green-600"><?= $selesai ?></span>
                <span class="text-xs text-green-600 uppercase font-bold">Selesai</span>
            </div>
            <div class="bg-red-50 p-3 rounded-lg border border-red-100 text-center">
                <span class="block text-2xl font-bold text-red-600"><?= $blokir ?></span>
                <span class="text-xs text-red-600 uppercase font-bold">Terblokir</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4 bg-white p-3 rounded-xl shadow-sm border sticky top-0 z-30">
        
        <div class="flex items-center gap-2 w-full md:w-auto">
            <input type="text" id="searchSiswa" onkeyup="filterTabel()" placeholder="Cari Nama / Kelas..." class="p-2 border rounded-lg text-sm w-full md:w-64 focus:ring-blue-500">
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto bg-gray-100 p-1.5 rounded-lg">
            <select id="pilihAksi" class="bg-white border-0 text-sm rounded-md focus:ring-0 w-full md:w-48 py-2">
                <option value="">-- PILIH AKSI --</option>
                <option value="unlock">🔓 Buka Blokir</option>
                <option value="add_time">⏰ Tambah Waktu</option>
                <option value="stop">🛑 Paksa Selesai</option>
                <option value="reset">♻️ Reset Ujian (Ulang)</option>
            </select>
            <button onclick="eksekusiMassal()" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-black transition-colors flex items-center gap-2">
                <i class="fas fa-bolt text-yellow-400"></i> EKSEKUSI
            </button>
        </div>

        <div class="flex gap-2">
             <a href="<?= base_url('guru/ujian') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-bold">Kembali</a>
             <button onclick="window.location.reload()" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-sm font-bold"><i class="fas fa-sync"></i> Refresh</button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="tabelSiswa">
                <thead class="bg-gray-50 text-gray-700 uppercase font-bold text-xs border-b">
                    <tr>
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" id="checkAll" onclick="toggleAll(this)" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="p-4">Identitas Siswa</th>
                        <th class="p-4">Kelas</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Waktu</th>
                        <th class="p-4 text-center">Nilai</th>
                        <th class="p-4 text-center">IP Address</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(empty($peserta)): ?>
                        <tr><td colspan="8" class="p-8 text-center text-gray-500">Belum ada peserta di ruangan ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($peserta as $p): ?>
                        <tr class="hover:bg-blue-50 transition-colors group">
                            <td class="p-4 text-center">
                                <?php if($p['id_ujian_siswa']): ?>
                                    <input type="checkbox" name="ids[]" value="<?= $p['id_ujian_siswa'] ?>" class="chk-siswa w-4 h-4 rounded text-blue-600 focus:ring-blue-500 cursor-pointer">
                                <?php else: ?>
                                    <i class="fas fa-minus text-gray-300"></i>
                                <?php endif; ?>
                            </td>

                            <td class="p-4">
                                <div class="font-bold text-gray-800 text-base"><?= $p['nama_lengkap'] ?></div>
                                <div class="text-xs text-gray-500 font-mono"><?= $p['nomor_peserta'] ?></div>
                            </td>

                            <td class="p-4 text-gray-600 font-medium"><?= $p['nama_kelas'] ?></td>

                            <td class="p-4 text-center">
                                <?php if(!$p['id_ujian_siswa']): ?>
                                    <span class="badge-status st-belum">Belum Login</span>
                                <?php elseif($p['is_blocked'] == 1): ?>
                                    <span class="badge-status st-blokir"><i class="fas fa-lock mr-1"></i> Terblokir</span>
                                <?php elseif($p['status'] == 1): ?>
                                    <span class="badge-status st-selesai"><i class="fas fa-check mr-1"></i> Selesai</span>
                                <?php else: ?>
                                    <span class="badge-status st-sedang"><i class="fas fa-pencil-alt mr-1"></i> Mengerjakan</span>
                                <?php endif; ?>
                            </td>

                            <td class="p-4 text-center text-xs font-mono text-gray-600">
                                <?php 
                                    if($p['waktu_mulai']) {
                                        echo date('H:i', strtotime($p['waktu_mulai']));
                                        // Jika sedang mengerjakan, hitung sisa waktu (opsional via JS)
                                    } else {
                                        echo "-";
                                    }
                                ?>
                            </td>

                            <td class="p-4 text-center">
                                <?php if($p['status'] == 1): ?>
                                    <span class="font-bold text-lg text-gray-800"><?= $p['nilai'] ?></span>
                                <?php else: ?>
                                    <span class="text-gray-300">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="p-4 text-center text-xs text-gray-500 font-mono">
                                <?= $p['ip_address'] ?? '-' ?>
                            </td>

                            <td class="p-4 text-center">
                                <?php if($p['id_ujian_siswa']): ?>
                                    <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        
                                        <button onclick="aksiSatu('reset', <?= $p['id_ujian_siswa'] ?>, '<?= $p['nama_lengkap'] ?>')" class="w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-200" title="Reset Ujian">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                        
                                        <?php if($p['is_blocked'] == 1): ?>
                                        <button onclick="aksiSatu('unlock', <?= $p['id_ujian_siswa'] ?>, '<?= $p['nama_lengkap'] ?>')" class="w-8 h-8 rounded bg-yellow-100 text-yellow-600 hover:bg-yellow-200" title="Buka Blokir">
                                            <i class="fas fa-lock-open"></i>
                                        </button>
                                        <?php endif; ?>

                                        <?php if($p['status'] == 0 && $p['is_blocked'] == 0): ?>
                                        <button onclick="aksiSatu('stop', <?= $p['id_ujian_siswa'] ?>, '<?= $p['nama_lengkap'] ?>')" class="w-8 h-8 rounded bg-gray-100 text-gray-600 hover:bg-gray-200" title="Paksa Selesai">
                                            <i class="fas fa-stop"></i>
                                        </button>
                                        <?php endif; ?>

                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- 1. FITUR CHECKBOX SELECT ALL ---
    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('.chk-siswa');
        checkboxes.forEach(chk => chk.checked = source.checked);
    }

    // --- 2. FITUR FILTER PENCARIAN ---
    function filterTabel() {
        const input = document.getElementById('searchSiswa');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('tabelSiswa');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const tdNama = tr[i].getElementsByTagName('td')[1];
            const tdKelas = tr[i].getElementsByTagName('td')[2];
            if (tdNama || tdKelas) {
                const txtValueNama = tdNama.textContent || tdNama.innerText;
                const txtValueKelas = tdKelas.textContent || tdKelas.innerText;
                if (txtValueNama.toUpperCase().indexOf(filter) > -1 || txtValueKelas.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

    // --- 3. EKSEKUSI MASSAL ---
    function eksekusiMassal() {
        const aksi = document.getElementById('pilihAksi').value;
        if(!aksi) {
            Swal.fire('Pilih Aksi', 'Silakan pilih jenis aksi yang mau dilakukan.', 'warning');
            return;
        }

        // Ambil ID yang dicentang
        let ids = [];
        document.querySelectorAll('.chk-siswa:checked').forEach(c => ids.push(c.value));

        if(ids.length === 0) {
            Swal.fire('Belum Ada Siswa', 'Centang minimal satu siswa di tabel.', 'info');
            return;
        }

        // Logic Khusus Tambah Waktu (Minta Input Menit)
        if(aksi === 'add_time') {
            Swal.fire({
                title: 'Tambah Waktu Ujian',
                input: 'number',
                inputLabel: 'Masukkan jumlah menit tambahan',
                inputPlaceholder: 'Contoh: 10',
                showCancelButton: true,
                confirmButtonText: 'Tambahkan',
                inputValidator: (value) => {
                    if (!value || value <= 0) return 'Masukkan angka menit yang valid!';
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    kirimAksi(aksi, ids, result.value);
                }
            });
        } else {
            // Konfirmasi Biasa
            let textKonfirmasi = '';
            if(aksi === 'reset') textKonfirmasi = 'Data ujian & jawaban siswa akan DIHAPUS dan harus ulang dari awal.';
            if(aksi === 'stop') textKonfirmasi = 'Siswa akan dipaksa selesai dengan nilai saat ini.';
            if(aksi === 'unlock') textKonfirmasi = 'Siswa yang terblokir bisa melanjutkan ujian.';

            Swal.fire({
                title: 'Konfirmasi Eksekusi',
                text: `Anda akan melakukan aksi ke ${ids.length} siswa. ${textKonfirmasi}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Lakukan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    kirimAksi(aksi, ids, 0);
                }
            });
        }
    }

    // --- 4. AKSI SATUAN (SHORTCUT) ---
    function aksiSatu(aksi, id, nama) {
        let msg = '';
        if(aksi === 'reset') msg = `Reset ujian siswa ${nama}?`;
        if(aksi === 'stop') msg = `Paksa selesai siswa ${nama}?`;
        if(aksi === 'unlock') msg = `Buka blokir siswa ${nama}?`;

        Swal.fire({
            title: 'Konfirmasi',
            text: msg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya'
        }).then((res) => {
            if(res.isConfirmed) {
                kirimAksi(aksi, [id], 0);
            }
        });
    }

    // --- 5. AJAX PENGIRIM KE CONTROLLER ---
    // Pastikan URL controller-nya benar. Saya asumsikan di Guru/Ujian/aksi_monitoring
    // Jika Bos pakai controller monitoring terpisah, ganti urlnya jadi 'guru/monitoring/aksi'
    function kirimAksi(aksi, ids, menit) {
        Swal.fire({title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() }});
        
        $.post('<?= base_url('guru/monitoring/aksi') ?>', { // Sesuaikan route-nya Bos
            aksi: aksi,
            ids: ids,
            menit: menit
        })
        .done(function(res) {
            if(res.status === 'success') {
                Swal.fire('Berhasil!', 'Aksi berhasil dilakukan.', 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Gagal', res.msg || 'Terjadi kesalahan sistem.', 'error');
            }
        })
        .fail(function() {
            Swal.fire('Error', 'Gagal menghubungi server.', 'error');
        });
    }

    // --- 6. AUTO REFRESH (OPSIONAL) ---
    // Refresh otomatis setiap 30 detik agar data realtime
    setInterval(() => {
        // location.reload(); 
        // Kalau mau smooth refresh tanpa reload, harus pakai AJAX fetch tabel.
        // Untuk sekarang reload biasa saja cukup.
    }, 30000);

</script>

<?= $this->endSection() ?>