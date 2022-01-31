<?php
$page = "Pengaturan Akun";
session_start();

//Load file config
require '../config.php';
require 'layout/dashboard_header.php';

if (isset($_POST['submit'])) {
	if ($_POST['password'] != '' && $_POST['new_password'] != '') {

		$idUser = $_SESSION['user']['id'];
		$dataUser = $connect->query("select * from user where id='$idUser'");

		if ($dataUser->num_rows > 0) {
			$fetchedData = $dataUser->fetch_assoc();

			$password = $connect->real_escape_string(trim(filter($_POST['password'])));
			$new_password = $connect->real_escape_string(trim(filter($_POST['new_password'])));
			$password_hash = password_hash($new_password, PASSWORD_DEFAULT);
			$verif_pass = password_verify($password, $fetchedData['password']);

			if (strlen($new_password) < 6) {
				$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Password minimal berjumlah 6 karakter.');
			} elseif ($verif_pass == true) {
				if ($connect->query("UPDATE user SET password ='$password_hash' WHERE id='$idUser'")) {
					$_SESSION['notification'] = array('alert' => 'success', 'title' => 'Berhasil', 'message' => 'Perubahan telah disimpan!');
				} else {
					$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Perubahan gagal tersimpan, silahkan coba lagi! Error : ' . mysqli_error($connect));
				}
			} else {
				$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Password saat ini kamu salah.');
			}
		}
	} else {
		$_SESSION['notification'] = array('alert' => 'info', 'title' => 'Gagal', 'message' => 'Tidak ada perubahan data.');
	}
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark"><i class="nav-icon fas fa-users"></i> <?= $page ?></h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active"><?= $page ?></li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
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
				</div>
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Data Akun Kamu</h3>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<form class="form" action="" method="post">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Username</label>
													<input type="text" name="username" class="form-control" value="<?= $_SESSION['user']['username'] ?>" readonly="true">
												</div>
												<div class="form-group">
													<label>Password</label>
													<input type="password" name="password" class="form-control mb-2" placeholder="Password Saat ini">
													<input type="password" name="new_password" class="form-control" placeholder="Password Baru">
													<small>isi password baru jika ingin mengganti password</small>
												</div>
												<div class="form-group float-right">
													<button type="submit" name="submit" class="btn btn-sm btn-success"><i class="fas fa-paper-plane btn-xs"></i> Kirim</button>
												</div>
											</div>
											<div class="col-md-6">

											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

</div>

<?php
require 'layout/dashboard_footer.php';
?>