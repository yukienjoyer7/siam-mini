<?php
function loadData($file) {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true);
}
function saveData($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}
function hitungTotalSKS($jadwal) {
    $total = 0;
    foreach ($jadwal as $j) $total += $j['sks'];
    return $total;
}
function tugasMendekatiDeadline($tugas) {
    $alerts = [];
    foreach ($tugas as $t) {
        $deadline = strtotime($t['deadline']);
        $diff = ($deadline - time()) / 86400;
        if ($diff <= 2 && $t['status'] == "Belum Selesai") {
            $alerts[] = "Tugas <b>" . $t['nama_tugas'] . "</b> akan berakhir pada tanggal " . $t['deadline'] . ".";
        }
    }
    return $alerts;
}

$jadwal = loadData("data_jadwal.json");
$tugas  = loadData("data_tugas.json");

if (isset($_GET['hapus_jadwal'])) {
    $id = (int)$_GET['hapus_jadwal'];
    if (isset($jadwal[$id])) {
        unset($jadwal[$id]);
        $jadwal = array_values($jadwal);
        saveData("data_jadwal.json", $jadwal);
    }
    header("Location: index.php");
    exit;
}

if (isset($_GET['hapus_tugas'])) {
    $id = (int)$_GET['hapus_tugas'];
    if (isset($tugas[$id])) {
        unset($tugas[$id]);
        $tugas = array_values($tugas);
        saveData("data_tugas.json", $tugas);
    }
    header("Location: index.php");
    exit;
}

$hari_ini = date("l");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siam Mini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand text-dark" href="#">Siam Mini</a>
        </div>
    </nav>
    <hr>

    <div class="container mt-5">

        <div class="mb-5">
            <h1 class="display-4 fw-light">Dashboard</h1>
            <p class="lead text-muted">Ringkasan jadwal dan tugas perkuliahan.</p>
        </div>

        <div class="row mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="stat-card h-100">
                    <h5 class="fw-normal mb-3">Total SKS Diambil</h5>
                    <p class="display-6 fw-bold text-dark"><?= hitungTotalSKS($jadwal) ?> SKS</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="stat-card h-100">
                    <h5 class="fw-normal mb-3">Peringatan Deadline</h5>
                    <?php
                    $alerts = tugasMendekatiDeadline($tugas);
                    if (count($alerts) > 0) {
                        foreach ($alerts as $a) echo "<div class='alert alert-warning p-2 mb-2'>$a</div>";
                    } else {
                        echo "<p class='text-muted mb-0'>Tidak ada tugas yang mendekati deadline.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">Jadwal Kuliah Hari Ini (<?= $hari_ini ?>)</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Mata Kuliah</th>
                                        <th>Jam</th><th>Ruangan</th>
                                        <th>Dosen</th>
                                        <th>SKS</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ada = false;
                                    foreach ($jadwal as $i => $j) {
                                        if (strtolower($j['hari']) == strtolower($hari_ini)) {
                                            echo "<tr>
                                            <td>{$j['mata_kuliah']}</td>
                                            <td>{$j['jam']}</td><td>{$j['ruangan']}</td>
                                            <td>{$j['dosen']}</td><td>{$j['sks']}</td>
                                            <td>
                                                <a href='?hapus_jadwal=$i'
                                                class='btn btn-outline-danger btn-sm'>
                                                <i class='bi bi-trash'></i>
                                                </a>
                                            </td>
                                            </tr>";
                                            $ada = true;
                                        }
                                    }
                                    if (!$ada) echo "<tr><td colspan='6' class='text-center text-muted fst-italic py-4'>Tidak ada jadwal.</td></tr>";
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Daftar Tugas Belum Selesai</h5>
                            <a href="add_tugas.php" class="btn btn-accent btn-sm">
                                <i class="bi bi bi-card-checklist"></i> Tambah Tugas
                            </a>
                        </div>
                        <div class="table-responsive">
                             <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama Tugas</th>
                                        <th>Mata Kuliah</th>
                                        <th>Deadline</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ada = false;
                                    foreach ($tugas as $i => $t) {
                                        if ($t['status'] == "Belum Selesai") {
                                            echo "
                                            <tr>
                                                <td>{$t['nama_tugas']}</td>
                                                <td>{$t['mata_kuliah']}</td>
                                                <td>{$t['deadline']}</td>
                                                <td>
                                                    <a href='?hapus_tugas=$i'
                                                    class='btn btn-outline-danger btn-sm''>
                                                    <i class='bi bi-trash'></i>
                                                    </a>
                                                </td>
                                            </tr>";
                                            $ada = true;
                                        }
                                    }
                                    if (!$ada) echo
                                    "<tr>
                                        <td colspan='5' class='text-center text-muted fst-italic py-4'>Semua tugas sudah selesai.</td>
                                    </tr>";
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Seluruh Jadwal Kuliah</h5>
                            <a href="add_jadwal.php" class="btn btn-accent btn-sm">
                                <i class="bi bi-calendar-plus"></i> Tambah Jadwal
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th>Mata Kuliah</th>
                                        <th>Jam</th><th>Ruangan</th>
                                        <th>Dosen</th><th>SKS</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (count($jadwal) > 0) {
                                        foreach ($jadwal as $i => $j) {
                                            echo "<tr>
                                            <td>{$j['hari']}</td><td>{$j['mata_kuliah']}</td>
                                            <td>{$j['jam']}</td><td>{$j['ruangan']}</td>
                                            <td>{$j['dosen']}</td><td>{$j['sks']}</td>
                                            <td>
                                                <a href='?hapus_jadwal=$i'
                                                class='btn btn-outline-danger btn-sm'>
                                                <i class='bi bi-trash'></i>
                                                </a>
                                            </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo
                                        "<tr>
                                            <td colspan='7' class='text-center text-muted fst-italic py-4'>Belum ada jadwal yang disimpan.</td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <footer class="text-center py-2 mt-2">
        <p class="text-muted small">Made With ❤️ and Lots of 🍉 by Dionisius Seraf Saputra</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
