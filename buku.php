<?php
$hasilBuku = [];
if (file_exists('data_buku.txt')) {
    $hasilBuku = json_decode(file_get_contents('data_buku.txt'), true);
}

if (isset($_POST['kirim'])) {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $bulan = trim( $_POST['bulan']);

    // Validasi input
    if (!empty($judul) && !empty($penulis)) {
        $hasilBuku[] = [
            'judul' => $judul,
            'penulis' => $penulis,
            'bulan' => $bulan
        ];
        file_put_contents('data_buku.txt', json_encode($hasilBuku));
        header('Location: buku.php'); // Redirect untuk mencegah resubmission
        exit;
    }
}

if (isset($_GET['aksi'])) {
    $key = $_GET['key'];
    if ($_GET['aksi'] == 'hapus') {
        unset($hasilBuku[$key]);
        $hasilBuku = array_values($hasilBuku); // Mengatur ulang indeks setelah penghapusan
        file_put_contents('data_buku.txt', json_encode($hasilBuku));
        header('Location: buku.php');
        exit;
    } elseif ($_GET['aksi'] == 'edit') {
        $judul = $hasilBuku[$key]['judul'];
        $penulis = $hasilBuku[$key]['penulis'];
        $bulan = $hasilBuku[$key]['bulan'];
    }
}

if (isset($_POST['edit'])) {
    $key = $_POST['key'];
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $bulan = trim( $_POST['bulan']);

    if (!empty($judul) && !empty($penulis) && !empty($bulan)) {
        $hasilBuku[$key] = [
            'judul' => $judul,
            'penulis' => $penulis,
            'bulan' => $bulan
        ];
        file_put_contents('data_buku.txt', json_encode($hasilBuku));
        header('Location: buku.php');
        exit;
    }
}

$hasilCari = $hasilBuku;
if (isset($_POST['submit_cari'])) {
    $cari = strtolower(trim($_POST['cari']));
    $hasilCari = array_filter($hasilBuku, function($item) use ($cari) {
        return (strpos(strtolower($item['judul']), $cari) !== false) || (strpos(strtolower($item['penulis']), $cari) !== false) || (strpos(strtolower($item['bulan']), $cari) !== false);
        echo "hellow";
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
        <label for="judul">Judul</label>
        <input type="text" name="judul" id="judul" value="<?= isset($judul) ? htmlspecialchars($judul) : '' ?>" required>
        <br>
        <label for="penulis">Penulis</label>
        <input type="text" name="penulis" id="penulis" value="<?= isset($penulis) ? htmlspecialchars($penulis) : '' ?>" required>
        <br>
        <label for="bulan">bulan</label>
        <input type="text" name="bulan" id="bulan" value="<?= isset($bulan) ? htmlspecialchars($bulan) : '' ?>" required>
        <br>
        <?php if (isset($key)) : ?>
            <input type="hidden" name="key" value="<?= $key ?>">
            <input type="submit" name="edit" value="Edit">
        <?php else : ?>
            <input type="submit" name="kirim" value="Kirim">
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
            <th>Judul</th>
            <th>Penulis</th>
            <th>bulan</th>
            <th>Aksi</th>
        </tr>
        <?php if (!empty($hasilCari)): ?>
            <?php foreach ($hasilCari as $key => $value) : ?>
            <tr>
                <td><?= $key + 1 ?></td>
                <td><?= htmlspecialchars($value['judul']) ?></td>
                <td><?= htmlspecialchars($value['penulis']) ?></td>
                <td><?= htmlspecialchars($value['bulan']) ?></td>
                <td>
                    <a href="<?= $_SERVER['PHP_SELF'] . '?aksi=edit&key=' . $key ?>">Edit</a>
                    <a href="<?= $_SERVER['PHP_SELF'] . '?aksi=hapus&key=' . $key ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Tidak ada data buku yang ditemukan.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>