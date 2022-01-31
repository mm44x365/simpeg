<?php
//Nama page/halaman
$page = "Dashboard";
//Start session
session_start();
//Load file config
require '../config.php';
//Ambil template header dashboard
require 'layout/dashboard_header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark"><?= $page ?></h1>
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

	<?php
	if ( $_SESSION['user']['role'] == 1 ) {
		require 'a_dashboard.php';
	}else{
		require 'u_dashboard.php';
	}
	?>
</div>
<!-- /.content-wrapper -->
<?php
require 'layout/dashboard_footer.php';
?>