<?php
// Pastikan semua variabel terkait formulir telah diinisialisasi dengan nilai default
session_start();

$oldDb = null; 
$newDb = null;

// $oldDbHost = '';
$oldDbName = '';
$oldDbUser = '';
$oldDbPass = '';
$newDbName = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldDbName = isset($_POST['oldDbName']) ? $_POST['oldDbName'] : '';
    $oldDbUser = isset($_POST['oldDbUser']) ? $_POST['oldDbUser'] : '';
    $oldDbPass = isset($_POST['oldDbPass']) ? $_POST['oldDbPass'] : '';
    $newDbName = isset($_POST['newDbName']) ? $_POST['newDbName'] : '';

    try {
        // Buat koneksi ke database lama
        $oldDb = new PDO("mysql:host=localhost;dbname=$oldDbName", $oldDbUser, $oldDbPass);
        $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Simpan detail koneksi dalam session untuk database lama
        $_SESSION['oldDbDetails'] = [
            'name' => $oldDbName,
            'user' => $oldDbUser,
            'pass' => $oldDbPass
        ];
        var_dump($_SESSION['oldDbDetails']);

        // Buat koneksi ke database baru jika password kosong
        if (empty($newDbPass)) {
            // Simpan detail koneksi dalam session untuk database baru
            $_SESSION['newDbDetails'] = [
                'user' => $oldDbUser,
                'pass' => $oldDbPass,
                'name' => $newDbName
            ];  
            var_dump($_SESSION['newDbDetails']);

            // Drop database baru jika sudah ada
            $newDb = new PDO("mysql:host=localhost", $oldDbUser, $oldDbPass);
            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Buat database baru jika nama tidak kosong
            if (!empty($newDbName)) {
                $newDb->exec("USE $newDbName");
            } else {
                echo '<div class="alert alert-danger" role="alert">New database name is required.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">New database password is required.</div>';
        }

        // Tutup koneksi
        echo '<div class="alert alert-primary text-center" role="alert">Connection success database : ' . $newDbName . '</div>';
        $oldDb = null;
        $newDb = null;
    } catch(PDOException $e) {
        // Tangani kesalahan jika koneksi gagal
        // echo '<div class="alert alert-danger" role="alert">Connection failed: ' . $e->getMessage() . '</div>';
    }
}
?>



<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <!-- Bootstrap 4 CSS -->
    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css'>
    <!-- Telephone Input CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/css/intlTelInput.css'>
    <!-- Icons CSS -->
    <link rel='stylesheet' href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'>
    <!-- Nice Select CSS -->
    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <link rel="stylesheet" href="css/style.css">

    <title>FORM MIGRATE!</title>
</head>

