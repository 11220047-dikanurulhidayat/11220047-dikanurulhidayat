<?php
include "koneksidb.php";

$mahasiswa = mysqli_query($conn, "SELECT * FROM m_mahasiswa");
$matakuliah = mysqli_query($conn, "SELECT * FROM m_matakuliah");

if (isset($_GET['id'])) {
    $b = mysqli_query($conn, "SELECT * FROM nilai WHERE id=" . $_GET['id']);
    $c = mysqli_fetch_assoc($b);

    // Ambil nilai NIM dan kode MK yang dipilih
    $selected_nim = $c['nim'];
    $selected_mk = $c['kode_mk'];

    // Inisialisasi array untuk opsi mahasiswa dan matakuliah
    $mahasiswa_options = array();
    $matakuliah_options = array();

    // Memasukkan semua opsi NIM ke dalam array
    while ($row = mysqli_fetch_assoc($mahasiswa)) {
        $nim_option = "<option value='" . $row['nim'] . "'" . ($selected_nim == $row['nim'] ? ' selected' : '') . ">" . $row['nim'] . " - " . $row['nama_mhs'] . "</option>";
        if ($selected_nim == $row['nim']) {
            // Pindahkan pilihan NIM yang dipilih ke posisi pertama
            array_unshift($mahasiswa_options, $nim_option);
        } else {
            $mahasiswa_options[] = $nim_option;
        }
    }

    // Memasukkan semua opsi kode MK ke dalam array
    while ($row = mysqli_fetch_assoc($matakuliah)) {
        $mk_option = "<option value='" . $row['kode_mk'] . "'" . ($selected_mk == $row['kode_mk'] ? ' selected' : '') . ">" . $row['kode_mk'] . " - " . $row['nama_mk'] . "</option>";
        if ($selected_mk == $row['kode_mk']) {
            // Pindahkan pilihan kode MK yang dipilih ke posisi pertama
            array_unshift($matakuliah_options, $mk_option);
        } else {
            $matakuliah_options[] = $mk_option;
        }
    }

    // Form ubah data
    echo "<table border='1' width='30%' align='center'>";
    echo "<form method='POST' action=''>";
    echo "<tr><th colspan='2'>UBAH DATA</th></tr>";
    echo "<tr><td>NIM</td><td>: <select name='npm'>";
    // Tampilkan opsi mahasiswa
    foreach ($mahasiswa_options as $option) {
        echo $option;
    }
    echo "</select></td></tr>";
    echo "<tr><td>MK</td><td>: <select name='mk'>";
    // Tampilkan opsi matakuliah
    foreach ($matakuliah_options as $option) {
        echo $option;
    }
    echo "</select></td></tr>";
    echo "<tr><td>THN AKADEMIK</td><td>: <input type='text' name='tahun' size='8' value='" . $c['thn_akademik'] . "'></td></tr>"; // Ubah menjadi input yang dapat diedit
    echo "<tr><td>NILAI</td><td>: <select name='nilai'>";
    echo "<option value='A'" . ($c['nilai'] == 'A' ? ' selected' : '') . ">A</option>"; 
    echo "<option value='B'" . ($c['nilai'] == 'B' ? ' selected' : '') . ">B</option>";
    echo "<option value='C'" . ($c['nilai'] == 'C' ? ' selected' : '') . ">C</option>";
    echo "<option value='D'" . ($c['nilai'] == 'D' ? ' selected' : '') . ">D</option>";
    echo "<option value='E'" . ($c['nilai'] == 'E' ? ' selected' : '') . ">E</option>";
    echo "</select></td></tr>";
    echo "<tr><th colspan='2'><input type='submit' value='UBAH' name='tombol_ubah'></th></tr>";
    echo "</form>";
    echo "</table>";
}

if (isset($_POST['tombol_ubah'])) {
    // Tangkap nilai tahun akademik yang diubah
    $tahun_akademik = $_POST['tahun'];

    // Lindungi dari serangan SQL Injection dengan mysqli_real_escape_string
    $tahun_akademik = mysqli_real_escape_string($conn, $tahun_akademik);

    // Update data di database
    $b = mysqli_query($conn, "UPDATE nilai SET nim='" . $_POST['npm'] . "', kode_mk='" . $_POST['mk'] . "', nilai='" . $_POST['nilai'] . "', thn_akademik='$tahun_akademik' WHERE id=" . $_GET['id']);
    echo "<META http-equiv=refresh content=\"0; URL=data02.php\">";
}


// Mengambil data nilai yang diurutkan berdasarkan ID tertinggi
$a = mysqli_query($conn, "SELECT a.*,b.nama_mhs,c.nama_mk FROM nilai a INNER JOIN m_mahasiswa b ON a.nim=b.nim INNER JOIN m_matakuliah c ON a.kode_mk=c.kode_mk ORDER BY a.id DESC");
$no = 0;

echo "<table border='1' width='80%' align='center'>";
echo "<tr><th> NO </th><th> NIM </th><th> NAMA </th><th> KODE MK</th><th> NAMA MK </th><th> THN AKADEMIK</th><th>NILAI</th><th>AKSI</th></tr>";

while ($b = mysqli_fetch_array($a)) {
    $no = $no + 1;
    echo "<tr><td> $no </td> <td> $b[nim] </td> <td> $b[nama_mhs] </td> <td>$b[kode_mk]</td> <td>$b[nama_mk]   </td> <td>$b[thn_akademik]   </td><td>$b[nilai]</td><td><a href='data02.php?id=$b[id]'>Ubah</a></td></tr>";
    echo " <br>";
}
?>
