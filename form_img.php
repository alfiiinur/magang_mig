<?php 


function move_image($folder_asal, $folder_tujuan) {
            
    $files = glob($folder_asal . '/*.{jpg,jpeg,png,gif,pdf,docs}', GLOB_BRACE);
    
    foreach ($files as $file) {
        $nama_file = basename($file);
        $path_tujuan = $folder_tujuan.'/'.$nama_file;
    
        // Cek apakah file dengan nama yang sama sudah ada di folder tujuan
        if (!file_exists($path_tujuan)) {
            if (copy($file, $path_tujuan)) {
                echo "File ".$nama_file." berhasil dipindahkan dan dicopy.<br>";
            } else {
                echo "Gagal memindahkan file ".$nama_file.".<br>";
            }
        } else {
            echo "File ".$nama_file." sudah ada di folder tujuan, tidak perlu dicopy.<br>";
        }
    }
}

// $_dirPost	= 'images/post'; //POST
// $_dirEmp	= 'images/employees'; //EMP
// $_dirLHKPN	= 'images/lhkpn'; //LHKPN
// $_dirKategori = 'images/kategori'; //KATEGORY
// $_dirGalery = 'images/galery'; //GALERY & BANNER
// $_dirFiles = 'images/files';//FILES PENGUMUMAN
// $_dirProf = 'images/prof';//PROFIL
// $_dirLink	= 'images/socials'; //SOSMED


if (isset($_POST['move_image'])) {
    
    $folder_move = array(
        // // banners
        // array('images_asal/banners', 'images/banners'),
        // // end banner

        // // employess
        // array('images_asal/employees', 'images/employees'),
        // // end employess

        // // post
        // array('images_asal/events', 'images/post'),
        // array('images_asal/news', 'images/post'),
        // // end post

        // socials
        array('images_asal/inst', 'images/socials'),

        // end files
    );

    foreach ($folder_move as $folder_mv) {
        move_image($folder_mv[0], $folder_mv[1]);
    }
}