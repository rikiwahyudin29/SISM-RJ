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
                <h1 class="text-2xl font-bold text-gray-800"><?= esc($jadwal['nama_ujian']) ?></h1>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded border border-blue-200 mt-2 inline-block">
                    <?= esc($jadwal['nama_mapel']) ?>
                </span>
                <div class="flex items-center gap-3 mt-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1"><i class="fas fa-clock"></i> <?= esc($jadwal['durasi']) ?> Menit</span>
                    <span class="flex items-center gap-1"><i class="fas fa-calendar"></i> <?= date('d M Y H:i', strtotime($jadwal['waktu_mulai'])) ?></span>
                </div>
            </div>
            
            <div class="bg-blue-600 text-white px-6 py-3 rounded-xl shadow-lg shadow-blue-600/30 text-center">
                <p class="text-xs uppercase opacity-80 mb-1 font-bold tracking-wider">Token Ujian</p>
                <h2 class="text-3xl font-mono font-bold tracking-widest" id="tokenDisplay">
                    <?= !empty($jadwal['token']) ? esc($jadwal['token']) : '-' ?>
                </h2>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-gray-50 p-3 rounded-lg border text-center">
                <span class="block text-2xl font-bold text-gray-700"><?= $stats['total'] ?></span>
                <span class="text-xs text-gray-500 uppercase font-bold">Total Siswa</span>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 text-center">
                <span class="block text-2xl font-bold text-blue-600"><?= $stats['sedang'] ?></span>
                <span class="text-xs text-blue-600 uppercase font-bold">Mengerjakan</span>
            </div>
            <div class="bg-green-50 p-3 rounded-lg border border-green-100 text-center">
                <span class="block text-2xl font-bold text-green-600"><?= $stats['sudah'] ?></span>
                <span class="text-xs text-green-600 uppercase font-bold">Selesai</span>
            </div>
            <div class="bg-red-50 p-3 rounded-lg border border-red-100 text-center">
                <span class="block text-2xl font-bold text-red-600"><?= $stats['total'] - ($stats['sudah'] + $stats['sedang']) ?></span>
                <span class="text-xs text-red-600 uppercase font-bold">Belum Login</span>
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
                <thead>
                    <tr>
                        <th class="p-4 w-4">...</th>
                        <th class="p-4 text-left text-xs font-medium text-gray-300 uppercase">IDENTITAS</th>
                        <th class="p-4 text-left text-xs font-medium text-gray-300 uppercase">MAPEL</th> <th class="p-4 text-center text-xs font-medium text-gray-300 uppercase">WAKTU</th>
                        <th class="p-4 text-center text-xs font-medium text-gray-300 uppercase">NILAI</th>
                        <th class="p-4 text-center text-xs font-medium text-gray-300 uppercase">STATUS</th>
                        <th class="p-4 text-center text-xs font-medium text-gray-300 uppercase">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(empty($peserta)): ?>
                        <tr><td colspan="7" class="p-8 text-center text-gray-500">Belum ada peserta di ruangan ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($peserta as $p): ?>
                        <tr class="hover:bg-blue-50 transition-colors group">
                            <td class="p-4 text-center">
                                <?php if($p['id_sesi']): ?>
                                    <input type="checkbox" name="ids[]" value="<?= $p['id_sesi'] ?>" class="chk-siswa w-4 h-4 rounded text-blue-600 focus:ring-blue-500 cursor-pointer">
                                <?php else: ?>
                                    <i class="fas fa-minus text-gray-300"></i>
                                <?php endif; ?>
                            </td>

                            <td class="p-4">
                                <div class="font-bold text-gray-800 text-base"><?= esc($p['nama_lengkap']) ?></div>
                                <div class="text-xs text-gray-500 font-mono"><?= esc($p['nis']) ?> • <?= esc($p['nama_kelas']) ?></div>
                            </td>

                            <td class="p-4">
                                <span class="bg-blue-900 text-blue-300 text-[10px] font-bold px-2 py-1 rounded border border-blue-700">
                                    <?= esc($jadwal['nama_mapel']) ?>
                                </span>
                            </td>

                            <td class="p-4 text-center text-xs font-mono text-gray-600">
                                <?php 
                                    if($p['waktu_mulai']) {
                                        echo date('H:i', strtotime($p['waktu_mulai']));
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

                            <td class="p-4 text-center">
                                <?php if(!$p['id_sesi']): ?>
                                    <span class="badge-status st-belum">Belum Login</span>
                                <?php elseif($p['is_blocked'] == 1): ?>
                                    <span class="badge-status st-blokir"><i class="fas fa-lock mr-1"></i> Terblokir</span>
                                <?php elseif($p['status'] == 1): ?>
                                    <span class="badge-status st-selesai"><i class="fas fa-check mr-1"></i> Selesai</span>
                                <?php else: ?>
                                    <span class="badge-status st-sedang"><i class="fas fa-pencil-alt mr-1"></i> Mengerjakan</span>
                                <?php endif; ?>
                            </td>

                            <td class="p-4 text-center">
                                <?php if($p['id_sesi']): ?>
                                    <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick="aksiSatu('reset', <?= $p['id_sesi'] ?>, '<?= $p['nama_lengkap'] ?>')" class="w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-200" title="Reset Ujian">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                        <?php if($p['is_blocked'] == 1): ?>
                                        <button onclick="aksiSatu('unlock', <?= $p['id_sesi'] ?>, '<?= $p['nama_lengkap'] ?>')" class="w-8 h-8 rounded bg-yellow-100 text-yellow-600 hover:bg-yellow-200" title="Buka Blokir">
                                            <i class="fas fa-lock-open"></i>
                                        </button>
                                        <?php endif; ?>
                                        <?php if($p['status'] == 0 && $p['is_blocked'] == 0): ?>
                                        <button onclick="aksiSatu('stop', <?= $p['id_sesi'] ?>, '<?= $p['nama_lengkap'] ?>')" class="w-8 h-8 rounded bg-gray-100 text-gray-600 hover:bg-gray-200" title="Paksa Selesai">
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
    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('.chk-siswa');
        checkboxes.forEach(chk => chk.checked = source.checked);
    }

    function filterTabel() {
        const input = document.getElementById('searchSiswa');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('tabelSiswa');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const tdNama = tr[i].getElementsByTagName('td')[1];
            if (tdNama) {
                const txtValue = tdNama.textContent || tdNama.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

    function eksekusiMassal() {
        const aksi = document.getElementById('pilihAksi').value;
        if(!aksi) { Swal.fire('Pilih Aksi', 'Silakan pilih jenis aksi.', 'warning'); return; }

        let ids = [];
        document.querySelectorAll('.chk-siswa:checked').forEach(c => ids.push(c.value));

        if(ids.length === 0) { Swal.fire('Belum Ada Siswa', 'Centang minimal satu siswa.', 'info'); return; }

        if(aksi === 'add_time') {
            Swal.fire({
                title: 'Tambah Waktu', input: 'number', inputPlaceholder: 'Menit',
                showCancelButton: true, confirmButtonText: 'Tambahkan'
            }).then((result) => {
                if (result.isConfirmed) kirimAksi(aksi, ids, result.value);
            });
        } else {
            Swal.fire({
                title: 'Konfirmasi', text: `Proses ${ids.length} siswa?`, icon: 'warning',
                showCancelButton: true, confirmButtonText: 'Ya'
            }).then((result) => {
                if (result.isConfirmed) kirimAksi(aksi, ids, 0);
            });
        }
    }

    function aksiSatu(aksi, id, nama) {
        Swal.fire({
            title: 'Konfirmasi', text: `${aksi.toUpperCase()} siswa ${nama}?`, icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya'
        }).then((res) => {
            if(res.isConfirmed) kirimAksi(aksi, [id], 0);
        });
    }

    function kirimAksi(aksi, ids, menit) {
        Swal.fire({title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() }});
        $.post('<?= base_url('guru/monitoring/aksi') ?>', { aksi: aksi, ids: ids, menit: menit })
        .done(function(res) {
            if(res.status === 'success') {
                Swal.fire('Berhasil!', 'Aksi berhasil.', 'success').then(() => location.reload());
            } else {
                Swal.fire('Gagal', res.msg, 'error');
            }
        });
    }
    
    // Auto Refresh agar data realtime
    setInterval(() => { location.reload(); }, 30000);
</script>

<?= $this->endSection() ?>