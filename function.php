<?php
session_start();

//Membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","lb_scr");

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
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $jumlah = $_POST['jumlah'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang = '$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$jumlah;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, jumlah, penerima) values('$barangnya','$jumlah','$penerima')");
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
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
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

?>