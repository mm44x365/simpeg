<?php
check_session('member');
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?= $web_info['title'] ?> - <?= $page ?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bbootstrap 4 -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.Jcrop.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/jqvmap/jqvmap.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/adminlte.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/daterangepicker/daterangepicker.css">
	<!-- summernote -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/summernote/summernote-bs4.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

	<style type="text/css">
		.description {
			margin-top: 10px;
			margin-bottom: 10px;
		}

		.description p {
			margin: 0;
		}
	</style>

	<!-- jQuery -->
	<script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
			</ul>
		</nav>

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="<?= base_url() ?>" class="brand-link">
				<span class="brand-text font-weight-light"><?= $web_info['title'] ?> Desa Sungapan</span>
			</a>
			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<?php
						$roleUser = $_SESSION['user']['role'];

						//Jika role yang disimpan dalam session admin, tampilkan menu admin.
						if ($roleUser == 1) {
						?>
							<li class="nav-header">Admin</li>
							<li class="nav-item">
								<a href="<?= base_url() ?>dashboard/" class="nav-link 
								<?php if ($page == "Dashboard") {
									echo "active";
								}
								?>">
									<i class="nav-icon fas fa-tachometer-alt"></i>
									<p>Dashboard</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?= base_url() ?>user-management/" class="nav-link 
								<?php if ($page == "Users Management") {
									echo "active";
								} ?>">
									<i class="nav-icon fas fa-users-cog"></i>
									<p>Data Karyawan</p>
								</a>
							</li>
							<!-- <li class="nav-item">
								<a href="<?= base_url() ?>manage/subtitle/" class="nav-link 
							<?php if ($page == "Manage Subtitle") {
								echo "active";
							} ?>">
									<i class="nav-icon fas fa-sliders-h"></i>
									<p>Subtitle Management</p>
								</a>
							</li> -->
						<?php
						} else {
						?>
							<li class="nav-header">User</li>
							<li class="nav-item">
								<a href="<?= base_url() ?>dashboard/" class="nav-link 
								<?php if ($page == "Dashboard") {
									echo "active";
								} ?>">
									<i class="nav-icon fas fa-tachometer-alt"></i>
									<p>Dashboard</p>
								</a>
							</li>
							<li class="nav-header">Subtitle</li>
							<li class="nav-item">
								<a href="<?= base_url() ?>subtitle/new/" class="nav-link 
							<?php if ($page == "Tambah Subtitle") {
								echo "active";
							} ?>">
									<i class="nav-icon fas fa-plus"></i>
									<p>Tambahkan Subtitle Baru</p>
								</a>
							</li>
						<?php
						}
						?>
						<li class="nav-header">Setting</li>
						<li class="nav-item">
							<a href="<?= base_url() ?>account/settings/" class="nav-link 
							<?php if ($page == "Pengaturan Akun") {
								echo "active";
							} ?>">
								<i class="nav-icon fas fa-cog"></i>
								<p>Pengaturan Akun</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="#" class="nav-link" data-toggle="modal" data-target="#modal-logout">
								<i class="nav-icon fas fa-sign-out-alt"></i>
								<p>Logout</p>
							</a>
						</li>

					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>
		<div class="modal fade" id="modal-logout">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Konfirmasi</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>Apakah anda yakin akan keluar?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-success" data-dismiss="modal"><i class="fas fa-times btn-xs"></i> Tidak</button>
						<a href="<?= base_url() ?>logout" type="button" class="btn btn-danger"><i class="fas fa-check btn-xs"></i> Ya, saya yakin</a>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>