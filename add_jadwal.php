<?php
function loadData($file) {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true);
}
function saveData($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $new = [
        "mata_kuliah" => $_POST['mata_kuliah'],
        "hari" => $_POST['hari'],
        "jam" => $_POST['jam'],
        "ruangan" => $_POST['ruangan'],
        "dosen" => $_POST['dosen'],
        "sks" => (int)$_POST['sks']
    ];
    $jadwal = loadData("data_jadwal.json");
    $jadwal[] = $new;
    saveData("data_jadwal.json", $jadwal);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="style.css" rel = "stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="mb-4 fw-light">Tambah Jadwal Baru</h2>
        <form method="post">
            <div class="mb-3">
                <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
                <input type="text" class="form-control" name="mata_kuliah" required>
            </div>
            <div class="mb-3">
                <label for="hari" class="form-label">Hari</label>
                <input type="text" class="form-control" name="hari" required>
            </div>
            <div class="mb-3">
                <label for="jam" class="form-label">Jam</label>
                <input type="text" class="form-control" name="jam" placeholder="Contoh: 08:00 - 09:40" required>
            </div>
            <div class="mb-3">
                <label for="ruangan" class="form-label">Ruangan</label>
                <input type="text" class="form-control" name="ruangan" required>
            </div>
            <div class="mb-3">
                <label for="dosen" class="form-label">Dosen</label>
                <input type="text" class="form-control" name="dosen" required>
            </div>
            <div class="mb-3">
                <label for="sks" class="form-label">SKS</label>
                <input type="number" class="form-control" name="sks" required>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                 <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                 <button type="submit" class="btn btn-accent">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</body>
</html>
