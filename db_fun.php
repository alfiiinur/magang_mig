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
            array('006', 'Prestasi', '001/1536802599', '', '001', '1', '1533562415', '2024-03-30', '1533562415', '2024-03-30')
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
            function migrateAllTables($oldDb, $newDb) {
            // Panggil fungsi migrasi untuk setiap tabel yang ingin Anda pindahkan
            setMenu($oldDb, $newDb);
            }

            // Jika tombol Migrate All Tables ditekan
                if (isset($_POST['migrateAll'])) {
                    try {
                        // Buat koneksi ke database lama
                        $oldDb = new PDO("mysql:host=$oldDbHost;dbname=$oldDbName", $oldDbUser, $oldDbPass);
                        $oldDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Buat koneksi ke database baru
                        $newDb = new PDO("mysql:host=$newDbHost;dbname=$newDbName", $newDbUser, $newDbPass);
                        $newDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Panggil fungsi untuk mengeksekusi semua migrasi
                        migrateAllTables($oldDb, $newDb);

                        // Tampilkan pesan sukses jika migrasi berhasil
                        echo '<div class="alert alert-success text-center" role="alert">Migration of all tables successful.</div>';

                        // Tutup koneksi PDO
                        $oldDb = null;
                        $newDb = null;
                    } catch(PDOException $e) {
                        // Tangani kesalahan jika koneksi atau operasi query gagal
                        echo '<div class="alert alert-danger" role="alert">Migration failed: ' . $e->getMessage() . '</div>';
                    }
                }
                                                    ?>