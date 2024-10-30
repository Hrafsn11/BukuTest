<?php
session_start();

// Inisialisasi data buku
if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = [
        ["judul" => "Buku A", "penulis" => "Penulis A"],
        ["judul" => "Buku B", "penulis" => "Penulis B"],
    ];
}

// Tambah Data Buku
if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $_SESSION['books'][] = ["judul" => $judul, "penulis" => $penulis];
}

// Hapus Data Buku
if (isset($_POST['hapus'])) {
    $id = $_POST['id_hapus'];
    if (isset($_SESSION['books'][$id])) {
        unset($_SESSION['books'][$id]);
    }
}

// Edit Data Buku
if (isset($_POST['edit'])) {
    $id = $_POST['id_edit'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    if (isset($_SESSION['books'][$id])) {
        $_SESSION['books'][$id] = ["judul" => $judul, "penulis" => $penulis];
        unset($_SESSION['edit_id']); // Reset edit session after updating
    }
}

// Set ID untuk edit 
if (isset($_POST['edit_button'])) {
    $_SESSION['edit_id'] = $_POST['id_edit'];
}

// Cari Data Buku
$cari = '';
if (isset($_POST['cari'])) {
    $cari = $_POST['kata_kunci'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Manajemen Data Buku</h1>

    <h2>Tambah Buku</h2>
    <form method="POST">
        <input type="text" name="judul" placeholder="Judul Buku" required>
        <input type="text" name="penulis" placeholder="Penulis Buku" required>
        <button type="submit" name="tambah">Tambah</button>
    </form>

    <h2>Daftar Buku</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['books'] as $id => $book): ?>
                <?php if ($cari === '' || strpos($book['judul'], $cari) !== false || strpos($book['penulis'], $cari) !== false): ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td>
                            <?php if (isset($_SESSION['edit_id']) && $_SESSION['edit_id'] == $id): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id_edit" value="<?php echo $id; ?>">
                                    <input type="text" name="judul" value="<?php echo $book['judul']; ?>" required>
                                    <input type="text" name="penulis" value="<?php echo $book['penulis']; ?>" required>
                                    <button type="submit" name="edit">Edit</button>
                                </form>
                            <?php else: ?>
                                <?php echo $book['judul']; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id_hapus" value="<?php echo $id; ?>">
                                <button type="submit" name="hapus" onclick="return confirm('Anda yakin ingin menghapus?')">Hapus</button>
                            </form>
                            <?php if (!isset($_SESSION['edit_id']) || $_SESSION['edit_id'] != $id): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id_edit" value="<?php echo $id; ?>">
                                    <button type="submit" name="edit_button">Edit</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Cari Buku</h2>
    <form method="POST">
        <input type="text" name="kata_kunci" placeholder="Masukkan kata kunci" value="<?php echo $cari; ?>">
        <button type="submit" name="cari">Cari</button>
    </form>

</body>
</html>