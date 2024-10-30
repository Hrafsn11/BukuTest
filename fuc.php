<?php
// Fungsi untuk membuat struktur tabel HTML
// Fungsi untuk mengisi data ke dalam tabel
function generateTable($data) {
    $html = '';
    foreach ($data as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) {
            $html .= "<td>$cell</td>";
        }
        $html .= '</tr>';
    }
    return $html;
}

// Data yang ingin ditampilkan dalam tabel
$data = [
    ['Nama' => 'John Doe', 'Umur' => 30, 'Kota' => 'Jakarta'],
    ['Nama' => 'Jane Smith', 'Umur' => 25, 'Kota' => 'ArAB'],
    ['Nama' => 'Alice Johnson', 'Umur' => 35, 'Kota' => 'Surabaya'],
];

// Panggil fungsi untuk membuat tabel dan mengisi data

$tableBody = generateTable($data);

// Gabungkan header dan body tabel
$tabelHTML = $tableBody ;

// Tampilkan tabel di halaman
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel Data</title>
</head>
<body>
    <h1>Tabel Data Pengguna</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Nama</th>
            <th>Umur</th>
            <th>Kota</th>
        </tr>

    <?php echo $tabelHTML; ?>
    </table>
    
</body>
</html>
