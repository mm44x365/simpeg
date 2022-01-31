<?php
$page = "Tambah Subtitle";
session_start();

//Load file config
require '../config.php';
require 'layout/dashboard_header.php';

check_session('member');

if (isset($_POST['add_data'])) {
	$title = $connect->real_escape_string(filter($_POST['title']));
	$author = $_SESSION['user']['username'];
	$web_title = $web_info['title'];

	$slug = str_replace(' ', '-', strtolower($title)) . '-' . time();

	if (!$title || empty($_FILES['file'])) {
		$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Harap mengisi semua form.');
	} else {
		if ($_FILES && $_FILES['file']) {
			if (!empty($_FILES['file']['name'][0])) {

				$zip = new ZipArchive();

				// Lokasi dan penamaan file zip
				$zip_name = "../assets/subtitles/" . $slug . '.' . $author  . '.' . $web_title . ".zip";

				// Buat target zip
				if ($zip->open($zip_name, ZipArchive::CREATE) !== TRUE) {
					$error .= "Sorry ZIP creation is not working currently.<br/>";
				}

				// Hitung jumlah file
				$fileCount = count($_FILES['file']['name']);
				for ($i = 0; $i < $fileCount; $i++) {
					if ($_FILES['file']['tmp_name'][$i] == '') {
						continue;
					}
					$newname = date('YmdHis', time()) . mt_rand() . '.temp';

					// Masukan file kedalam zip
					$zip->addFromString($_FILES['file']['name'][$i], file_get_contents($_FILES['file']['tmp_name'][$i]));

					// Pindah file kedalam folder
					move_uploaded_file($_FILES['file']['tmp_name'][$i], '../assets/subtitles/temp/' . $newname);
				}
				$zip->close();

				// Create HTML Link option to download zip
				$namaFile = basename($zip_name);

				// Hapus folder temp yang berisi data dengan rekursif
				delete_files('../assets/subtitles/temp/');

				// Buat ulang folder temp untuk menampung file temporary selanjutnya
				mkdir("../assets/subtitles/temp/", 0700);

				if ($connect->query("INSERT INTO subtitle (name, slug, file_name, author) VALUES ('$title','$slug', '$namaFile', '$author')") == true) {
					$_SESSION['notification'] = array('alert' => 'success', 'title' => 'Sukses', 'message' => 'Data berhasil ditambahkan.');
				} else {
					$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Fatal error!' . mysqli_error($connect));
				}
			} else {
				$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Tolong pilih file yang ingin diupload');
			}
		}
	}
}
if (isset($_POST['delete_data'])) {
	$fileName = $connect->real_escape_string(filter($_POST['fileName']));
	$author = $_SESSION['user']['username'];



	if (!$fileName) {
		$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Harap mengisi semua form.');
	} else {
		$target = "../assets/subtitles/" . $fileName;
		// Cek ada file
		if (file_exists($target)) {
			unlink($target);
		} else {
			$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Fatal error! Gagal menghapus file.');
		}

		if ($connect->query("DELETE FROM subtitle WHERE file_name = '$fileName' AND author = '$author' ") == true) {
			$_SESSION['notification'] = array('alert' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil dihapus.');
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
										<th>Name</th>
										<th>Link Download</th>
										<th>Author</th>
										<th>Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$author = $_SESSION['user']['username'];
									$getSubtitle = $connect->query("SELECT * FROM subtitle WHERE author = '$author'");
									while ($dataSubtitles = $getSubtitle->fetch_assoc()) {
										$tanggal = date('Y-m-d', strtotime($dataSubtitles['date']));
										$path = base_url() . "download/subtitle/";
										$linkSubtitle = $path . $dataSubtitles['slug'];
									?>
										<tr>
											<td><?= $no ?></td>
											<td><?= $dataSubtitles['name'] ?></td>
											<td>
												<input type="text" value="<?= $linkSubtitle ?>" id="linkSubtitle">
											</td>
											<td><?= $dataSubtitles['author'] ?></td>
											<td><?= tanggal_indo($tanggal, true) ?></td>
											<td>
												<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete-data<?= $no ?>"><i class="fas fa-trash"></i></button>
												<div class="modal fade" id="modal-delete-data<?= $no ?>">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title">Hapus Data #<?= $no ?> (<?= $dataSubtitles['name'] ?>)</h4>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<p>Apakah anda yakin akan menghapus data <i><?= $dataSubtitles['name'] ?></i>?, semua data akan terhapus, tindakan ini tidak bisa dikembalikan.</p>
															</div>
															<div class="modal-footer">
																<form method="POST">
																	<input type="text" name="fileName" value="<?= $dataSubtitles['file_name'] ?>" hidden>
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
										<th>Name</th>
										<th>File Name</th>
										<th>Author</th>
										<th>Date</th>
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
				<form method="POST" enctype="multipart/form-data">
					<div class="input-group mb-3">
						<input type="text" name="title" id="title" class="form-control" placeholder="Title">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-edit"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="file" name="file[]" id="file" class="form-control" multiple>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fa fa-upload"></span>
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