<body>


    <!---header-and-banner-section-->
    <section class="multi_step_form w-100 float-left form-main-con pt-4 padding-bottom " id="Contact">
        <div class="container">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#connectionModal">
                Database Connection
            </button>
            <div class="position-relative">
                <div class="generic-title text-center">
                    <h6>FORM MIGRATE</h6>
                    <h2 class="mb-0">FORM MIGRATE DATABASE DAN FILE</h2>
                    <!-- <img src="images/post/IMAGE-EVENTS-1536802783.jpg"> -->
                </div>
                <div class="generic-title text-center">
                    <h4 class="mt-3">Progres Form Migrate </h4>
                    <div id="msform" class="msform">
                        <!-- progressbar -->
                        <ul id="progressbar">
                            <li class="active"><span class="ion-ios-albums-outline"></span> Migrate Database </li>
                            <li><span class="ion-ios-folder-outline"></span> Migrate File </li>
                            <li><span class="ion-ios-compose"></span> Selesai </li>
                        </ul>
                        <!-- fieldsets -->
                        <fieldset>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div id="form_result">
                                        <h4 class="text-center pb-4">TEKAN BUTTON FILE MIGRATE</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div id="contactpage"
                                        class="contact-form wow slideInRight text-lg-left text-center">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-6">
                                                <div class="form-group mb-0 text-center">
                                                    <label>Button File Migrate </label>
                                                    <form method="POST" action="">
                                                        <input type="submit" class="btn btn-primary" name="move_image"
                                                            value="Migrate Image">
                                                    </form>
                                                    <?php 
                                   function move_image($folder_asal, $folder_tujuan) {
                                    // Membuat folder tujuan jika belum ada
                                    if (!is_dir($folder_tujuan)) {
                                        // Buat folder baru rekursif
                                        if (!mkdir($folder_tujuan, 0777, true)) {
                                            echo "Gagal membuat folder tujuan ".$folder_tujuan."<br>";
                                            return;
                                        }
                                    }
                                
                                    $files = glob($folder_asal . '/*.{jpg,jpeg,png,gif,pdf,docs}', GLOB_BRACE);
                                
                                    foreach ($files as $file) {
                                        $nama_file = basename($file);
                                        $path_tujuan = $folder_tujuan.'/'.$nama_file;                                                                           
                                            if (file_exists($path_tujuan)) {
                                                unlink($path_tujuan); // Hapus file dengan nama yang sama
                                                echo "File ".$nama_file." sudah ada di folder tujuan, file lama dihapus.<br>";
                                            }
                                            
                                            // Salin file ke folder tujuan
                                            if (copy($file, $path_tujuan)) {
                                                echo "File ".$nama_file." berhasil dipindahkan dan dicopy ke ".$folder_tujuan.".<br>";
                                            } else {
                                                echo "Gagal memindahkan file ".$nama_file.".<br>";
                                            }
                                    }
                                }
                                
                                $_dirPost	= 'images/post'; // POST
                                $_dirEmp	= 'images/employees'; // EMP
                                $_dirLHKPN	= 'images/lhkpn'; // LHKPN
                                $_dirKategori = 'images/kategori'; // KATEGORI
                                $_dirGalery = 'images/galery'; // GALERI & BANNER
                                $_dirFiles = 'images/files'; // FILES PENGUMUMAN
                                $_dirProf = 'images/prof'; // PROFIL
                                $_dirLink	= 'images/socials'; // SOSIAL MEDIA
                                
                                if (isset($_POST['move_image'])) {
                                    $folder_move = array(
                                        array('web_old/images/uploads/banners', $_dirGalery),
                                        array('web_old/images/uploads/prestasi', $_dirGalery),
                                        array('web_old/images/uploads/employees', $_dirEmp),
                                        array('web_old/images/uploads/leaders', $_dirEmp),
                                        array('web_old/images/uploads/events', $_dirPost),
                                        array('web_old/images/uploads/news', $_dirPost),
                                        array('web_old/images/uploads/inst', $_dirLink),
                                        array('web_old/downloads', $_dirFiles)
                                    );
                                
                                    foreach ($folder_move as $folder_mv) {
                                        move_image($folder_mv[0], $folder_mv[1]);
                                    }
                                    echo '<div class="alert alert-success" role="alert">Data berhasil dipindahkan!</div>';
                                }
                                
                                
                            ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" id="submit" data-mdb-ripple-init data-mdb-modal-init
                                data-mdb-target="#exampleModal"
                                class="next action-button appointment-btn mt-4">Next</button>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div id="form_result">
                                        <h4 class="text-center pb-4">TEKAN BUTTON MIGRATE DATABASE</h4>
                                    </div>
                                    <div id="contactpage"
                                        class="contact-form wow slideInRight text-lg-left text-center">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-6">
                                                <div class=" form-group mb-0 text-center">
                                                    <label>Button Migrate DATABASE</label>
                                                    <form method="POST" action="">

                                                        <!-- <input type="submit" class="btn btn-primary" name="migrateAll"
                                                            value="Migrate All Tables"> -->
                                                        <!-- <input type="submit" class="btn btn-success" name="migrateTable"
                                                                value="Migrate New Tables"> -->
                                                        <input type="submit" class="btn btn-primary" name="setuser"
                                                            value="setuser">
                                                        <input type="submit" class="btn btn-primary" name="profile"
                                                            value="profile">
                                                        <input type="submit" class="btn btn-primary" name="employess"
                                                            value="employess">
                                                        <input type="submit" class="btn btn-primary" name="social"
                                                            value="social">
                                                        <input type="submit" class="btn btn-primary" name="vidios"
                                                            value="vidios">
                                                        <input type="submit" class="btn btn-primary" name="dinas"
                                                            value="dinas">
                                                        <input type="submit" class="btn btn-primary" name="counternews"
                                                            value="counternews">
                                                        <input type="submit" class="btn btn-primary" name="counterpost"
                                                            value="counterpost">
                                                        <input type="submit" class="btn btn-primary" name="ppidpost"
                                                            value="ppidpost">
                                                        <input type="submit" class="btn btn-primary"
                                                            name="counterpengumumna" value="counterpengumumna">
                                                        <input type="submit" class="btn btn-primary" name="download"
                                                            value="download">
                                                        <input type="submit" class="btn btn-primary" name="serv"
                                                            value="serv">
                                                        <input type="submit" class="btn btn-primary" name="visit"
                                                            value="visit">
                                                        <input type="submit" class="btn btn-primary" name="setform"
                                                            value="setform">
                                                    </form>
                                                    <?php
                                                    // Fungsi migrasi untuk tabel pertama
                                                    function setMenu($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = " DROP TABLE IF EXISTS `set_menu` ; CREATE TABLE IF NOT EXISTS `set_menu` (
                                                    `mn_id` char(3) NOT NULL,
                                                    `mn_txt` varchar(50) NOT NULL,
                                                    `mn_url` tinytext NOT NULL,
                                                    `mn_tar` varchar(15) NOT NULL,
                                                    `parent` char(3) NOT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`mn_id`)
                                                    )";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'set_menu' created successfully.";
                                                    } else {
                                                    echo "Error creating table 'set_menu'.<br>";
                                                    }


                                                    // Insert data set menu
                                                    $categories = array(
                                                    array('001', 'Profil', 'javascript:void(0)', '_parent', '0', '1', '1533562415', '2024-03-30', '1533562415',
                                                    '2024-03-30'),
                                                    array('002', 'Visi Misi', '', '', '001', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('003', 'Layanan', '#', '_self', '0', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('004', 'Layanan Pemerintahan', '004/', '', '003', '1', '1533562415', '2024-03-30', '1533562415',
                                                    '2024-03-30'),
                                                    array('005', 'Layanan Publik', '004/', '', '003', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('006', 'Prestasi', '001/1536802599', '', '001', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('007', 'PPID', '', '', '0', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('008', 'ProfiL PPID Pelaksana', '', '', '007', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('009', 'Visi Misi PPID Pelaksana', '', '', '007', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('010', 'Struktur Organisasi PPID Pelaksana', '', '', '007', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('011', 'SKPPID', '', '', '007', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('012', 'Maklumat Pelayanan', '', '', '007', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('013', 'Kebijakan & Regulasi', '', '', '007', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('014', 'Layanan', '', '', '007', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('015', 'Informasi Publik', '', '', '007', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('016', 'DIP', '', '', '015', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('017', 'Informasi Berkala', '', '', '015', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('018', 'Informasi Serta Merta', '', '', '015', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('019', 'Informasi Setiap Saat', '', '', '015', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    array('020', 'Informasi yang Dikecualikan', '', '', '015', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30'),
                                                    );

                                                    $stmtBaru = $newDb->prepare("INSERT INTO set_menu (mn_id, mn_txt, mn_url, mn_tar, parent, _active, _cre, _cre_date,
                                                    _chg, _chg_date)
                                                    VALUES (:mn_id, :mn_txt, :mn_url, :mn_tar, :parent, :_active, :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($categories as $category) {
                                                    $stmtBaru->execute([
                                                    ':mn_id' =>$category[0] ,
                                                    ':mn_txt' =>$category[1] ,
                                                    ':mn_url' => $category[2],
                                                    ':mn_tar' => $category[3],
                                                    ':parent' =>$category[4] ,
                                                    '_active' =>$category[5] ,
                                                    '_cre' =>$category[6],
                                                    '_cre_date' =>$category[7] ,
                                                    '_chg' => $category[8],
                                                    '_chg_date' => $category[9]
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "<br>Migration for set_menu successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }


                                                    function pubBanner($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `pub_banner` ;
                                                    CREATE TABLE IF NOT EXISTS `pub_banner` (
                                                    `ban_id` varchar(10) NOT NULL,
                                                    `ban_title` varchar(50) NOT NULL,
                                                    `ban_desk` tinytext,
                                                    `ban_img` varchar(30) DEFAULT NULL,
                                                    `ban_stat` char(3) NOT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`ban_id`)
                                                    )";

                                                    $newDb->exec($createTableQuery);

                                                    // Mengambil data dari tabel lama
                                                    $stmt = $oldDb->prepare("SELECT * FROM tb_banners WHERE category = '2' ");
                                                    $stmt->execute();
                                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_banner (ban_id, ban_title, ban_desk, ban_img, ban_stat, _active, _cre,
                                                    _cre_date, _chg, _chg_date)
                                                    VALUES (:ban_id, :ban_title, :ban_desk, :ban_img, :ban_stat, :_active, :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $row) {
                                                    // Memberikan jeda 1 detik
                                                    sleep(1);

                                                    $id = date('Y-m-d H:i:s');
                                                    $convert = strtotime($id) + 1;


                                                    $stmtBaru->execute([
                                                    ':ban_id' => $convert,
                                                    ':ban_title' => $row['title'],
                                                    ':ban_desk' => $row['description'],
                                                    ':ban_img' => $row['img'],
                                                    ':ban_stat' => '001',
                                                    ':_active' => $row['status'],
                                                    ':_cre' => str_repeat('1', 18),
                                                    ':_cre_date' => date('Y-m-d'),
                                                    ':_chg' => str_repeat('1', 18),
                                                    ':_chg_date' => date('Y-m-d')
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for Table data pub_baner successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }

                                                    function pesan($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `pesan` ;
                                                    CREATE TABLE IF NOT EXISTS pesan (
                                                    idpesan timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                                    nama varchar(50) NOT NULL,
                                                    email varchar(100) NOT NULL,
                                                    pesan varchar(255) NOT NULL,
                                                    otp char(6) NOT NULL,
                                                    status char(1) NOT NULL DEFAULT '0',
                                                    PRIMARY KEY (idpesan)
                                                    )
                                                    ";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'pesan' created successfully.<br> ";
                                                    } else {
                                                    echo "Error creating table 'pesan'.<br>";
                                                    }

                                                    }


                                                    function setCategory($oldDb, $newDb) {
                                                    // Create Table if not exists
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `set_category` ;
                                                    CREATE TABLE IF NOT EXISTS `set_category` (
                                                    `ca_id` char(3) NOT NULL,
                                                    `ca_nm` varchar(20) NOT NULL,
                                                    `ca_desk` tinytext NOT NULL,
                                                    `ca_icon` varchar(10) NOT NULL,
                                                    `fm_id` char(3) NOT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`ca_id`)
                                                    )
                                                    ";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'set_category' created successfully. <br>";
                                                    } else {
                                                    echo "Error creating table 'set_category'.<br>";
                                                    }

                                                    // Mendefinisikan nilai default
                                                    $defaultActive = '1';
                                                    $defaultCre = str_repeat('1', 18);
                                                    $defaultChg = str_repeat('1', 18);
                                                    $defaultDate = date('Y-m-d');

                                                    // Insert data kategori
                                                    $categories = array(
                                                    array('001', 'Berita', '','','001', $defaultActive, $defaultCre, $defaultDate, $defaultChg, $defaultDate),
                                                    array('002', 'Agenda Kegiatan', '','','002', $defaultActive, $defaultCre, $defaultDate, $defaultChg, $defaultDate),
                                                    array('003', 'Pengumuman', '','','004', $defaultActive, $defaultCre, $defaultDate, $defaultChg, $defaultDate),
                                                    array('004', 'Download', '','','004', $defaultActive, $defaultCre, $defaultDate, $defaultChg, $defaultDate),
                                                    array('005', 'Layanan', '','','001', $defaultActive, $defaultCre, $defaultDate, $defaultChg, $defaultDate),
                                                    array('006', 'PPID', '','','001', $defaultActive, $defaultCre, $defaultDate, $defaultChg, $defaultDate),
                                                    );

                                                    $stmtBaru = $newDb->prepare("INSERT INTO set_category (ca_id, ca_nm, ca_desk, ca_icon, fm_id, _active, _cre,
                                                    _cre_date, _chg, _chg_date)
                                                    VALUES (:ca_id, :ca_nm, :ca_desk, :ca_icon,:fm_id, :_active, :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($categories as $category) {
                                                    $stmtBaru->execute([
                                                    ':ca_id' => $category[0],
                                                    ':ca_nm' => $category[1],
                                                    ':ca_desk' => $category[2],
                                                    ':ca_icon' => $category[3],
                                                    ':fm_id' => $category[4],
                                                    ':_active' => $category[5],
                                                    ':_cre' => $category[6],
                                                    ':_cre_date' => $category[7],
                                                    ':_chg' => $category[8],
                                                    ':_chg_date' => $category[9]
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for set_category successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }






                                                    function setJabdept($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `set_jabdept` ;
                                                    CREATE TABLE IF NOT EXISTS `set_jabdept` (
                                                    `jab_id` char(3) NOT NULL,
                                                    `jab_nm` varchar(255) NOT NULL,
                                                    `stat` char(1) NOT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`jab_id`, `stat`)
                                                    )
                                                    ";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'set_jabdept' created successfully. <br>";
                                                    } else {
                                                    echo "Error creating table 'set_jabdept'.<br>";
                                                    }

                                                    // Insert data set menu
                                                    $categories = array(
                                                    array('001', 'Kepala Badan/Dinas/Badan/Kecamatan/Puskesmas', '1', '1', '555', '2024-04-05', '555', '2024-04-05')
                                                    );

                                                    $stmtBaru = $newDb->prepare("INSERT INTO `set_jabdept` (`jab_id`, `jab_nm`, `stat`, `_active`, `_cre`, `_cre_date`,
                                                    `_chg`, `_chg_date`)
                                                    VALUES (:jab_id, :jab_nm, :stat, :_active, :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($categories as $category) {
                                                    $stmtBaru->execute([
                                                    ':jab_id' => $category[0],
                                                    ':jab_nm' => $category[1],
                                                    ':stat' => $category[2],
                                                    '_active' => $category[3],
                                                    '_cre' => $category[4],
                                                    '_cre_date' => $category[5],
                                                    '_chg' => $category[6],
                                                    '_chg_date' => $category[7]
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for set_jabdept successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }


                                                    function setPage($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `set_page` ;
                                                    CREATE TABLE IF NOT EXISTS `set_page` (
                                                    `page_id` varchar(10) NOT NULL,
                                                    `page_name` varchar(50) NOT NULL,
                                                    `page_addr` varchar(50) NOT NULL,
                                                    `_active` char(1) NOT NULL,
                                                    `_cre` char(18) NOT NULL,
                                                    `_cre_date` date NOT NULL,
                                                    `_chg` char(18) NOT NULL,
                                                    `_chg_date` date NOT NULL,
                                                    PRIMARY KEY (`page_id`)
                                                    )
                                                    ";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'set_page' created successfully. <br>";
                                                    } else {
                                                    echo "Error creating table 'set_page'.<br>";
                                                    }

                                                    // Insert data kategori
                                                    $categories = array(
                                                    array('001', 'Roles Akses', 'setting_roles_akses', '1', '555', '2023-06-06', '111111111111111111', '2023-07-17'),
                                                    array('002', 'Roles Halaman', 'setting_roles_Halaman', '1', '111111111111111111', '2023-07-17',
                                                    '111111111111111111', '2023-07-17'),
                                                    array('003', 'Layout Form', 'setting_layout_Form', '1', '111111111111111111', '2023-07-17', '111111111111111111',
                                                    '2023-07-17'),
                                                    array('004', 'Web Banner', 'setting_web_banner', '1', '111111111111111111', '2023-07-17', '111111111111111111',
                                                    '2023-07-17'),
                                                    array('005', 'Web Menu', 'setting_web_menu', '1', '111111111111111111', '2023-07-17', '111111111111111111',
                                                    '2023-07-17'),
                                                    array('006', 'OPD Profil', 'Setting_OPD_Profil', '1', '111111111111111111', '2023-07-17', '1713774528',
                                                    '2024-04-23'),
                                                    array('007', 'OPD Sosial Media', 'Setting_OPD_Sosial-Media', '1', '111111111111111111', '2023-07-17', '1713774528',
                                                    '2024-04-23'),
                                                    array('008', 'OPD Bagian/bidang', 'Setting_OPD_Bidang', '1', '1533562415', '2024-04-01', '1713774528',
                                                    '2024-04-23'),
                                                    array('009', 'OPD Jabatan', 'Setting_OPD_Jabatan', '1', '1533562415', '2024-04-02', '1713774528', '2024-04-23'),
                                                    array('010', 'OPD Link Terkait', 'Setting_OPD_Link-Terkait', '1', '1533562415', '2024-04-02', '1713774528',
                                                    '2024-04-23'),
                                                    array('011', 'Master Data User', 'Master-Data_User', '1', '1533562415', '2024-04-02', '1713774528', '2024-04-23'),
                                                    array('012', 'Master Data Kategori', 'Master-Data_Kategori', '1', '1533562415', '2024-04-03', '1713774528',
                                                    '2024-04-23'),
                                                    array('013', 'Master Data Pegawai', 'Master-Data_Pegawai', '1', '1533562415', '2024-04-04', '1713774528',
                                                    '2024-04-23'),
                                                    array('014', 'Publikasi Data Berita', 'Publikasi_Data_Berita', '1', '1533562415', '2024-04-05', '1713774528',
                                                    '2024-04-23'),
                                                    array('015', 'Publikasi Data Agenda Kegiatan', 'Publikasi_Data_Agenda-Kegiatan', '1', '1533562415', '2024-04-05',
                                                    '1713774528', '2024-04-23'),
                                                    array('016', 'Publikasi Data Pengumuman', 'Publikasi_Data_Pengumuman', '1', '1533562415', '2024-04-05',
                                                    '1713774528', '2024-04-23'),
                                                    array('017', 'Publikasi Data Download', 'Publikasi_Data_Download', '1', '1533562415', '2024-04-05', '1713774528',
                                                    '2024-04-23'),
                                                    array('018', 'Publikasi Data Layanan', 'Publikasi_Data_Layanan', '1', '1533562415', '2024-04-05', '1713774528',
                                                    '2024-04-23'),
                                                    array('019', 'Publikasi Galery', 'Publikasi_Galery', '1', '1533562415', '2024-04-06', '1713774528', '2024-04-23')
                                                    );


                                                    $stmtBaru = $newDb->prepare("INSERT INTO `set_page` (`page_id`, `page_name`, `page_addr`, `_active`, `_cre`,
                                                    `_cre_date`, `_chg`, `_chg_date`)
                                                    VALUES (:page_id, :page_name, :page_addr, :_active, :_cre,:_cre_date, :_chg, :_chg_date) ");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($categories as $category) {
                                                    $stmtBaru->execute([
                                                    ':page_id' => $category[0],
                                                    ':page_name' => $category[1],
                                                    ':page_addr' => $category[2],
                                                    ':_active' => $category[3],
                                                    ':_cre' => $category[4],
                                                    ':_cre_date' => $category[5],
                                                    ':_chg' => $category[6],
                                                    ':_chg_date' => $category[7]
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for set_page successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }

                                                    }



                                                    function setRoles($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "DROP TABLE IF EXISTS `set_role` ;
                                                    CREATE TABLE IF NOT EXISTS `set_role` (
                                                    `ro_id` char(3) NOT NULL,
                                                    `ro_name` varchar(50) NOT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`ro_id`)
                                                    )
                                                    ";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'set_role' created successfully. <br>";
                                                    } else {
                                                    echo "Error creating table 'set_role'.<br>";
                                                    }

                                                    $categories = array(
                                                    array('001', 'Administrator', '1', '555', '2023-03-09', '555', '2023-03-09'),
                                                    );


                                                    $stmtBaru = $newDb->prepare("INSERT INTO `set_role` (`ro_id`, `ro_name`, `_active`, `_cre`, `_cre_date`, `_chg`,
                                                    `_chg_date`)
                                                    VALUES (:ro_id, :ro_name, :_active, :_cre,:_cre_date, :_chg, :_chg_date) ");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($categories as $category) {
                                                    $stmtBaru->execute([
                                                    ':ro_id' => $category[0],
                                                    ':ro_name' => $category[1],
                                                    ':_active' => $category[2],
                                                    ':_cre' => $category[3],
                                                    ':_cre_date' => $category[4],
                                                    ':_chg' => $category[5],
                                                    ':_chg_date' => $category[6]
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for set_role successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }


                                                    }

                                                    function setRules($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `set_rules` ;
                                                    CREATE TABLE IF NOT EXISTS `set_rules` (
                                                    `ro_id` char(3) NOT NULL,
                                                    `page_id` char(3) NOT NULL,
                                                    `rr_read` char(1) NOT NULL,
                                                    `rr_cre` char(1) NOT NULL,
                                                    `rr_up` char(1) NOT NULL,
                                                    `rr_del` char(1) NOT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`ro_id`, `page_id`)
                                                    )
                                                    ";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'set_rules' created successfully. <br>";
                                                    } else {
                                                    echo "Error creating table 'set_rules'.<br>";
                                                    }


                                                    $categories = array(
                                                    array('001', '001', '1', '1', '1', '1', '1', '111111111111111111', '2023-07-17', '1564468378', '2024-04-23'),
                                                    array('001', '002', '1', '1', '1', '1', '1', '1533562415', '2024-03-25', '1564468378', '2024-04-23'),
                                                    array('001', '003', '1', '1', '1', '1', '1', '1533562415', '2024-03-25', '1564468378', '2024-04-23'),
                                                    array('001', '004', '1', '1', '1', '1', '1', '1533562415', '2024-03-25', '1564468378', '2024-04-23'),
                                                    array('001', '005', '1', '1', '1', '1', '1', '1533562415', '2024-03-25', '1564468378', '2024-04-23'),
                                                    array('001', '006', '1', '1', '1', '1', '1', '1533562415', '2024-03-25', '1564468378', '2024-04-23'),
                                                    array('001', '007', '1', '1', '1', '1', '1', '1533562415', '2024-03-25', '1564468378', '2024-04-23'),
                                                    array('001', '008', '1', '1', '1', '1', '1', '1533562415', '2024-04-01', '1564468378', '2024-04-23'),
                                                    array('001', '009', '1', '1', '1', '1', '1', '1533562415', '2024-04-02', '1564468378', '2024-04-23'),
                                                    array('001', '010', '1', '1', '1', '1', '1', '1533562415', '2024-04-02', '1564468378', '2024-04-23'),
                                                    array('001', '011', '1', '1', '1', '1', '1', '1533562415', '2024-04-02', '1564468378', '2024-04-23'),
                                                    array('001', '012', '1', '1', '1', '1', '1', '1533562415', '2024-04-03', '1564468378', '2024-04-23'),
                                                    array('001', '013', '1', '1', '1', '1', '1', '1533562415', '2024-04-04', '1564468378', '2024-04-23'),
                                                    array('001', '014', '1', '1', '1', '1', '1', '1564468378', '2024-04-23', '1564468378', '2024-04-23'),
                                                    array('001', '015', '1', '1', '1', '1', '1', '1564468378', '2024-04-23', '1564468378', '2024-04-23'),
                                                    array('001', '016', '1', '1', '1', '1', '1', '1564468378', '2024-04-23', '1564468378', '2024-04-23'),
                                                    array('001', '017', '1', '1', '1', '1', '1', '1564468378', '2024-04-23', '1564468378', '2024-04-23'),
                                                    array('001', '018', '1', '1', '1', '1', '1', '1564468378', '2024-04-23', '1564468378', '2024-04-23'),
                                                    array('001', '019', '1', '1', '1', '1', '1', '1564468378', '2024-04-23', '1564468378', '2024-04-23')
                                                    );



                                                    $stmtBaru = $newDb->prepare("INSERT INTO `set_rules` (`ro_id`, `page_id`, `rr_read`, `rr_cre`, `rr_up`, `rr_del`,
                                                    `_active`, `_cre`, `_cre_date`, `_chg`, `_chg_date`)
                                                    VALUES (:ro_id, :page_id, :rr_read, :rr_cre, :rr_up, :rr_del, :_active, :_cre,:_cre_date, :_chg, :_chg_date) ");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($categories as $category) {
                                                    $stmtBaru->execute([
                                                    ':ro_id' => $category[0],
                                                    ':page_id' => $category[1],
                                                    ':rr_read' => $category[2],
                                                    ':rr_cre' => $category[3],
                                                    ':rr_up' => $category[4],
                                                    ':rr_del' => $category[5],
                                                    ':_active' => $category[6],
                                                    ':_cre' => $category[7],
                                                    ':_cre_date' => $category[8],
                                                    ':_chg' => $category[9],
                                                    ':_chg_date' => $category[10]
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for set_rules successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }


                                                    function filePost($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `pub_files` ;
                                                    CREATE TABLE IF NOT EXISTS `pub_files` (
                                                    `files_id` varchar(10) NOT NULL,
                                                    `post_id` varchar(10) NOT NULL,
                                                    `files_nm` tinytext DEFAULT NULL,
                                                    `files_down` varchar(10) NOT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`files_id`, `post_id`)
                                                    )";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'pub_files' created successfully.";
                                                    } else {
                                                    echo "Error creating table 'pub_files'.<br>";
                                                    }

                                                    // Mengambil data dari tabel lama
                                                    $stmt = $oldDb->prepare("SELECT * FROM tb_downloads");
                                                    $stmt->execute();
                                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_files (files_id, post_id, files_nm, files_down, _active, _cre,
                                                    _cre_date, _chg, _chg_date)
                                                    VALUES (:files_id, :post_id, :files_nm, :files_down, :_active, :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    // Inisialisasi counter ID
                                                    $idCounter = 1;

                                                    foreach ($data as $row) {
                                                    // Format ID menjadi tiga digit
                                                    $files_id = sprintf('%03d', $idCounter);

                                                    // Memberikan jeda 1 detik
                                                    sleep(1);

                                                    $stmtBaru->execute([
                                                    ':files_id' => $files_id,
                                                    ':post_id' => $row['id'],
                                                    ':files_nm' => $row['file'],
                                                    ':files_down' => $row['totdown'],
                                                    '_active' => 1,
                                                    '_cre' => str_repeat('1', 18),
                                                    '_cre_date' => date('Y-m-d'),
                                                    '_chg' => str_repeat('1', 18),
                                                    '_chg_date' => date('Y-m-d')
                                                    ]);

                                                    // Increment counter ID
                                                    $idCounter++;
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for Table pub_files successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }


                                                    function setUser($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `set_users` ;
                                                    CREATE TABLE IF NOT EXISTS `set_users` (
                                                    `us_nip` char(18) NOT NULL,
                                                    `us_nama` varchar(100) NOT NULL,
                                                    `us_email` varchar(50) NOT NULL,
                                                    `us_roles` char (3) NOT NULL,
                                                    `us_passwd` longtext NOT NULL,
                                                    `us_last` date NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`us_nip`)
                                                    )";

                                                    $newDb->exec($createTableQuery);
                                                    $cre_date = date('Y-m-d');

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'set_user' created successfully.";
                                                    } else {
                                                    echo "Error creating table 'set_user'.<br>";
                                                    }


                                                    // Mengambil data dari tabel lama
                                                    $stmt = $oldDb->prepare("SELECT * FROM tb_users");
                                                    $stmt->execute();
                                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


                                                    $stmtBaru = $newDb->prepare("INSERT INTO `set_users` (`us_nip`, `us_nama`, `us_email`, `us_roles`, `us_passwd`,
                                                    `us_last`, `_active`, `_cre`, `_cre_date`, `_chg`, `_chg_date`)
                                                    VALUES (:us_nip, :us_nama, :us_email, :us_roles, :us_passwd, :us_last, :_active, :_cre, :_cre_date, :_chg,
                                                    :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $category) {
                                                    $stmtBaru->execute([
                                                    ':us_nip' => $category['id'],
                                                    ':us_nama' => $category['full_name'],
                                                    ':us_email' => $category['email'],
                                                    ':us_roles' => str_pad($category['level'], 3, '0', STR_PAD_LEFT),
                                                    ':us_passwd' => $category['password'],
                                                    ':us_last' => NULL,
                                                    ':_active' => $category['status'],
                                                    '_cre' => str_repeat('1', 18),
                                                    '_cre_date' => date('Y-m-d'),
                                                    '_chg' => str_repeat('1', 18),
                                                    '_chg_date' => date('Y-m-d')
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for set_user successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }


                                                    function pubProfile($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `pub_profile` ;
                                                    CREATE TABLE IF NOT EXISTS `pub_profile` (
                                                    `prof_id` varchar(10) NOT NULL,
                                                    `prof_lnm` varchar(50) NOT NULL,
                                                    `prof_snm` varchar(20) NOT NULL,
                                                    `prof_addr` tinytext NOT NULL,
                                                    `prof_fax` varchar(20) NOT NULL,
                                                    `prof_telp` varchar(13) NOT NULL,
                                                    `prof_mail` varchar(50) NOT NULL,
                                                    `prof_pwd` varchar(50) DEFAULT NULL,
                                                    `prof_maps` varchar(50) NOT NULL,
                                                    `prof_skm` tinytext,
                                                    `prof_desk` tinytext,
                                                    `prof_lg` varchar(50) DEFAULT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`prof_id`)
                                                    )";

                                                    // Execute the create table query
                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'pub_profile' created successfully. <br>";
                                                    } else {
                                                    echo "Error creating table 'pub_profile'.<br>";
                                                    }



                                                    // Mengambil data dari tabel lama
                                                    $stmt = $oldDb->prepare("SELECT value FROM tb_options WHERE id IN (2, 6, 7, 8, 9) ");
                                                    $stmt->execute();
                                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);




                                                    $stmtBaru = $newDb->prepare("INSERT INTO `pub_profile` (`prof_id`, `prof_lnm`, `prof_snm`, `prof_addr`, `prof_fax`,
                                                    `prof_telp`, `prof_mail`,`prof_pwd`, `prof_maps`, `prof_skm`, `prof_desk`, `prof_lg`, `_active`, `_cre`,
                                                    `_cre_date`, `_chg`, `_chg_date`) VALUES (:prof_id, :prof_lnm, :prof_snm, :prof_addr, :prof_fax, :prof_telp,
                                                    :prof_mail, :prof_pwd, :prof_maps, :prof_skm, :prof_desk, :prof_lg, :_active, :_cre, :_cre_date, :_chg,
                                                    :_chg_date)");

                                                    // Begin transaction
                                                    $newDb->beginTransaction();

                                                    try {
                                                    $id = time() ;
                                                    $stmtBaru->execute([
                                                    ':prof_id' => $id,
                                                    ':prof_lnm' => $data[0]['value'],
                                                    ':prof_snm' => '',
                                                    ':prof_addr' => $data[2]['value'],
                                                    ':prof_fax' => $data[4]['value'],
                                                    ':prof_telp' => $data[3]['value'],
                                                    ':prof_mail' => $data[1]['value'],
                                                    ':prof_pwd' => '',
                                                    ':prof_maps' => '',
                                                    ':prof_skm' => '',
                                                    ':prof_desk' => '',
                                                    ':prof_lg' => '',
                                                    '_active' => '1',
                                                    '_cre' => str_repeat('1', 18),
                                                    '_cre_date' => date('Y-m-d'),
                                                    '_chg' => str_repeat('1', 18),
                                                    '_chg_date' => date('Y-m-d')
                                                    ]);


                                                    // Commit the transaction
                                                    $newDb->commit();
                                                    echo "Data inserted into 'pub_profile' successfully.<br>";
                                                    } catch (PDOException $e) {
                                                    // Rollback the transaction if an error occurred
                                                    $newDb->rollBack();
                                                    echo "Insertion failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }



                                                    function pubEmployes($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `pub_employess` ;
                                                    CREATE TABLE IF NOT EXISTS `pub_employees` (
                                                    emp_id char(10) NOT NULL,
                                                    emp_nip char(18) NOT NULL,
                                                    emp_nm varchar(100) NOT NULL,
                                                    emp_desk tinytext,
                                                    emp_ent date DEFAULT NULL,
                                                    emp_lhkpn char(15) DEFAULT NULL,
                                                    emp_img char(30) DEFAULT NULL,
                                                    jab_id char(3) DEFAULT NULL,
                                                    dept_id char(3) NOT NULL,
                                                    parent char(10) DEFAULT NULL,
                                                    _active char(1) DEFAULT NULL,
                                                    _cre char(18) DEFAULT NULL,
                                                    _cre_date date DEFAULT NULL,
                                                    _chg char(18) DEFAULT NULL,
                                                    _chg_date date DEFAULT NULL,
                                                    PRIMARY KEY (emp_id)
                                                    )";

                                                    // Eksekusi query untuk membuat tabel baru
                                                    $newDb->exec($createTableQuery);

                                                    // Periksa apakah tabel berhasil dibuat
                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'pub_employees' created successfully.<br>";
                                                    } else {
                                                    echo "Error creating table 'pub_employees'.<br>";
                                                    }

                                                    // Mengambil data dari tabel lama
                                                    $stmt = $oldDb->prepare("SELECT * FROM tb_pegawai");
                                                    $stmt->execute();
                                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    // Persiapan statement untuk menyisipkan data ke tabel baru
                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_employees (emp_id, emp_nip, emp_nm, emp_desk, emp_ent, emp_lhkpn,
                                                    emp_img, jab_id, dept_id, parent, _active, _cre, _cre_date, _chg, _chg_date)
                                                    VALUES (:emp_id, :emp_nip, :emp_nm, :emp_desk, :emp_ent, :emp_lhkpn, :emp_img, :jab_id, :dept_id, :parent, :_active,
                                                    :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    // Mulai transaksi untuk menyisipkan data ke tabel baru
                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $row) {
                                                    // Memberikan jeda 1 detik
                                                    sleep(1);


                                                    $cre_date = date('Y-m-d');

                                                    // tahun ent 01 - Januari
                                                    $tahun = $row['tahun'];
                                                    $emp_ent = $tahun. '-01-01';

                                                    // Eksekusi statement untuk menyisipkan data ke tabel baru
                                                    $stmtBaru->execute([
                                                    ':emp_id' => $row['id'],
                                                    ':emp_nip' => '',
                                                    ':emp_nm' => $row['name'],
                                                    ':emp_desk' => '', // Sesuaikan dengan deskripsi pegawai jika ada
                                                    ':emp_ent' => $emp_ent,
                                                    ':emp_lhkpn' => '', // Sesuaikan dengan status LHKPN jika ada
                                                    ':emp_img' => $row['img'],
                                                    ':jab_id' => '', // Sesuaikan dengan jabatan pegawai jika ada
                                                    ':dept_id' => '',
                                                    ':parent' => null, // asumsi parent dapat null
                                                    ':_active' => $row['status'],
                                                    ':_cre' => str_repeat('1', 18),
                                                    ':_cre_date' => $cre_date,
                                                    ':_chg' => str_repeat('1', 18),
                                                    ':_chg_date' => $cre_date
                                                    ]);
                                                    }

                                                    // Commit transaksi jika semua data berhasil disisipkan
                                                    $newDb->commit();
                                                    echo "Migration for Table 'pub_employees' successful.<br>";
                                                    } catch (PDOException $e) {
                                                    // Rollback transaksi jika terjadi kesalahan
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }



                                                    function pubSocial($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `pub_socials` ;
                                                    CREATE TABLE IF NOT EXISTS `pub_socials` (
                                                    `sos_id` varchar(10) NOT NULL,
                                                    `sos_nm` varchar(50) NOT NULL,
                                                    `sos_ic` varchar(30) NOT NULL,
                                                    `sos_url` tinytext,
                                                    `cat` char(3) NOT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`sos_id`)
                                                    )
                                                    ";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'pub_socials' created successfully.";
                                                    } else {
                                                    echo "Error creating table 'pub_socials'.<br>";
                                                    }

                                                    // Fetch data from the old table
                                                    $stmt = $oldDb->prepare("SELECT * FROM tb_apis");
                                                    $stmt->execute();
                                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_socials (sos_id, sos_nm, sos_ic, sos_url, cat, _active, _cre,
                                                    _cre_date, _chg, _chg_date)
                                                    VALUES (:sos_id, :sos_nm, :sos_ic, :sos_url, :cat, :_active, :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $row) {
                                                    // Memberikan jeda 1 detik
                                                    sleep(1);

                                                    $id = date('Y-m-d H:i:s');
                                                    $convert = strtotime($id) + 1;

                                                    $stmtBaru->execute([
                                                    ':sos_id' => $convert,
                                                    ':sos_nm' => $row['name'],
                                                    ':sos_ic' => '',
                                                    ':sos_url' => $row['value'],
                                                    ':cat' => '001',
                                                    ':_active' => '1',
                                                    ':_cre' => str_repeat('1', 18),
                                                    ':_cre_date' => date('Y-m-d'),
                                                    ':_chg' => str_repeat('1', 18),
                                                    ':_chg_date' => date('Y-m-d')
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for Table pub_socials successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }



                                                    function pubVidios($oldDb, $newDb) {
                                                        // Mengambil data dari tabel lama
                                                        $stmt = $oldDb->prepare("SELECT * FROM tb_video");
                                                        $stmt->execute();
                                                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                                                        $stmtBaru = $newDb->prepare("INSERT INTO pub_socials (sos_id, sos_nm, sos_ic, sos_url, cat, _active, _cre,
                                                    _cre_date, _chg, _chg_date)
                                                    VALUES (:sos_id, :sos_nm, :sos_ic, :sos_url, :cat, :_active, :_cre, :_cre_date, :_chg, :_chg_date)");
    
                                                        $newDb->beginTransaction();
    
                                                        try {
                                                        foreach ($data as $row) {
                                                        // Memberikan jeda 1 detik
                                                        sleep(1);
    
                                                        // $id = date('Y-m-d H:i:s');
                                                        // $convert = strtotime($id) + 1;
    
    
                                                        $stmtBaru->execute([
                                                            ':sos_id' => $row['id'],
                                                            ':sos_nm' => $row['title'],
                                                            ':sos_ic' => '',
                                                            ':sos_url' => $row['url'],
                                                            ':cat' => '003',
                                                            ':_active' => $row['status'],
                                                            ':_cre' => str_repeat('1', 18),
                                                            ':_cre_date' => date('Y-m-d'),
                                                            ':_chg' => str_repeat('1', 18),
                                                            ':_chg_date' => date('Y-m-d')
                                                        ]);
                                                        }
    
                                                        $newDb->commit();
                                                        echo "Migration for pub_vidios successful.<br>";
                                                        } catch (PDOException $e) {
                                                        $newDb->rollBack();
                                                        echo "Migration failed: " . $e->getMessage() . "<br>";
                                                        }
                                                        }


                                                    function setDinasPub($oldDb, $newDb){
                                                    // Fetch from the old table
                                                    $stmt = $oldDb->prepare("SELECT * FROM tb_dinas");
                                                    $stmt->execute();
                                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_socials (sos_id, sos_nm, sos_ic, sos_url, cat, _active, _cre,
                                                    _cre_date, _chg, _chg_date)
                                                    VALUES (:sos_id, :sos_nm, :sos_ic, :sos_url, :cat, :_active, :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $row) {
                                                    // Memberikan jeda 1 detik
                                                    sleep(1);

                                                    $id = date('Y-m-d H:i:s');
                                                    $convert = strtotime($id) + 1;

                                                    $stmtBaru->execute([
                                                    ':sos_id' => $convert,
                                                    ':sos_nm' => $row['name'],
                                                    ':sos_ic' => $row['img'],
                                                    ':sos_url' => $row['description'],
                                                    ':cat' => '002',
                                                    ':_active' => '1',
                                                    ':_cre' => str_repeat('1', 18),
                                                    ':_cre_date' => date('Y-m-d'),
                                                    ':_chg' => str_repeat('1', 18),
                                                    ':_chg_date' => date('Y-m-d')
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for Table pub_socials successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }

                                                    }



                                                    function pubCounterNews($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `pub_post` ;
                                                    CREATE TABLE IF NOT EXISTS `pub_post` (
                                                    `post_id` varchar(10) NOT NULL,
                                                    `post_judul` tinytext,
                                                    `post_desk` longtext,
                                                    `post_publish` date DEFAULT NULL,
                                                    `post_datex` date DEFAULT NULL,
                                                    `post_img` varchar(30) DEFAULT NULL,
                                                    `post_see` char(5) DEFAULT NULL,
                                                    `ca_id` char(3) DEFAULT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`post_id`)
                                                    )";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'pub_post' created successfully.";
                                                    } else {
                                                    echo "Error creating table 'pub_post'.<br>";
                                                    }

                                                    // Mengambil data dari tabel news
                                                    $stm1 = $oldDb->prepare("SELECT tb_news.*, tb_counter.idnews, tb_counter.total FROM `tb_counter`
                                                    RIGHT JOIN tb_news
                                                    ON tb_news.id = tb_counter.idnews WHERE category = 1");
                                                    $stm1->execute();
                                                    $data = $stm1->fetchAll(PDO::FETCH_ASSOC);

                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_post (post_id, post_judul, post_desk, post_publish, post_datex,
                                                    post_img, post_see, ca_id, _active, _cre, _cre_date, _chg, _chg_date)
                                                    VALUES (:post_id, :post_judul, :post_desk, :post_publish, :post_datex, :post_img, :post_see, :ca_id, :_active,
                                                    :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $row) {
                                                    // Memberikan jeda 1 detik
                                                    sleep(1);
                                                    $datetime = $row['date'];
                                                    $datetimeobj = new DateTime($datetime);
                                                    $dateconv = $datetimeobj -> format('Y-m-d');


                                                    $stmtBaru->execute([
                                                    ':post_id' => $row['id'],
                                                    ':post_judul' => $row['title'], // Adjust as per your data source
                                                    ':post_desk' => $row['description'], // Adjust as per your data source
                                                    ':post_publish' => $dateconv, // Adjust as per your data source
                                                    ':post_datex' => NULL, // Adjust as per your data source
                                                    ':post_img' => $row['img'], // Adjust as per your data source
                                                    ':post_see' => $row['total'], // Adjust as per your data source
                                                    ':ca_id' => str_pad($row['category'], 3, '0', STR_PAD_LEFT), // Adjust as per your data source
                                                    ':_active' => $row['status'],
                                                    ':_cre' => str_repeat('1', 18),
                                                    ':_cre_date' => date('Y-m-d'),
                                                    ':_chg' => str_repeat('1', 18),
                                                    ':_chg_date' => date('Y-m-d')
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for Table pub_post successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }

                                                    function PPID_Post($oldDb, $newDb) {
                                                        // Mengambil data dari tabel download
                                                        sleep(1);
                                                    
                                                        $categories = array(
                                                            array('Profil PPID Pelaksana', '<p class="MsoNormal" style="margin-left: 42.55pt; text-align: justify; line-height: 13.8pt; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="SV" style="font-size:12.0pt;font-family:&quot;Bookman Old Style&quot;,serif;mso-fareast-font-family:
                                                                &quot;Times New Roman&quot;;mso-bidi-font-family:&quot;Segoe UI&quot;;color:#212529;mso-ansi-language:
                                                                SV;mso-fareast-language:EN-ID">Profil<o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 42.55pt; text-align: justify; line-height: 13.8pt; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="SV" style="font-size:12.0pt;font-family:&quot;Bookman Old Style&quot;,serif;mso-fareast-font-family:
                                                                &quot;Times New Roman&quot;;mso-bidi-font-family:&quot;Segoe UI&quot;;color:#212529;mso-ansi-language:
                                                                SV;mso-fareast-language:EN-ID">Keterbukaan informasi merupakan ciri penting
                                                                negara demokratis yang menjunjung tinggi kedaulatan rakyat.&nbsp;Hal ini
                                                                tertuang dalam Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi
                                                                Publik dan Peraturan Pemerintah Nomor 61 Tahun 2010 tentang Pelaksanaan
                                                                Undang-undang Nomor 14 Tahun 2008.&nbsp;</span><span lang="IN" style="font-size:
                                                                12.0pt;font-family:&quot;Times New Roman&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
                                                                mso-fareast-language:IN"><o:p></o:p></span></p><p>
                                                                
                                                                
                                                                </p><p class="MsoNormal" style="margin-bottom: 0in; margin-left: 42.55pt; text-align: justify; line-height: 13.8pt; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="SV" style="font-size:12.0pt;font-family:&quot;Bookman Old Style&quot;,serif;mso-fareast-font-family:
                                                                &quot;Times New Roman&quot;;mso-bidi-font-family:&quot;Segoe UI&quot;;color:#212529;mso-ansi-language:
                                                                SV;mso-fareast-language:EN-ID">Undang-Undang dan Peraturan Pemerintah tersebut
                                                                memberikan jaminan atas hak masyarakat untuk mendapatkan informasi dalam rangka
                                                                meningkatkan pengetahuan dan peran aktif masyarakat dalam pengambilan kebijakan
                                                                publik dan pengelolaan badan publik yang baik. Keduanya mengamanatkan badan
                                                                publik untuk melaksanakan pengelolaan dokumentasi dan pelayanan informasi dalam
                                                                mewujudkan keterbukaan informasi publik.</span><span lang="IN" style="font-size:
                                                                12.0pt;font-family:&quot;Times New Roman&quot;,serif;mso-fareast-font-family:&quot;Times New Roman&quot;;
                                                                mso-fareast-language:IN"><o:p></o:p></span></p>', '','','','006'),
                                                            array('Visi Misi PPID Pelaksana', '<p style="text-align: justify; ">Visi
                                                            </p><p style="text-align: justify; ">Menjadi penyelenggara layanan informasi publik yang transparan, responsif, dan tidak diskriminatif di bidang obat dan makanan.
                                                            </p><p style="text-align: justify; ">
                                                            Misi</p><ul><li style="text-align: justify; ">Meningkatkan pengelolaan dan pelayanan informasi yang andal berbasis teknologi informasi;
                                                            </li><li style="text-align: justify; ">Meningkatkan partisipasi masyarakat melalui penyediaan media layanan informasi yang mudah diakses; dan</li><li style="text-align: justify; ">Meningkatkan kompetensi petugas layanan informasi sehingga mampu memberikan layanan informasi yang berkualitas.
                                                            </li></ul>', '','','','006'),
                                                            array('Tugas dan Fungsi PPID Pelaksana', '<p></p><h4 style="text-align: justify; "><br></h4><p></p><ol><li style="text-align: justify;">&nbsp;<span lang="FI" style="text-indent: -0.25in; line-height: 150%;">Membantu PPID Dinas ............. melaksanakan tanggung jawab, tugas dab kewenangannya</span></li><li style="text-align: justify;"><span lang="FI" style="text-indent: -0.25in; line-height: 150%;">Melaksanakan kebijakanan
    teknis layanan Informasi Publik yang telah ditetapkan PPID Dinas.................</span></li><li style="text-align: justify;"><span lang="FI" style="text-indent: -0.25in; line-height: 150%;">Mengonsolidasikan proses
    penyimpanan, pendokumentasian, penyediaan dan pelayanan Informasi Publik</span></li><li style="text-align: justify;"><span lang="FI" style="text-indent: -0.25in; line-height: 150%;">Mengumpulkan dokumen&nbsp;
    Informasi Publik dari Petugas Pelayanan Informasi di unit kerjanya&nbsp;</span></li><li style="text-align: justify;"><span lang="FI" style="text-indent: -0.25in; line-height: 150%;">Membantu PPID Dinas .......
    dalam membuat, memverifikasi, mengelola, memelihara, dan memutakhirkan Daftar
    Informasi Publik dan Klasifikasi Informasi yang Dikecualikan&nbsp;</span></li><li style="text-align: justify;">Membantu membuat, mengelola, memelihara, dan
    memutakhirkan Daftar Informasi Publik </li><li style="text-align: justify;">Menjamin ketersediaan dan akselerasi layanan
    Informasi Publik agar mudah diakses oleh publik </li><li style="text-align: justify;">Mengusulkan pengujian konsekuensi kepada PPID <span style="text-indent: -0.25in; line-height: 150%;">Dinas………</span><span lang="IN" style="text-indent: -0.25in; line-height: 150%;"> apabila terdapat permohonan informasi publik
    dan/atau terdapat informasi dikecualikan yang telah habis jangka waktu
    pengecualiannya&nbsp;</span></li><li style="text-align: justify;"><span lang="IN" style="text-indent: -0.25in; line-height: 150%;">Memberikan pelayanan Informasi Publik yang cepat,
    tepat, dan sederhana&nbsp;</span></li><li style="text-align: justify;">Menyampaikan laporan layanan
    Informasi Publik di lingkungan unit kerjanya kepada PPID Utama </li><li style="text-align: justify;">Dinas Komunikasi dan
    Informatika Kabupaten Sidoarjo melalui Sistem Informasi PPID</li><li style="text-align: justify;"><span lang="FI" style="text-indent: -0.25in; line-height: 150%;">Melakukan edukasi dan
    sosialisasi keterbukaan Informasi Publik</span></li></ol>', '','','','006'),
                                                            array('Struktur Organisasi PPID Pelaksana', 'Teks/jpg/pdf', '','','','006'),
                                                            array('SK PPID ', 'Teks/jpg/pdf', '','','','006'),
                                                            array('Maklumat Pelayanan ', 'Teks/jpg/pdf', '','','','006'),
                                                            array('Kebijakan dan Regulasi', 'Teks/jpg/pdf', '','','','006'),
                                                            array('Layanan', '<p style="text-align: justify;">Layanan</p><ul><li style="text-align: justify;">Permohonan Informasi Publik</li><li style="text-align: justify;">Keberatan Informasi Publik&nbsp;</li></ul><p style="text-align: justify;">Informasi Publik</p><ul><li style="text-align: justify;">Daftar Informasi Publik</li><li style="text-align: justify;">Daftar Informasi Publik 2023</li><li style="text-align: justify;">Daftar Informasi Publik 2024</li><li style="text-align: justify;">Informasi Publik Serta Merta</li><li style="text-align: justify;">Informasi Publik Setiap Saat</li><li style="text-align: justify;">Informasi Yang Dikecualikan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li></ul>', '','','','006'),
                                                            array('Informasi Publik', '<p style="text-align: justify;">Daftar Informasi Publik</p><p style="text-align: justify;">Informasi Berkala</p><p class="MsoNormal" style="text-align: justify; margin-bottom: 7.5pt; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="IN" style="font-size: 10pt; font-family: &quot;Open Sans&quot;;">Informasi berkala adalah informasi yang wajib diperbaharui kemudian
                                                            disediakan dan diumumkan kepada publik secara rutin atau berkala
                                                            sekurang-kurangnya setiap 6 bulan sekali.<o:p></o:p></span></p><p style="text-align: justify;">Informasi Serta Merta</p><p class="MsoNormal" style="text-align: justify; margin-bottom: 7.5pt; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="IN" style="font-size: 10pt; font-family: &quot;Open Sans&quot;;">Informasi Serta Merta adalah informasi yang berkaitan dengan hajat hidup
                                                            orang banyak dan ketertiban umum dan wajib diumumkan secara serta merta tanpa
                                                            penundaan.<o:p></o:p></span></p><p style="text-align: justify;">Informasi Setiap Saat</p><p class="MsoNormal" style="text-align: justify; margin-bottom: 7.5pt; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="IN" style="font-size: 10pt; font-family: &quot;Open Sans&quot;;">Informasi Tersedia Setiap Saat adalah informasi yang harus disediakan oleh
                                                            Badan Publik dan siap tersedia untuk bisa langsung diberikan kepada Pemohon
                                                            Informasi Publik ketika terdapat permohonan terhadap Informasi Publik tersebut<o:p></o:p></span></p><p style="text-align: justify;">Informasi Yang Dikecualikan</p><p class="MsoNormal" style="text-align: justify; margin-bottom: 7.5pt; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span lang="IN" style="font-size: 10pt; font-family: &quot;Open Sans&quot;;">Informasi </span><span style="font-size: 10pt; font-family: &quot;Open Sans&quot;;">Yang Dikecualikan</span><span lang="IN" style="font-size: 10pt; font-family: &quot;Open Sans&quot;;"> adalah informasi yang tidak dapat diakses
                                                            oleh pemohon informasi publik sebagaimana dimaksud dalam Undang – Undang Nomor
                                                            14 Tahun 2008 tentang Keterbukaan Informasi Publik. Informasi yang dikecualikan
                                                            </span><span style="font-size: 10pt; font-family: &quot;Open Sans&quot;;">masuk</span><span lang="IN" style="font-size: 10pt; font-family: &quot;Open Sans&quot;;"> dalam Daftar Informai Yang Dikecualikan.&nbsp;<o:p></o:p></span></p><p style="text-align: justify;"><br></p>', '','','','006'),
                                                            array('Dokumen Perencanaan dan Pelaporan', 'Dokumen Perencanaan dan Pelaporan', '','','','004'),
                                                            array('Dokumen Standar Pelayanan', 'Dokumen Standar Pelayanan', '','','','004'),
                                                        );
                                                    
                                                        $stmtBaru = $newDb->prepare("INSERT INTO pub_post (post_id, post_judul, post_desk, post_publish, post_datex,
                                                                                        post_img, post_see, ca_id, _active, _cre, _cre_date, _chg, _chg_date)
                                                                                        VALUES (:post_id, :post_judul, :post_desk, :post_publish, :post_datex, :post_img, :post_see, :ca_id, :_active,
                                                                                        :_cre, :_cre_date, :_chg, :_chg_date)");
                                                    
                                                        $newDb->beginTransaction();
                                                    
                                                        try {
                                                            foreach ($categories as $row) {
                                                                $convert = strtotime(date('Y-m-d H:i:s')) + 1;
                                                    
              
                                                                $checkStmt = $newDb->prepare("SELECT COUNT(*) FROM pub_post WHERE post_id = :post_id");
                                                                $checkStmt->execute([':post_id' => $convert]);
                                                                $count = $checkStmt->fetchColumn();
                                                    
        
                                                                while ($count > 0) {
                                                                    $convert++;
                                                                    $checkStmt->execute([':post_id' => $convert]);
                                                                    $count = $checkStmt->fetchColumn();
                                                                }
                                                    
              
                                                                $stmtBaru->execute([
                                                                    ':post_id' => $convert,
                                                                    ':post_judul' => $row[0], 
                                                                    ':post_desk' => $row[1], 
                                                                    ':post_publish' => date('Y-m-d'), 
                                                                    ':post_datex' => NULL, 
                                                                    ':post_img' => $row[3], 
                                                                    ':post_see' => $row[4], 
                                                                    ':ca_id' => $row[5], 
                                                                    ':_active' => 1,
                                                                    ':_cre' => str_repeat('1', 18),
                                                                    ':_cre_date' => date('Y-m-d'),
                                                                    ':_chg' => str_repeat('1', 18),
                                                                    ':_chg_date' => date('Y-m-d')
                                                                ]);
                                                            }
                                                    
                                                            $newDb->commit();
                                                            echo "<br>Migration for PPID_POST successful.<br>";
                                                        } catch (PDOException $e) {
                                                            $newDb->rollBack();
                                                            echo "Migration failed: " . $e->getMessage() . "<br>";
                                                        }
                                                    }

                                                    

                                                    function pubCounterPost($oldDb, $newDb) {
                                                    // Mengambil data dari tabel event
                                                    $stm2 = $oldDb->prepare("SELECT tb_events.*, tb_counter.idnews, tb_counter.total FROM `tb_counter`
                                                    INNER JOIN tb_events
                                                    ON tb_events.id = tb_counter.idnews");
                                                    $stm2->execute();
                                                    $data = $stm2->fetchAll(PDO::FETCH_ASSOC);


                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_post (post_id, post_judul, post_desk, post_publish, post_datex,
                                                    post_img, post_see, ca_id, _active, _cre, _cre_date, _chg, _chg_date)
                                                    VALUES (:post_id, :post_judul, :post_desk, :post_publish, :post_datex, :post_img, :post_see, :ca_id, :_active,
                                                    :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $row) {
                                                    // Memberikan jeda 1 detik
                                                    sleep(1);
                                                    // conv string datetime
                                                    $datetimestr = $row['date'];
                                                    if ($datetimestr === null) {
                                                    $formatdatestr = '0000-00-00';
                                                    } else {
                                                    $dateObj = DateTime::createFromFormat('l d F Y - H:i', $datetimestr);
                                                    if ($dateObj) {
                                                    $datestr = $dateObj->format('d F Y');
                                                    $converttiemstr = strtotime($datestr);
                                                    $formatdatestr = date('Y-m-d', $converttiemstr);
                                                    } else {
                                                    // Jika format tanggal tidak sesuai, atur tanggal ke '0000-00-00'
                                                    $formatdatestr = '0000-00-00';
                                                    }
                                                    }
                                                    //conv datetime
                                                    $datetime = $row['datesubmit'];
                                                    $datetimeobj = new DateTime($datetime);
                                                    $dateconv = $datetimeobj -> format('Y-m-d');


                                                    $stmtBaru->execute([
                                                    ':post_id' => $row['id'],
                                                    ':post_judul' => $row['title'], // Adjust as per your data source
                                                    ':post_desk' => $row['description'], // Adjust as per your data source
                                                    ':post_publish' => $formatdatestr, // Adjust as per your data source
                                                    ':post_datex' => $dateconv, // Adjust as per your data source
                                                    ':post_img' => $row['img'], // Adjust as per your data source
                                                    ':post_see' => $row['total'], // Adjust as per your data source
                                                    ':ca_id' => '002' , // Adjust as per your data source
                                                    ':_active' =>1,
                                                    ':_cre' => str_repeat('1', 18),
                                                    ':_cre_date' => date('Y-m-d'),
                                                    ':_chg' => str_repeat('1', 18),
                                                    ':_chg_date' => date('Y-m-d')
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for Table pub_post successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }



                                                    function pubCounterPengumuman($oldDb, $newDb) {

                                                    // Mengambil data dari tabel download
                                                    $stm3 = $oldDb->prepare("SELECT * FROM `tb_pengumuman`");
                                                    $stm3->execute();
                                                    $data = $stm3->fetchAll(PDO::FETCH_ASSOC);


                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_post (post_id, post_judul, post_desk, post_publish, post_datex,
                                                    post_img, post_see, ca_id, _active, _cre, _cre_date, _chg, _chg_date)
                                                    VALUES (:post_id, :post_judul, :post_desk, :post_publish, :post_datex, :post_img, :post_see, :ca_id, :_active,
                                                    :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $row) {
                                                    // Memberikan jeda 1 detik
                                                    sleep(1);


                                                    $stmtBaru->execute([
                                                    ':post_id' => $row['id'],
                                                    ':post_judul' => $row['title'], // Adjust as per your data source
                                                    ':post_desk' => $row['description'], // Adjust as per your data source
                                                    ':post_publish' => $row['date'], // Adjust as per your data source
                                                    ':post_datex' => $row['datex'], // Adjust as per your data source
                                                    ':post_img' => '' , // Adjust as per your data source
                                                    ':post_see' => '', // Adjust as per your data source
                                                    ':ca_id' => '003', // Adjust as per your data source
                                                    ':_active' =>$row['status'],
                                                    ':_cre' => str_repeat('1', 18),
                                                    ':_cre_date' => date('Y-m-d'),
                                                    ':_chg' => str_repeat('1', 18),
                                                    ':_chg_date' => date('Y-m-d')
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for pub_post successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }


                                                    function pubDownloads($oldDb, $newDb) {

                                                    // Mengambil data dari tabel download
                                                    $stm3 = $oldDb->prepare("SELECT * FROM `tb_downloads`");
                                                    $stm3->execute();
                                                    $data = $stm3->fetchAll(PDO::FETCH_ASSOC);


                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_post (post_id, post_judul, post_desk, post_publish, post_datex,
                                                    post_img, post_see, ca_id, _active, _cre, _cre_date, _chg, _chg_date)
                                                    VALUES (:post_id, :post_judul, :post_desk, :post_publish, :post_datex, :post_img, :post_see, :ca_id, :_active,
                                                    :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $row) {
                                                    $timestamp = $row['id'];
                                                    $date_pub = date('Y-m-d', $timestamp);
                                                    // Memberikan jeda 1 detik
                                                    sleep(1);



                                                    $stmtBaru->execute([
                                                    ':post_id' => $row['id'],
                                                    ':post_judul' => $row['title'], // Adjust as per your data source
                                                    ':post_desk' => $row['description'], // Adjust as per your data source
                                                    ':post_publish' => $date_pub, // Adjust as per your data source
                                                    ':post_datex' => NULL, // Adjust as per your data source
                                                    ':post_img' => '' , // Adjust as per your data source
                                                    ':post_see' => '', // Adjust as per your data source
                                                    ':ca_id' => '004', // Adjust as per your data source
                                                    ':_active' =>1,
                                                    ':_cre' => str_repeat('1', 18),
                                                    ':_cre_date' => date('Y-m-d'),
                                                    ':_chg' => str_repeat('1', 18),
                                                    ':_chg_date' => date('Y-m-d')
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for pub_post successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }

                                                    function pubServ($oldDb, $newDb) {

                                                    // Mengambil data dari tabel download
                                                    $stm3 = $oldDb->prepare("SELECT * FROM `tb_services`");
                                                    $stm3->execute();
                                                    $data = $stm3->fetchAll(PDO::FETCH_ASSOC);


                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_post (post_id, post_judul, post_desk, post_publish, post_datex,
                                                    post_img, post_see, ca_id, _active, _cre, _cre_date, _chg, _chg_date)
                                                    VALUES (:post_id, :post_judul, :post_desk, :post_publish, :post_datex, :post_img, :post_see, :ca_id, :_active,
                                                    :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $row) {
                                                    $timestamp = $row['id'];
                                                    $date_pub = date('Y-m-d', $timestamp);
                                                    // Memberikan jeda 1 detik
                                                    sleep(1);


                                                    $stmtBaru->execute([
                                                    ':post_id' => $row['id'],
                                                    ':post_judul' => $row['name'], // Adjust as per your data source
                                                    ':post_desk' => $row['description'], // Adjust as per your data source
                                                    ':post_publish' => $date_pub, // Adjust as per your data source
                                                    ':post_datex' => NULL, // Adjust as per your data source
                                                    ':post_img' => '' , // Adjust as per your data source
                                                    ':post_see' => '', // Adjust as per your data source
                                                    ':ca_id' => '005', // Adjust as per your data source
                                                    ':_active' =>$row['status'],
                                                    ':_cre' => str_repeat('1', 18),
                                                    ':_cre_date' => date('Y-m-d'),
                                                    ':_chg' => str_repeat('1', 18),
                                                    ':_chg_date' => date('Y-m-d')
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for pub_post successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }

                                                    function visit($oldDb, $newDb){
                                                    $createTableQuery = "CREATE TABLE IF NOT EXISTS `visitors` (
                                                    `vs_id` varchar(10) NOT NULL,
                                                    `vs_ip` varchar(45) NOT NULL,
                                                    `vs_os` varchar(50) NOT NULL,
                                                    `vs_brow` varchar(50) NOT NULL,
                                                    `vs_date` datetime NOT NULL,
                                                    PRIMARY KEY (`vs_id`)
                                                    )";


                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'visitor' created successfully. <br>";
                                                    } else {
                                                    echo "Error creating table 'visitor'.<br>";
                                                    }
                                                    }


                                                    function set_form($oldDb, $newDb){

                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `set_form` ;
                                                    CREATE TABLE IF NOT EXISTS `set_form` (
                                                    `fm_id` char(3) NOT NULL,
                                                    `fm_name` varchar(50) NOT NULL,
                                                    `fm_file` varchar(50) NOT NULL,
                                                    `_active` char(1) NOT NULL,
                                                    `_cre` char(18) NOT NULL,
                                                    `_cre_date` date NOT NULL,
                                                    `_chg` char(18) NOT NULL,
                                                    `_chg_date` date NOT NULL
                                                    )";


                                                    $newDb->exec($createTableQuery);

                                                    // Insert data kategori
                                                    $categories = array(
                                                    array('001', 'Form Berita', '_page-post', '1', '555', '2023-03-20', '555', '2023-05-02'),
                                                    array('002', 'Form Agenda', '_page-agnd', '1', '555', '2023-03-20', '555', '2023-05-08'),
                                                    array('003', 'Form Link', '_page-link', '0', '555', '2023-03-20', '555', '2024-04-04'),
                                                    array('004', 'Form Pengumuman', '_page-publik', '1', '555', '2023-05-09', '555', '2023-05-16')
                                                    );

                                                    $stmtBaru = $newDb->prepare("INSERT INTO `set_form` (`fm_id`, `fm_name`, `fm_file`, `_active`, `_cre`, `_cre_date`,
                                                    `_chg`, `_chg_date`)
                                                    VALUES (:fm_id, :fm_name, :fm_file, :_active, :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($categories as $category) {
                                                    $stmtBaru->execute([
                                                    ':fm_id' => $category[0],
                                                    ':fm_name' => $category[1],
                                                    ':fm_file' => $category[2],
                                                    ':_active' => $category[3],
                                                    ':_cre' => $category[4],
                                                    ':_cre_date' => $category[5],
                                                    ':_chg' => $category[6],
                                                    ':_chg_date' => $category[7]
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for set_form successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }

                                                    function pub_Post($oldDb, $newDb){
                                                        $createTableQuery = "
                                                    DROP TABLE IF EXISTS `pub_post` ;
                                                    CREATE TABLE IF NOT EXISTS `pub_post` (
                                                    `post_id` varchar(10) NOT NULL,
                                                    `post_judul` tinytext,
                                                    `post_desk` longtext,
                                                    `post_publish` date DEFAULT NULL,
                                                    `post_datex` date DEFAULT NULL,
                                                    `post_img` varchar(30) DEFAULT NULL,
                                                    `post_see` char(5) DEFAULT NULL,
                                                    `ca_id` char(3) DEFAULT NULL,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`post_id`)
                                                    )";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'pub_post' created successfully.";
                                                    } else {
                                                    echo "Error creating table 'pub_post'.<br>";
                                                    }
                                                    }
                                                    function userd($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }

                                                    function profil($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }
                                                    function empploye($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }
                                                    function social ($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }
                                                    function vidos ($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }
                                                    function dinas($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }
                                                    function news($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }
                                                    function post($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }

                                                    function ppdi($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }

                                                    function pengumuman($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }

                                                    function download($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }

                                                    function serv($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }

                                                    function visit($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    visit($oldDb, $newDb);
                                                    // set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }

                                                    function form($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan

                                                    // setUser($oldDb, $newDb);
                                                    // pubProfile($oldDb, $newDb);
                                                    // pubEmployes($oldDb, $newDb);
                                                    // pubSocial($oldDb, $newDb);
                                                    // pubVidios($oldDb, $newDb);
                                                    // setDinasPub($oldDb, $newDb);
                                                    // pubCounterNews($oldDb, $newDb);
                                                    // pubCounterPost($oldDb, $newDb);
                                                    // PPID_Post($oldDb, $newDb);
                                                    // pubCounterPengumuman($oldDb, $newDb);
                                                    // pubDownloads($oldDb, $newDb);
                                                    // pubServ($oldDb, $newDb);
                                                    // visit($oldDb, $newDb);
                                                    set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }
                                                    

                                                    function migrateNewTable($oldDb, $newDb) {
                                                        if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                            $oldDbDetails = $_SESSION['oldDbDetails'];
                                                            $newDbDetails = $_SESSION['newDbDetails'];
                                                            // Buat koneksi ke database lama
                                                            $oldDb = new PDO("mysql:host=localhost;dbname={$oldDbDetails['name']}",
                                                                $oldDbDetails['user'], $oldDbDetails['pass']);
                                                            $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    
                                                            // Buat koneksi ke database baru
                                                            $newDb = new PDO("mysql:host=localhost;dbname={$newDbDetails['name']}",
                                                                $newDbDetails['user'], $newDbDetails['pass']);
                                                            $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                                                             // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan
                                                    setMenu($oldDb, $newDb);
                                                    setCategory($oldDb, $newDb);
                                                    setJabdept($oldDb, $newDb);
                                                    setPage($oldDb, $newDb);
                                                    setRoles($oldDb, $newDb);
                                                    setRules($oldDb, $newDb);
                                                    pub_Post($oldDb, $newDb);
                                                    PPID_Post($oldDb, $newDb);
                                                    set_form($oldDb, $newDb);
                                                        } else {
                                                            echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                        }
                                                    }



                                                    // Jika tombol Migrate New Tables ditekan
                                                    if (isset($_POST['setuser'])) {
                                                        userd($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['profile'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['employess'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['social'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['vidios'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['dinas'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['counternews'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['counterpost'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['ppidpost'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['counterpengumumna'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['download'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['serv'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['visit'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                    if (isset($_POST['setform'])) {
                                                        migrateAllTables($oldDb, $newDb);
                                                    }
                                                   
                                                    // Jika tombol Migrate All Tables ditekan
                                                    if (isset($_POST['migrateTable'])) {
                                                        migrateNewTable($oldDb, $newDb);
                                                    }
                                                  
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <button type="button" class="action-button previous previous_button mt-3">Back</button>
                            <button type="button" class="next action-button mt-3">Continue</button>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div id="form_result">
                                        <h4 class="text-center pb-4">WEBSITE SUDAH SIAP DIGUNAKAN</h4>
                                    </div>
                                    <div id="contactpage"
                                        class="contact-form wow slideInRight text-lg-left text-center">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-6">
                                                <div class=" form-group mb-0 text-center">
                                                    <label>TEKAN BUTTON UNTUK KE WEBSITE</label><br>
                                                    <a href="http://" class="btn btn-primary">LINK KE WEBSITE</a>
                                                    <input type="text" hidden class="form-control"
                                                        placeholder="Upload Bakesbangpol" name="bb" id="bb" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="action-button previous previous_button mt-3">Back</button>
                            <!-- <button type="submit" value="Submit" class="next action-button mt-3">Save</button> -->
                        </fieldset>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- form section -->


    <!-- The Modal -->
    <!-- Modal -->
    <div class="modal" id="connectionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Database Connection Form</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="oldDbName">Old Database Name:</label>
                            <input type="text" id="oldDbName" name="oldDbName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="oldDbUser">Old Database Username:</label>
                            <input type="text" id="oldDbUser" name="oldDbUser" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="oldDbPass">Old Database Password:</label>
                            <input type="password" id="oldDbPass" name="oldDbPass" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="newDbName">New Database Name:</label>
                            <input type="text" id="newDbName" name="newDbName" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Connect</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <!-- Bootstrap JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>
    <!-- jQuery Easing JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
    <!-- Telephone Input JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/intlTelInput.js'></script>
    <!-- Popper JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
    <!-- jQuery Nice Select JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js'></script>
    <!-- jQuery -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Initialization -->
    <script src="js/script.js"></script>
    <!-- Optional JavaScript; choose one of the two! -->

    <script>
    // $(document).ready(function() {
    //     $('#connectionModal').modal('show');
    // });
    // var select_box_element = document.querySelector('#select_box');

    dselect(select_box_element, {
        search: true
    });
    </script>


</body>

</html>