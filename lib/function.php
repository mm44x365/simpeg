<?php
function get_webSettings()
{
	global $connect;
	$CallData = mysqli_query($connect, "SELECT * FROM web_info WHERE id = '1'");
	$ThisData = mysqli_fetch_assoc($CallData);
	return $ThisData;
}

function base_url()
{
	$base_url = "http://127.0.0.1/subskuy/";
	return $base_url;
}

function notification()
{
	$_SESSION['notification'] = array('alert' => 'null', 'title' => 'null', 'message' => 'null');
	return $_SESSION['notification'];
}

function filter($data)
{
	$filter = stripslashes(strip_tags(htmlspecialchars(htmlentities($data, ENT_QUOTES))));
	return $filter;
}

function set_flashdata($key, $value)
{
	$_SESSION[$key] = $value;
}

function set_flashdata_array($key, $array)
{
	$_SESSION[$key] = $array;
}

function check_flashdata($key)
{
	if (isset($_SESSION[$key])) {
		$data = $_SESSION[$key];
		return $data;
	}
}

function get_flashdata($key)
{
	if (isset($_SESSION[$key])) {
		$data = $_SESSION[$key];
		if ($_SESSION[$key] != '') {
			$_SESSION[$key] = '';
		}
		return $data;
	}
}

function visualArr($array)
{
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

function uploadFile($file, $path)
{
	$targetPath = $path . $file['name'];
	if (move_uploaded_file($file['tmp_name'], $targetPath)) {
		return true;
	}

	echo $file['error'];
}

function getExtension($file)
{
	$exploded = explode('.', $file['name']);
	$extension = end($exploded);
	return $extension;
}

function check_session($role)
{
	global $config, $connect;

	if (isset($_SESSION['user'])) {
		$session_username = $_SESSION['user']['username'];
		$session_role = $_SESSION['user']['role'];
		$ip_address = $_SERVER['REMOTE_ADDR'];

		$checkAdmin = $connect->query("SELECT * FROM user WHERE username = '$session_username' AND role = '1'  AND last_login_ip = '$ip_address'");
		$checkAdminRows = mysqli_num_rows($checkAdmin);
		$checkMember = $connect->query("SELECT * FROM user WHERE username = '$session_username' AND last_login_ip = '$ip_address'");
		$checkMemberRows = mysqli_num_rows($checkMember);
		if ($role == 'admin') {
			if ($checkAdminRows == 0) {
				unset($_SESSION['user']);
				require '../redirect.php';
				die();
			}
		} elseif ($role == 'member') {
			if ($checkMemberRows == 0) {
				$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Alamat Ip berubah, silakan login ulang.');
				unset($_SESSION['user']);
				exit(header("Location: " . base_url() . "login/"));
			}
		}
	} else {
		$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Mohon login terlebih dahulu.');
		exit(header("Location: " . base_url() . "login/"));
	}
}

function delete_files($target)
{
	if (is_dir($target)) {
		$files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

		foreach ($files as $file) {
			delete_files($file);
		}

		rmdir($target);
	} elseif (is_file($target)) {
		unlink($target);
	}
}

function tanggal_indo($tanggal, $cetak_hari = false)
{
	$hari = array(
		1 =>    'Senin',
		'Selasa',
		'Rabu',
		'Kamis',
		'Jumat',
		'Sabtu',
		'Minggu'
	);

	$bulan = array(
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$split 	  = explode('-', $tanggal);
	$tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}

function dashboardAdmin($data, $seesUser){
	global $config, $connect;

	switch ($data) {
		case 'totalUser':
			$checkData = $connect->query("SELECT * FROM user");
			$return = mysqli_num_rows($checkData);
			break;

		case 'totalSubtitle':
			$checkData = $connect->query("SELECT * FROM subtitle");
			$return = mysqli_num_rows($checkData);
			break;

		case 'totalSubtitleUser':
		$checkData = $connect->query("SELECT * FROM subtitle WHERE author = '$seesUser'");
		$return = mysqli_num_rows($checkData);
		break;
		
		default:
			# code...
			break;
	}

	 return $return;
}