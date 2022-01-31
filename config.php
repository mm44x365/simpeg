<?php
//************************************************
//* Mulai dibuat : 20/November/2020 12:30 PM
//* Dibuat Oleh : Pratama Ardy Prayoga
//* -------------Man Jadda Wa Jadda---------------
//************************************************

//Konfigurasi database
	$config['db'] = array(
		'host' => 'localhost',
		'name' => 'subskuy',
		'username' => 'root',
		'password' => ''
	);
	$connect = mysqli_connect($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['name']);
	if(!$connect) {
	die("Koneksi Gagal : ".mysqli_connect_error());
	}
	


//Setting timezone dan waktu
setlocale(LC_ALL, 'id-ID', 'id_ID');
date_default_timezone_set('Asia/Jakarta');

//Include fungsi
include("lib/function.php");

//Ambil informasi web dari fungsi
$web_info = get_webSettings();
?>







