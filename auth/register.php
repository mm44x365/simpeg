<?php
//Nama halaman
$page = "Register User";

//Start session
session_start();

//Ambil template header auth
require 'layout/auth_header.php';

//Set null notifikasi
// notification();

//Jika sudah ada user login terdeteksi arahkan ke halaman utama
if (isset($_SESSION['user'])) {
	header("Location: " . base_url() . "user/");
} else {
	//Jika tombol register di tekan
	if (isset($_POST['register'])) {
		//Tangkap data yang di post form register
		$username = $connect->real_escape_string(trim(filter($_POST['username'])));
		$password1 = $connect->real_escape_string(trim(filter($_POST['password1'])));
		$password2 = $connect->real_escape_string(trim(filter($_POST['password2'])));

		//Cek data apakah sudah ada dalam database?
		$check_userame = $connect->query("SELECT * FROM user WHERE username = '$username'");

		//Cek apakah from nik, nama lengkap, alamat, asal kantor, nomor handphone, username, email, password1, dan password 2 sudah terisi semua
		if (!$username || !$password1 || !$password2) {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Harap mengisi semua form.');
		}

		//Jika data username sudah ada didalam database, jika iya maka proses daftar gagal
		else if ($check_userame->num_rows > 0) {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Username <strong>(' . $username . ')</strong> Sudah terdaftar.');
		}

		//Jika jumlah karakter username kurang dari 6 maka proses daftar gagal
		elseif (strlen($username) < 6) {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Username minimal berjumlah 6 karakter.');
		}

		//Jika jumlah karakter password kurang dari 6 maka proses daftar gagal
		elseif (strlen($password1) < 6) {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Password minimal berjumlah 6 karakter.');
		}

		//Apakah password1 dan password2 cocok? jika tidak maka proses daftar gagal
		else if ($password1 <> $password2) {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Konfirmasi password tidak cocok, mohon ketik ulang password anda.');
		}

		//Apabila semua "if" diatas terlewati maka..
		else {

			//Hash password1 menggunakan password hash bawaan php
			$password_hash = password_hash($password1, PASSWORD_DEFAULT);

			//Jika Berhasil memasukan data kedalam database, maka proses daftar berhasi;
			if ($connect->query("INSERT INTO user (username, password) VALUES ('$username','$password_hash')") == true) {
				$_SESSION['notification'] = array('alert' => 'success', 'title' => 'Berhasil', 'message' => 'Akun anda berhasil didaftarkan, silakan masuk dihalaman login.');
			}

			//Jika gagal
			else {
				$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Fatal error!' . mysqli_error($connect));
			}
		}
	}
}
?>
<div class="register-box">
	<div class="register-logo">
		<p>Register</p>
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
	<div class="card">
		<div class="card-body register-card-body">
			<form method="POST">
				<div class="input-group mb-3">
					<input type="text" name="username" class="form-control" value="<?= empty($username) ? "" : $username; ?>" placeholder="Username">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-user"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" name="password1" class="form-control" placeholder="Password">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" name="password2" class="form-control" placeholder="Ketik ulang password">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- /.col -->
					<div class="col-4">
						<button type="submit" name="register" class="btn btn-primary btn-block">Daftar</button>
					</div>
					<!-- /.col -->
				</div>
			</form>

			<div class="social-auth-links text-center">
				<p>Sudah punya akun? <a href="<?= base_url() ?>login/">Klik Disini</a></p>
			</div>

		</div>
		<!-- /.form-box -->
	</div><!-- /.card -->
</div>
<!-- /.register-box -->
<?php
require 'layout/auth_footer.php';
?>