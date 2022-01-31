<?php
//Nama page/halaman
$page = "Masuk";

//Start session
session_start();

//Ambil template header auth
require 'layout/auth_header.php';

//Jika sudah ada user login terdeteksi arahkan ke halaman utama
if (isset($_SESSION['user'])) {
	header("Location: " . base_url() . "dashboard/");
} else {
	//Jika tombol login ditekan
	if (isset($_POST['login'])) {
		//Tangkap dan filter data post dari from login
		$username = $connect->real_escape_string(trim(filter($_POST['username'])));
		$password = $connect->real_escape_string(trim(filter($_POST['password'])));

		//Cek apakah username ada?
		$check_user = $connect->query("SELECT * FROM user WHERE username = '$username'");
		$check_user_rows = mysqli_num_rows($check_user);
		$data_user = mysqli_fetch_assoc($check_user);

		//Cocokan password, menggunkan password verifiy php
		$verif_pass = password_verify($password, $data_user['password']);

		//Jika from username, dan password kosong maka proses login gagal
		if (!$username || !$password) {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Mohon isi semua form.');
		}
		//Jika username admin ditemukan
		else if ($check_user_rows == 1) {
			//Jika hasil dari $verif_pass true/benar maka dibuat session admin, dan arahkan ke dashboard
			if ($verif_pass == true) {
				$ip_address = $_SERVER['REMOTE_ADDR'];
				if ($connect->query("UPDATE user SET last_login_ip = '$ip_address' WHERE username = '$username'") == true) {
					$_SESSION['user'] = $data_user;
					//check_session();
					exit(header("Location: " . base_url() . "dashboard/"));
				}
			} else {
				//Jika hasil dari $verif_pass false/salah maka proses login gagal
				$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Password salah.');
			}
		} else {
			//Jika username admin tidak ditemukan
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Akun tidak ditemukan.');
		}
	}
}
?>
<div class="login-box">
	<div class="login-logo">
		<p><?= $page ?></p>
	</div>
	<?php
	if (isset($_SESSION['notification']['alert'])) {
	?>
		<div class="alert alert-<?= $_SESSION['notification']['alert'] ?>">
			<h5><?= $_SESSION['notification']['title'] ?>!</h5>
			<?= $_SESSION['notification']['message'] ?>
		</div>
	<?php
		unset($_SESSION['notification']);
	}
	?>
	<!-- /.login-logo -->
	<div class="card">
		<div class="card-body login-card-body">
			<form method="POST">
				<div class="input-group mb-3">
					<input type="text" name="username" value="<?= empty($username) ? "" : $username; ?>" class="form-control" placeholder="Username">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fa fa-user"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" name="password" class="form-control" placeholder="Password">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-4">
						<button type="submit" name="login" class="btn btn-primary btn-block">Masuk</button>
					</div>
					<!-- /.col -->
				</div>
			</form>
			<div class="social-auth-links text-center mb-3">
				<p class="mb-0">
					Belum punya akun? <a href="<?= base_url() ?>register/">Klik Disini</a>
				</p>
			</div>
			<!-- /.social-auth-links -->
		</div>
		<!-- /.login-card-body -->
	</div>
</div>
<!-- /.login-box -->
<?php
require 'layout/auth_footer.php';
?>