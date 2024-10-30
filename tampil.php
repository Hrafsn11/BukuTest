<?php
$hasilBuku = [];
$originalData = []; // Variabel untuk menyimpan data asli

if (file_exists('data_buku.txt')) {
    $hasilBuku = json_decode(file_get_contents('data_buku.txt'), true);
    $originalData = $hasilBuku; // Simpan data asli
}

if (isset($_POST['kirim'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $hasilBuku[] = [
        'judul' => $judul,
        'penulis' => $penulis
    ];
    file_put_contents('data_buku.txt', json_encode($hasilBuku));
}

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $key = $_GET['key'];
        unset($hasilBuku[$key]);
        file_put_contents('data_buku.txt', json_encode(array_values($hasilBuku))); // Reindex dan simpan
        header('Location: buku.php');
        exit();
    } else if ($_GET['aksi'] == 'edit') {
        $key = $_GET['key'];
        $judul = $hasilBuku[$key]['judul'];
        $penulis = $hasilBuku[$key]['penulis'];
    }
}

if (isset($_POST['edit'])) {
    $key = $_POST['key'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $hasilBuku[$key] = [
        'judul' => $judul,
        'penulis' => $penulis
    ];
    file_put_contents('data_buku.txt', json_encode($hasilBuku));
    header('Location: buku.php');
    exit();
}

if (isset($_POST['cari'])) {
    $cari = $_POST['cari'];
    $hasilBuku = array_filter($originalData, function($item) use ($cari) {
        return (strpos(strtolower($item['judul']), strtolower($cari)) !== false) || (strpos(strtolower($item['penulis']), strtolower($cari)) !== false);
    });
} else {
    $hasilBuku = $originalData; // Kembalikan ke data asli jika tidak ada pencarian
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jurnal tiga</title>
</head>
<body>
    <form action="" method="post">
        <label for="">Judul</label>
        <input type="text" name="judul" id="judul" value="<?= isset($judul) ? $judul : '' ?>">
        <br>
        <label for="">Penulis</label>
        <input type="text" name="penulis" id="penulis" value="<?= isset($penulis) ? $penulis : '' ?>">
        <br>
        <?php if (isset($key)) : ?>
        <input type="hidden" name="key" value="<?= $key ?>">
        <input type="submit" name="edit" value="Edit">
        <?php else : ?>
        <input type="submit" name="kirim" value="kirim">
        <?php endif; ?>
    </form>    

    <form action="" method="post">
        <label for="">Cari</label>
        <input type="text" name="cari" id="cari">
        <br>
        <input type="submit" name="cari" value="Cari">
    </form>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($hasilBuku as $key => $value) : ?>
        <tr>
            <td><?= $key + 1 ?></td>
            <td><?= $value['judul'] ?></td>
            <td><?= $value['penulis'] ?></td>
            <td>
                <a href="<?= $_SERVER['PHP_SELF'] . '?aksi=edit&key=' . $key ?>">Edit</a>
                <a href="<?= $_SERVER['PHP_SELF'] . '?aksi=hapus&key=' . $key ?>">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>