<?php
$page = 'Home';
require 'header.php';
?>

<section class="call-to-action text-white text-center">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h2 class="mb-4">Cari Pengaduan</h2>
            </div>
            <div class="col-xl-10   mx-auto mb-3">
                <form action="index.php" method="get">
                    <div class="form-row">
                        <div class="col-12 col-md-9 mb-2 mb-md-0">
                            <?php
                            //$passSearch = $connect->real_escape_string(filter($_GET['searchSubtitle']));
                            ?>
                            <input type="text" name="searchSubtitle" class="form-control form-control-lg" placeholder="Masukan kode pengaduan">
                        </div>
                        <div class="col-12 col-md-3">
                            <button type="submit" value="search" class="btn btn-block btn-lg btn-primary"><i class="fas fa-search"></i> Cari</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-10 mx-auto">
                <?php
                if (isset($_GET['searchSubtitle'])) {
                    $search = $connect->real_escape_string(filter($_GET['searchSubtitle']));
                ?>
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped text-black">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Author</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $getSubtitle = $connect->query("SELECT * FROM subtitle WHERE name like '%" . $search . "%'");
                                    while ($dataSubtitles = $getSubtitle->fetch_assoc()) {
                                        $tanggal = date('Y-m-d', strtotime($dataSubtitles['date']));
                                        $path = base_url() . "download/subtitle/";
                                        $linkDownloadSubtitle = $path . $dataSubtitles['slug'];
                                    ?>
                                        <tr>
                                            <td><a href="<?= $linkDownloadSubtitle ?>"><?= $dataSubtitles['name'] ?></a></td>
                                            <td><?= $dataSubtitles['author'] ?></td>
                                            <td><?= tanggal_indo($tanggal, true) ?></td>
                                        </tr>
                                    <?php
                                        $no++;
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Author</th>
                                        <th>Date</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>
<?php
require 'footer.php';
?>