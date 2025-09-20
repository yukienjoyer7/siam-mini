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
        "nama_tugas" => $_POST['nama_tugas'],
        "mata_kuliah" => $_POST['mata_kuliah'],
        "deadline" => $_POST['deadline'],
        "status" => "Belum Selesai"
    ];
    $tugas = loadData("data_tugas.json");
    $tugas[] = $new;
    saveData("data_tugas.json", $tugas);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
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
        <h2 class="mb-4 fw-light">Tambah Tugas Baru</h2>
        <form method="post">
            <div class="mb-3">
                <label for="nama_tugas" class="form-label">Nama Tugas</label>
                <input type="text" class="form-control" name="nama_tugas" required>
            </div>
            <div class="mb-3">
                <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
                <input type="text" class="form-control" name="mata_kuliah" required>
            </div>
            <div class="mb-3">
                <label for="deadline" class="form-label">Deadline</label>
                <input type="date" class="form-control" name="deadline" required>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                 <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                 <button type="submit" class="btn btn-accent">Simpan Tugas</button>
            </div>
        </form>
    </div>
</body>
</html>
