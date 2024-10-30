<?php
$hasilbarang = [];
if (file_exists('data_barang.txt')) {
    $hasilbarang = json_decode(file_get_contents('data_barang.txt'), true);
}

if (isset($_POST['kirim'])) {
    $namaBarang = trim($_POST['namaBarang']);
    $kategoriBarang = trim($_POST['kategoriBarang']);
    $hargaBarang = trim($_POST['hargaBarang']);

    // Validasi input
    if (!empty($namaBarang) && !empty($kategoriBarang)) {
        $hasilbarang[] = [
            'namaBarang' => $namaBarang,
            'kategoriBarang' => $kategoriBarang,
            'hargaBarang' => intval($hargaBarang)
        ];
        file_put_contents('data_barang.txt', json_encode($hasilbarang));
        header('Location: barang.php'); // Redirect untuk mencegah resubmission
        exit;
    }
}

if (isset($_GET['aksi'])) {
    $key = $_GET['key'];
    if ($_GET['aksi'] == 'hapus') {
        unset($hasilbarang[$key]);
        $hasilbarang = array_values($hasilbarang); // Mengatur ulang indeks setelah penghapusan
        file_put_contents('data_barang.txt', json_encode($hasilbarang));
        header('Location: barang.php');
        exit;
    } elseif ($_GET['aksi'] == 'edit') {
        $namaBarang = $hasilbarang[$key]['namaBarang'];
        $kategoriBarang = $hasilbarang[$key]['kategoriBarang'];
        $hargaBarang = $hasilbarang[$key]['hargaBarang'];
    }
}

if (isset($_POST['edit'])) {
    $key = $_POST['key'];
    $namaBarang = trim($_POST['namaBarang']);
    $kategoriBarang = trim($_POST['kategoriBarang']);
    $hargaBarang = trim($_POST['hargaBarang']);

    if (!empty($namaBarang) && !empty($kategoriBarang) && !empty($hargaBarang)) {
        $hasilbarang[$key] = [
            'namaBarang' => $namaBarang,
            'kategoriBarang' => $kategoriBarang,
            'hargaBarang' => intval($hargaBarang)
        ];
        file_put_contents('data_barang.txt', json_encode($hasilbarang));
        header('Location: barang.php');
        exit;
    }
}

$hasilCari = $hasilbarang;
if (isset($_POST['submit_cari'])) {
    $cari = strtolower(trim($_POST['cari']));
    $hasilCari = array_filter($hasilbarang, function ($item) use ($cari) {
        return (strpos(strtolower($item['namaBarang']), $cari) !== false) || (strpos(strtolower($item['kategoriBarang']), $cari) !== false) || (strpos(strtolower($item['hargaBarang']), $cari) !== false);
    });
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Tiga</title>
</head>

<body>
    <!-- Form untuk tambah atau edit buku -->
    <form action="" method="post">
        <label for="namaBarang">Nama Barang</label>
        <input type="text" name="namaBarang" id="namaBarang"
            value="<?= isset($namaBarang) ? htmlspecialchars($namaBarang) : '' ?>" required>
        <br>
        <label for="kategoriBarang">KategoriBarang</label>
        <input type="text" name="kategoriBarang" id="kategoriBarang"
            value="<?= isset($kategoriBarang) ? htmlspecialchars($kategoriBarang) : '' ?>" required>
        <br>
        <label for="hargaBarang">Harga Barang</label>
        <input type="text" name="hargaBarang" id="hargaBarang"
            value="<?= isset($hargaBarang) ? htmlspecialchars($hargaBarang) : '' ?>" required>
        <br>
        <?php if (isset($key)): ?>
            <input type="hidden" name="key" value="<?= $key ?>">
            <input type="submit" name="edit" value="Edit">
        <?php else: ?>
            <input type="submit" name="kirim" value="Tambah Barang">
        <?php endif; ?>
    </form>

    <!-- Form pencarian -->
    <form action="" method="post">
        <label for="cari">Cari</label>
        <input type="text" name="cari" id="cari">
        <br>
        <input type="submit" name="submit_cari" value="Cari">
    </form>

    <!-- Tabel daftar buku -->
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kategori Barang</th>
            <th>Harga Barang</th>
            <th>Aksi</th>
        </tr>
        
        <?php if (!empty($hasilCari)): ?>
            <?php foreach ($hasilCari as $key => $value): ?>
                
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= htmlspecialchars($value['namaBarang']) ?></td>
                    <td><?= htmlspecialchars($value['kategoriBarang']) ?></td>
                    <td><?= htmlspecialchars($value['hargaBarang']) ?></td>
                    <td>
                        <a href="<?= $_SERVER['PHP_SELF'] . '?aksi=edit&key=' . $key ?>">Edit</a>
                        <a href="<?= $_SERVER['PHP_SELF'] . '?aksi=hapus&key=' . $key ?>"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Tidak ada data barang yang ditemukan.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>

</html>


