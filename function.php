<?php
session_start();

//Membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","lb_scr");

// Fungsi untuk menghitung total barang
function getTotalBarang()
{
    global $conn;
    $query = "SELECT COUNT(*) AS total FROM stock";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

// Fungsi untuk menghitung total barang masuk
function getTotalBarangMasuk()
{
    global $conn;
    $query = "SELECT SUM(stock) AS total FROM stock";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

//Fungsi untuk menghitung total peminjaman
function getTotalPeminjaman()
{
    global $conn;
    $query = "SELECT COUNT(*) AS total FROM peminjaman";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

// Fungsi untuk menghitung total barang peminjaman
function getTotalBarangPeminjaman()
{
    global $conn;
    $query = "SELECT SUM(jumlah) AS total FROM peminjaman";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

//Menambah data baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn,"insert into stock (namabarang, deskripsi, stock) values('$namabarang','$deskripsi','$stock')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
};

//Menambah barang masuk
if(isset($_POST['addbarangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang = '$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$jumlah;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, jumlah, penerima, keterangan) values('$barangnya','$jumlah','$penerima','$keterangan')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock ='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}

//Menambah barang keluar
if(isset($_POST['barangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $jumlah = $_POST['jumlah'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang = '$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang-$jumlah;

    $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang, jumlah, penerima) values('$barangnya','$jumlah','$penerima')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock ='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtokeluar&&$updatestockmasuk){
        header('location:peminjaman.php');
    } else {
        echo 'Gagal';
        header('location:peminjaman.php');
    }
}

//Update info barang
if(isset($_POST['updatebarang'])){
    $idbrg = $_POST['idbrg'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn,"update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang ='$idbrg'");
    if($update){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//Menghapus Item
if(isset($_POST['hapusbarang'])){
    $idbrg = $_POST['idbrg'];

    $hapus = mysqli_query($conn,"delete from stock where idbarang ='$idbrg'");
    if($delete){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//Update Data Barang Masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idbrg = $_POST['idbrg'];
    $idm = $_POST['idm'];
    $keterangan = $_POST['keterangan'];
    $jumlah = $_POST['jumlah'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$idbrg'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    $jumlahskrg = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk = '$idm'");
    $jumlahnya = mysqli_fetch_array($jumlahskrg);
    $jumlahskrg = $jumlahnya['jumlah'];

    if ($jumlah > $jumlahskrg) {
        $selisih = $jumlah - $jumlahskrg;
        $kurangi = $stocksekarang - $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock = '$kurangi' WHERE idbarang = '$idbrg'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET jumlah = '$jumlah', keterangan = '$keterangan' WHERE idmasuk = '$idm'");

        if ($kurangistocknya && $updatenya) {
            header('Location: masuk.php');
            exit();
        } else {
            echo 'Gagal';
            header('Location: masuk.php');
            exit();
        }
    } else {
        $selisih = $jumlahskrg - $jumlah;
        $kurangi = $stocksekarang + $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock = '$kurangi' WHERE idbarang = '$idbrg'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET jumlah = '$jumlah', keterangan = '$keterangan' WHERE idmasuk = '$idm'");

        if ($kurangistocknya && $updatenya) {
            header('Location: masuk.php');
            exit();
        } else {
            echo 'Gagal';
            header('Location: masuk.php');
            exit();
        }
    }
}




// Menghapus Barang Masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idbrg = $_POST['idbrg'];
    $jumlah = $_POST['jumlah'];
    $idm = $_POST['idm'];

    // Ambil data stok barang
    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$idbrg'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    // Hitung selisih stok setelah penghapusan barang masuk
    $selisih = $stok - $jumlah;

    // Update stok barang
    $updating = mysqli_query($conn, "UPDATE stock SET stock = '$selisih' WHERE idbarang = '$idbrg'");
    
    // Hapus data barang masuk
    $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk = '$idm'");

    if ($updating && $hapusdata) {
        header('location: masuk.php');
    } else {
        header('location: masuk.php');
    }
}


//Menambah Data Peminjaman
if(isset($_POST['pinjam'])){
    $idbarang = $_POST['barangnya'];
    $idp = $_POST['idp'];
    $status = $_POST['status'];
    $jumlah = $_POST['jumlah'];
    $penerima = $_POST['penerima'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang ='$idbrg'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    $jumlahPskrg = mysqli_query($conn, "select * from peminjaman where idpeminjaman ='$idp'");
    $jumlahnya = mysqli_fetch_array($jumlahPskrg);
    $jumlahPskrg = $jumlahnya['jumlah'];

    if($jumlah > $jumlahskrg){
        $selisih = $jumlah - $jumlahskrg;
        $kurangi = $stocksekarang - $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock = '$kurangi' where idbarang = '$idbrg'");
        $updatenya = mysqli_query($conn, "update peminjaman set jumlah = 'jumlah', keterangan='$keterangan' where idpeminjaman = '$idp'");
            if($kurangistocknya&&$updatenya){
                header('location:peminjaman.php');
            } else {
                echo 'Gagal';
                header('location:peminjaman.php');
            }
    }else {
        $selisih = $jumlahskrg - $jumlah;
        $kurangi = $stocksekarang + $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock = '$kurangi' where idbarang = '$idbrg'");
        $updatenya = mysqli_query($conn, "update peminjaman set jumlah = 'jumlah', keterangan='$keterangan' where idpeminjaman = '$idm'");
            if($kurangistocknya&&$updatenya){
                header('location:peminjaman.php');
            } else {
                echo 'Gagal';
                header('location:peminjaman.php');
            }
    }
}
?>