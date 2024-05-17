<?php
// Pastikan semua variabel terkait formulir telah diinisialisasi dengan nilai default
session_start();

$oldDb = null; 
$newDb = null;

$oldDbHost = '';
$oldDbName = '';
$oldDbUser = '';
$oldDbPass = '';
$newDbHost = '';
$newDbName = '';
$newDbUser = '';
$newDbPass = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldDbHost = isset($_POST['oldDbHost']) ? $_POST['oldDbHost'] : '';
    $oldDbName = isset($_POST['oldDbName']) ? $_POST['oldDbName'] : '';
    $oldDbUser = isset($_POST['oldDbUser']) ? $_POST['oldDbUser'] : '';
    $oldDbPass = isset($_POST['oldDbPass']) ? $_POST['oldDbPass'] : '';
    $newDbHost = isset($_POST['newDbHost']) ? $_POST['newDbHost'] : '';
    $newDbName = isset($_POST['newDbName']) ? $_POST['newDbName'] : '';
    $newDbUser = isset($_POST['newDbUser']) ? $_POST['newDbUser'] : '';
    $newDbPass = isset($_POST['newDbPass']) ? $_POST['newDbPass'] : '';

    try {
        // Buat koneksi ke database lama
        $oldDb = new PDO("mysql:host=$oldDbHost;dbname=$oldDbName", $oldDbUser, $oldDbPass);
        $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Simpan detail koneksi dalam session untuk database lama
        $_SESSION['oldDbDetails'] = [
            'host' => $oldDbHost,
            'name' => $oldDbName,
            'user' => $oldDbUser,
            'pass' => $oldDbPass
        ];

        // Buat koneksi ke database baru
        $newDb = new PDO("mysql:host=$newDbHost", $newDbUser, $newDbPass);
        $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Simpan detail koneksi dalam session untuk database baru
        $_SESSION['newDbDetails'] = [
            'host' => $newDbHost,
            'user' => $newDbUser,
            'pass' => $newDbPass,
            'name' => $newDbName
        ];

        // Drop database baru jika sudah ada
        // $dropDbQuery = "DROP DATABASE IF EXISTS $newDbName";
        // $newDb->exec($dropDbQuery);

        // Buat database baru jika nama tidak kosong
        if (!empty($newDbName)) {
            // $createDbQuery = "CREATE DATABASE $newDbName";
            // $newDb->exec($createDbQuery);
            // Pilih database baru jika nama tidak kosong
            $newDb->exec("USE $newDbName");
        } else {
            echo '<div class="alert alert-danger" role="alert">New database name is required.</div>';
        }

        // Tutup koneksi
        echo '<div class="alert alert-primary text-center" role="alert">Connection success database : ' . $newDbName . '</div>';
    } catch(PDOException $e) {
        // Tangani kesalahan jika koneksi gagal
        echo '<div class="alert alert-danger" role="alert">Connection failed: ' . $e->getMessage() . '</div>';
    } finally {
        // Tutup koneksi
        $oldDb = null;
        $newDb = null;
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
            <!-- Button to trigger modal -->
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
                                
                                if (isset($_POST['move_image'])) {
                                    $folder_move = array(
                                        array('../images_asal/inst',  'images/post'),
                                        array('../images_asal/inst',  'images/mahal')
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
                                                        <input type="submit" class="btn btn-primary" name="migrateAll"
                                                            value="Migrate All Tables">
                                                    </form>
                                                    <?php
                                                    // Fungsi migrasi untuk tabel pertama
                                                    function setMenu($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = " DROP TABLE IF EXISTS `set_menu` ; CREATE TABLE IF NOT EXISTS `set_menu` (
                                                    `mn_id` char(3) NOT NULL,
                                                    `mn_txt` varchar(20) NOT NULL,
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
                                                    echo "Migration for set_menu successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                }
                                                function pubVidios($oldDb, $newDb) {
                                                    // Create Table
                                                    $createTableQuery = "
                                                    DROP TABLE IF EXISTS `pub_videos` ;
                                                    CREATE TABLE IF NOT EXISTS `pub_videos` (
                                                    `vd_id` varchar(10) NOT NULL,
                                                    `vd_url` tinytext,
                                                    `_active` char(1) DEFAULT NULL,
                                                    `_cre` char(18) DEFAULT NULL,
                                                    `_cre_date` date DEFAULT NULL,
                                                    `_chg` char(18) DEFAULT NULL,
                                                    `_chg_date` date DEFAULT NULL,
                                                    PRIMARY KEY (`vd_id`)
                                                    )
                                                    ";

                                                    $newDb->exec($createTableQuery);

                                                    if ($newDb->exec($createTableQuery) !== false) {
                                                    echo "Table 'pub_vidios' created successfully.";
                                                    } else {
                                                    echo "Error creating table 'pub_vidios'.<br>";
                                                    }

                                                    // Mengambil data dari tabel lama
                                                    $stmt = $oldDb->prepare("SELECT * FROM tb_video");
                                                    $stmt->execute();
                                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    $stmtBaru = $newDb->prepare("INSERT INTO pub_videos (vd_id, vd_url, _active, _cre, _cre_date, _chg, _chg_date)
                                                    VALUES (:vd_id, :vd_url, :_active, :_cre, :_cre_date, :_chg, :_chg_date)");

                                                    $newDb->beginTransaction();

                                                    try {
                                                    foreach ($data as $row) {
                                                    // Memberikan jeda 1 detik
                                                    sleep(1);

                                                    // $id = date('Y-m-d H:i:s');
                                                    // $convert = strtotime($id) + 1;


                                                    $stmtBaru->execute([
                                                    ':vd_id' => $row['id'],
                                                    ':vd_url' => $row['url'],
                                                    '_active' => $row['status'],
                                                    '_cre' => str_repeat('1', 18),
                                                    '_cre_date' => date('Y-m-d'),
                                                    '_chg' => str_repeat('1', 18),
                                                    '_chg_date' => date('Y-m-d')
                                                    ]);
                                                    }

                                                    $newDb->commit();
                                                    echo "Migration for pub_vidios successful.<br>";
                                                    } catch (PDOException $e) {
                                                    $newDb->rollBack();
                                                    echo "Migration failed: " . $e->getMessage() . "<br>";
                                                    }
                                                    }
                                                function migrateAllTables() {
                                                    // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan
                                                    if (isset($_SESSION['oldDbDetails']) && isset($_SESSION['newDbDetails'])) {
                                                        $oldDbDetails = $_SESSION['oldDbDetails'];
                                                        $newDbDetails = $_SESSION['newDbDetails'];
                                                        // Buat koneksi ke database lama
                                                        $oldDb = new PDO("mysql:host={$oldDbDetails['host']};dbname={$oldDbDetails['name']}",
                                                            $oldDbDetails['user'], $oldDbDetails['pass']);
                                                        $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                                                        // Buat koneksi ke database baru
                                                        $newDb = new PDO("mysql:host={$newDbDetails['host']};dbname={$newDbDetails['name']}",
                                                            $newDbDetails['user'], $newDbDetails['pass']);
                                                        $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                                                        setMenu($oldDb, $newDb);
                                                        pubVidios($oldDb, $newDb);
                                                    } else {
                                                        echo '<div class="alert alert-danger" role="alert">Database connections not found.</div>';
                                                    }
                                                }

                                                    // Jika tombol Migrate All Tables ditekan
                                                        if (isset($_POST['migrateAll'])) {
                                                            migrateAllTables($oldDb, $newDb);
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
                            <label for="oldDbHost">Old Database Host:</label>
                            <input type="text" id="oldDbHost" name="oldDbHost" class="form-control" value="localhost">
                        </div>
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
                            <label for="newDbHost">New Database Host:</label>
                            <input type="text" id="newDbHost" name="newDbHost" class="form-control" value="localhost">
                        </div>
                        <div class="form-group">
                            <label for="newDbName">New Database Name:</label>
                            <input type="text" id="newDbName" name="newDbName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="newDbUser">New Database Username:</label>
                            <input type="text" id="newDbUser" name="newDbUser" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="newDbPass">New Database Password:</label>
                            <input type="password" id="newDbPass" name="newDbPass" class="form-control">
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