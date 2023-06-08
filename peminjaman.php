<?php
require 'function.php';
require 'cek.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Data Peminjaman</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <style>
            .zoomable{
                width: 100px;
            }
            .zoomable:hover{
                transform: scale(2);
                transition: 0.5s ease;
            }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">
            <div style="display: flex; align-items: center;">
                <img src="assets/img/Logo_SCR.png" alt="Logo" width="50" height="50">
                    <div style="margin-left: 10px; text-align: center;">
                        <span style="font-size: 20px; display: block;">Inventaris</span>
                        <span style="font-size: 12px; display: block;">Lab SCR</span>
                    </div>
            </div>
        </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>

        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link" href="index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <a class="nav-link" href="masuk.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-cube"></i></div>
                        Data Barang
                    </a>
                    <a class="nav-link" href="peminjaman.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                        Data Peminjaman
                    </a>
                    <a class="nav-link" href="admin.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                        Kelola Admin
                    </a>
                </div>
                </div>
                <div class="sb-sidenav-footer">
                <a class="nav-link" href="logout.php">
                    Logout
                </a>
                </div>
            </nav>
            </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Data Peminjaman</h1>

                    <div class="card mb-4">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPeminjaman">
                                Tambah Data Peminjaman
                            </button>
                            <br>
                            <div class="row mt-4">
                                <div class="col">
                                    <form method="post" class="form-inline">
                                        <input type="date" name="tgl_mulai" class="form-control">
                                        <input type="date" name="tgl_selesai" class="form-control ml-3">
                                        <br>
                                        <button type="submit" name="filter_tgl" class="btn btn-info ml-3">filter</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Gambar</th>
                                            <th>Bentuk Peminjaman</th>
                                            <th>Jumlah</th>
                                            <th>Penanggung Jawab</th>
                                            <th>Peminjam</th>
                                            <th>Nomor Handphone</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        if(isset($_POST['filter_tgl'])){
                                            $mulai = $_POST['tgl_mulai'];
                                            $selesai = $_POST['tgl_selesai'];

                                            if($mulai!=null || $selesai!=null){
                                                $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM peminjaman p, stock s WHERE s.idbarang = p.idbarang and tanggal BETWEEN '$mulai' and DATE_ADD('$selesai',INTERVAL 1 DAY)");
                                            } else {
                                                $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM peminjaman p, stock s WHERE s.idbarang = p.idbarang");
                                            }
                                            
                                        } else {
                                            $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM peminjaman p, stock s WHERE s.idbarang = p.idbarang");
                                        }

                                        
                                        while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                                            $idp = $data['idpeminjaman'];
                                            $tanggal = $data['tanggal'];
                                            $namabarang = $data['namabarang'];
                                            $jumlah = $data['jumlah'];
                                            $penanggung_jawab = $data['penanggung_jawab'];
                                            $peminjam = $data['peminjam'];
                                            $nohp = $data['no_telepon'];
                                            $status = $data['status'];
                                            $idbrg = $data['idbarang'];
                                            
                                            //cek apakah ada gambar
                                            $gambar = $data['image']; //ambil gambar
                                            if($gambar==null){
                                                //jika tidak ada gambar
                                                $img = 'No Photo';
                                            } else {
                                                //jika ada gambar
                                                $img = '<img src="images/'.$gambar.'" class="zoomable">';
                                            }
                                        ?>
                                            <tr>
                                                <td><?= $tanggal; ?></td>
                                                <td><?= $img; ?></td>
                                                <td><?= $namabarang; ?></td>
                                                <td><?= $jumlah; ?></td>
                                                <td><?= $penanggung_jawab; ?></td>
                                                <td><?= $peminjam; ?></td>
                                                <td><?= $nohp; ?></td>
                                                <td><?= $status; ?></td>
                                                <td>

                                                <?php
                                                    //cek status
                                                    if($status=='Dipinjam'){
                                                        echo '<button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#edit'.$idp.'">
                                                        Selesai
                                                        </button>';
                                                    } else {
    
                                                    }
                                                ?>
                                                </td>
                                            </tr>
                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit<?=$idp;?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Selesaikan</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <form method="post" action="peminjaman.php">
                                                            <div class="modal-body">
                                                                Apakah Barang ini sudah selesai dipinjam
                                                                <br>
                                                                <br>
                                                                <input type="hidden" name="idpeminjaman" value="<?=$idp;?>">
                                                                <input type="hidden" name="idbarang" value="<?=$idbrg;?>">
                                                                <button type="submit" class="btn btn-primary" name="barangkembali">Iya</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>  
                                       
                                        <?php } ?>


                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy;
                            <?= date('Y'); ?> Your Website</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>
</body>

<!-- The Modal -->
<div class="modal" id="addPeminjaman">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Data Peminjaman</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="post">

                    <select name="barangnya" class="form-control">
                        <?php
                        $ambilsemuadatanya = mysqli_query($conn, "SELECT * FROM stock");
                        while ($fetcharray = mysqli_fetch_array($ambilsemuadatanya)) {
                            $namabarangnya = $fetcharray['namabarang'];
                            $idbarangnya = $fetcharray['idbarang'];
                        ?>

                            <option value="<?php echo $idbarangnya; ?>"><?php echo $namabarangnya; ?></option>

                        <?php
                        }
                        ?>
                    </select>

                    <br>
                    <input type="number" min="0" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" name="jumlah" placeholder="Jumlah Barang" class="form-control" required> 
                    <br>
                    <input type="text" name="peminjam" placeholder="Nama Peminjam" class="form-control" required>
                    <br>
                    <input type="text" name="no_telepon" placeholder="Nomor Telepon" class="form-control" required>
                    <br>
                    <input type="text" name="penanggung_jawab" placeholder="Penanggung Jawab" class="form-control" required>
                    <br>

                    <button type="submit" class="btn btn-primary" name="pinjam">Submit</button>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
           

        </div>
    </div>
</div>
</html>

