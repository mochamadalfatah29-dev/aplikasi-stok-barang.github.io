<?php
session_start();

$conn = mysqli_connect("localhost","root","","stockbarang");

if(!$conn){
    die("Koneksi gagal : " . mysqli_connect_error());
}

// Tambah barang
if(isset($_POST['addnew_barang'])){

    $nama = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['jumlah'];

    $query = mysqli_query($conn,
"INSERT INTO stock (namabarang, deskripsi, stock)
 VALUES ('$nama','$deskripsi','$stock')");

    if($query){
        echo "<script>
                alert('Data berhasil disimpan');
                window.location='index.php';
              </script>";
    }else{
        echo mysqli_error($conn);
    }
}

//barang masuk
 if(isset($_POST['barangmasuk'])){

    $idbarang = $_POST['idbarang'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['penerima'];

    // Simpan riwayat barang masuk
    mysqli_query($conn,"INSERT INTO masuk(idbarang,keterangan,jumlah)
    VALUES('$idbarang','$keterangan','$jumlah')");

    // Tambah stok otomatis
    mysqli_query($conn,"UPDATE  stock
    SET stock = stock + '$jumlah'
    WHERE idbarang='$idbarang'");

    echo "
    <script>
    alert('Data berhasil disimpan');
    window.location='masuk.php';
    </script>";
}

//barang keluar
 if(isset($_POST['barangkeluar'])){

    $idbarang = $_POST['idbarang'];
    $jumlah = $_POST['jumlah'];
    $penerima = $_POST['penerima'];

    // Cek stok barang
    $cekstoksekarang = mysqli_query($conn,"SELECT * FROM stock WHERE idbarang='$idbarang'");
    $ambildatanya = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildatanya['stock'];

    if($stoksekarang >= $jumlah){
        // Simpan riwayat barang keluar
        mysqli_query($conn,"INSERT INTO keluar(idbarang,penerima,jumlah)
        VALUES('$idbarang','$penerima','$jumlah')");

        // Kurangi stok otomatis
        mysqli_query($conn,"UPDATE  stock
        SET stock = stock - '$jumlah'
        WHERE idbarang='$idbarang'");

        echo "
        <script>
        alert('Data berhasil disimpan');
        window.location='keluar.php';
        </script>";
    }else{
        echo "
        <script>
        alert('Stok saat ini tidak mencukupi');
        window.location='keluar.php';
        </script>";
    }
}