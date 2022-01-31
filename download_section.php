<section class="features-icons bg-light text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <?= $page . ' - ' . $dataSubtitle['name'] . ' by ' . $dataSubtitle['author'] ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Link Download</h5>
                        <p class="card-text">Klik tombol dibawah ini untuk mendownload subtitle <?= $dataSubtitle['name'] . ' by ' . $dataSubtitle['author'] ?>.</p>
                        <?php
                        $downloadLink = base_url() . "assets/subtitles/" . $fileName;
                        ?>
                        <a href="<?= $downloadLink   ?>" class="btn btn-primary"><i class="fas fa-download"></i> Download Subtitle</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>