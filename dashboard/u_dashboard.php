<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <?php
                        $sessUser = $_SESSION['user']['username'];
                        //$sessUser = 'abc';
                        ?>
                        <h3><?= dashboardAdmin('totalSubtitleUser', $sessUser) ?></h3>
                        <p>Total Subtitle Diupload</p>
                    </div>
                    <div class="icon">
                    <i class="fas fa-upload"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->