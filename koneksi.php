<?php
    // inisialisasi variabel koneksi dataseb
    $host = "127.0.0.1";
    $user = "root";
    $pass = "";
    $db = "starter_v1";

    // buat koneksi ke database
    $conn = mysqli_connect($host, $user, $pass, $db);

    // cek koneksi database
    if (!$conn) {
        die("koneksi gagal" . mysqli_connect_error());
    }

    // fungsi untuk menghandle query SELECT
    function query ($sql) {
        global $conn;
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query Error: " . mysqli_error($conn));
        }
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // fungsi untuk menangani query INSERT, UPDATE, DELETE
    function execute($sql) {
        global $conn;
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query Error" . mysqli_error($conn));
        }
        return $result;
    }
?>