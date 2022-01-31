<?php
$page = "Users Management";
session_start();

//Load file config
require '../config.php';
require 'layout/dashboard_header.php';

check_session('admin');

if (isset($_POST['add_data'])) {
	//Tangkap data yang di post form register
	$username = $connect->real_escape_string(trim(filter($_POST['username'])));
	$password1 = $connect->real_escape_string(trim(filter($_POST['password1'])));
	$password2 = $connect->real_escape_string(trim(filter($_POST['password2'])));
	$nik = $connect->real_escape_string(trim(filter($_POST['nik'])));
	$fullname = $connect->real_escape_string(trim(filter($_POST['fullname'])));
	$address = $connect->real_escape_string(trim(filter($_POST['address'])));

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
		if ($connect->query("INSERT INTO user (username, password, nik, fullname, address) VALUES ('$username','$password_hash','$nik','$fullname','$address')") == true) {
			$_SESSION['notification'] = array('alert' => 'success', 'title' => 'Berhasil', 'message' => 'Akun anda berhasil didaftarkan, silakan masuk dihalaman login.');
		}

		//Jika gagal
		else {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Fatal error!' . mysqli_error($connect));
		}
	}
}
if (isset($_POST['edit_data'])) {
	$idUser = $connect->real_escape_string(filter($_POST['idUser']));
	$realUsername = $connect->real_escape_string(filter($_POST['realUsername']));
	$username = $connect->real_escape_string(filter($_POST['username']));
	$fullname = $connect->real_escape_string(filter($_POST['fullname']));
	$nik = $connect->real_escape_string(filter($_POST['nik']));
	$address = $connect->real_escape_string(filter($_POST['address']));
	$role = $connect->real_escape_string(filter($_POST['role']));
	$is_active = $connect->real_escape_string(filter($_POST['is_active']));

	if (!$idUser || !$username || !$role) {
		$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Harap mengisi semua form.');
	} elseif ($username != $realUsername) {
		//Cek data apakah sudah ada dalam database?
		$checkUsername = $connect->query("SELECT * FROM user WHERE username = '$username'");
		if ($checkUsername->num_rows > 0) {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Email <strong>(' . $username . ')</strong> Sudah terdaftar.');
		} elseif ($connect->query("UPDATE user SET username = '$username', fullname = '$fullname', nik = '$nik', address = '$address', role = '$role', is_active = '$is_active' WHERE id = '$idUser'") == true) {
			echo $username, $role, $is_active;
			$_SESSION['notification'] = array('alert' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil diubah.');
		} else {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Fatal error!' . mysqli_error($connect));
		}
	} else {
		if ($connect->query("UPDATE user SET username = '$username', fullname = '$fullname', nik = '$nik', address = '$address',  role = '$role', is_active = '$is_active' WHERE id = '$idUser'") == true) {
			echo $username, $role, $is_active;
			$_SESSION['notification'] = array('alert' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil diubah.');
		} else {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Fatal error!' . mysqli_error($connect));
		}
	}
}
if (isset($_POST['delete_data'])) {
	$idUser = $connect->real_escape_string(filter($_POST['idUser']));
	if (!$idUser) {
		$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Harap mengisi semua form.');
	} else {
		if ($connect->query("DELETE FROM user WHERE id = '$idUser'") == true) {
			$_SESSION['notification'] = array('alert' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil dihapus.');
		} else {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Fatal error!');
		}
	}
}
if (isset($_POST['edit_password'])) {
	$password1 = $connect->real_escape_string(trim(filter($_POST['password1'])));
	$password2 = $connect->real_escape_string(trim(filter($_POST['password2'])));
	$idUser = $connect->real_escape_string(filter($_POST['idUser']));

	if (!$password1 || !$password2 || !$idUser) {
		$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Harap mengisi semua form.');
	} elseif ($password1 <> $password1) {
		$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Konfirmasi password tidak cocok, mohon ketik ulang password anda.');
	} else {
		//Hash password1 menggunakan password hash bawaan php
		$password_hash = password_hash($password1, PASSWORD_DEFAULT);
		if ($connect->query("UPDATE user SET password = '$password_hash' WHERE id = '$idUser'") == true) {
			$_SESSION['notification'] = array('alert' => 'success', 'title' => 'Berhasil', 'message' => 'Berhasil mengubah password akun.');
		} else {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Fatal error!');
		}
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
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-data"><i class="fas fa-plus btn-xs"></i> Tambah Data</button>
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<table id="example1" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>No.</th>
										<th>Username</th>
										<th>Nama Lengkap</th>
										<th>Alamat</th>
										<th>Role</th>
										<th>Status</th>
										<th>Last IP</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$getUsers = $connect->query("SELECT * FROM user");
									while ($dataUsers = $getUsers->fetch_assoc()) {
									?>
										<tr>
											<td><?= $no ?></td>
											<td><?= $dataUsers['username'] ?></td>
											<td><?= $dataUsers['fullname'] ?></td>
											<td><?= $dataUsers['address'] ?></td>
											<td>
												<?php
												if ($dataUsers['role'] == 1) {
													echo "Admin";
												} else {
													echo "Karyawan";
												}
												?>
											</td>
											<td>
												<?php
												if ($dataUsers['is_active'] == 1) {
													echo "Aktif";
												} else {
													echo "Tidak Aktif";
												}
												?>
											</td>
											<td><?= $dataUsers['last_login_ip'] ?></td>
											<td>
												<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-edit-data<?= $no ?>"><i class="fas fa-pen"></i></button>
												<div class="modal fade" id="modal-edit-data<?= $no ?>">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title">Ubah Data #<?= $no ?></h4>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<form method="POST">
																	<div class="input-group mb-3">
																		<input type="text" name="idUser" value="<?= $dataUsers['id'] ?>" hidden>
																		<input type="text" name="realUsername" value="<?= $dataUsers['username'] ?>" hidden>
																		<input type="text" name="username" class="form-control" placeholder="Username" value="<?= $dataUsers['username'] ?>" required>
																		<div class="input-group-append">
																			<div class="input-group-text">
																				<span class="fa fa-user"></span>
																			</div>
																		</div>
																	</div>
																	<div class="input-group mb-3">
																		<input type="text" name="fullname" class="form-control" placeholder="Nama Lengkap" value="<?= $dataUsers['fullname'] ?>" required>
																		<div class="input-group-append">
																			<div class="input-group-text">
																				<span class="fa fa-user"></span>
																			</div>
																		</div>
																	</div>
																	<div class=" input-group mb-3">
																		<input type="text" name="nik" class="form-control" placeholder="NIK" value="<?= $dataUsers['nik'] ?>" required>
																		<div class=" input-group-append">
																			<div class="input-group-text">
																				<span class="fas fa-address-card"></span>
																			</div>
																		</div>
																	</div>
																	<div class="input-group mb-3">
																		<textarea name="address" id="address" class="form-control" cols="30" rows="10" required><?= $dataUsers['address'] ?></textarea>
																		<!-- <input type="text" name="fullname" class="form-control" placeholder="Nama Lengkap"> -->
																		<div class="input-group-append">
																			<div class="input-group-text">
																				<span class="fas fa-map-marked-alt"></span>
																			</div>
																		</div>
																	</div>
																	<div class="input-group mb-3">
																		<select class="form-control" value="<?= $dataUsers['role'] ?>" name="role">
																			<option <?php if ($dataUsers['role'] == 1) { ?>selected="true" <?php } ?> value="1">Admin</option>
																			<option <?php if ($dataUsers['role'] == 2) { ?>selected="true" <?php } ?> value="2">Karyawan</option>
																		</select>
																	</div>
																	<div class="input-group mb-3">
																		<select class="form-control" value="<?= $dataUsers['is_active'] ?>" name="is_active">
																			<option <?php if ($dataUsers['is_active'] == 0) { ?>selected="true" <?php } ?> value="0">Tidak Aktif</option>
																			<option <?php if ($dataUsers['is_active'] == 1) { ?>selected="true" <?php } ?> value="1">Aktif</option>
																		</select>
																	</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times btn-xs"></i> Batal</button>
																<button type="submit" name="edit_data" class="btn btn-success"><i class="fas fa-paper-plane btn-xs"></i> Simpan</button>
																</form>
															</div>
														</div>
														<!-- /.modal-content -->
													</div>
													<!-- /.modal-dialog -->
												</div>
												<!-- /.modal -->
												<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-ubah-password<?= $no ?>"><i class="fas fa-lock"></i></button>
												<div class="modal fade" id="modal-ubah-password<?= $no ?>">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title">Ubah Password Admin #<?= $no ?></h4>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<form method="POST">
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
																	<input type="text" name="idUser" value="<?= $dataUsers['id'] ?>" hidden>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times btn-xs"></i> Batal</button>
																<button type="submit" name="edit_password" class="btn btn-success"><i class="fas fa-paper-plane btn-xs"></i> Kirim</button>
																</form>
															</div>
														</div>
														<!-- /.modal-content -->
													</div>
													<!-- /.modal-dialog -->
												</div>
												<!-- /.modal -->
												<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete-data<?= $no ?>"><i class="fas fa-trash"></i></button>
												<div class="modal fade" id="modal-delete-data<?= $no ?>">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title">Hapus Data #<?= $no ?></h4>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<form method="POST">
																	<p>Apakah anda yakin akan menghapus username <i><?= $dataUsers['username'] ?></i>?</p>
																	<input type="text" name="idUser" value="<?= $dataUsers['id'] ?>" hidden>
															</div>
															<div class="modal-footer">
																<button type="submit" name="delete_data" class="btn btn-danger"><i class="fas fa-paper-plane btn-xs"></i> Yakin, Hapus</button>
																<button type="button" class="btn btn-success" data-dismiss="modal"><i class="fas fa-times btn-xs"></i> Batal</button>
																</form>
															</div>
														</div>
														<!-- /.modal-content -->
													</div>
													<!-- /.modal-dialog -->
												</div>
												<!-- /.modal -->
											</td>
										</tr>
									<?php
										$no++;
									} ?>
								</tbody>
								<tfoot>
									<tr>
										<th>No.</th>
										<th>Username</th>
										<th>Nama Lengkap</th>
										<th>Alamat</th>
										<th>Role</th>
										<th>Status</th>
										<th>Last IP</th>
										<th>Action</th>
									</tr>
								</tfoot>
							</table>
						</div>
						<!-- /.card-body -->
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<div class="modal fade" id="modal-add-data">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Data</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST">
					<div class="input-group mb-3">
						<input type="text" name="username" class="form-control" placeholder="Username" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="text" name="nik" class="form-control" placeholder="NIK" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-address-card"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="text" name="fullname" class="form-control" placeholder="Nama Lengkap" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" name="password1" class="form-control" placeholder="Password" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" name="password2" class="form-control" placeholder="Ketik ulang password" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<textarea name="address" id="address" class="form-control" cols="30" rows="10" required></textarea>
						<!-- <input type="text" name="fullname" class="form-control" placeholder="Nama Lengkap"> -->
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-map-marked-alt"></span>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times btn-xs"></i> Batal</button>
				<button type="submit" name="add_data" class="btn btn-success"><i class="fas fa-paper-plane btn-xs"></i> Kirim</button>
				</form>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php
require 'layout/dashboard_footer.php';
?>