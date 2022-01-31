<?php
$page = 'Download Subtitle';
require 'header.php';

if (isset($_GET['slug'])) {
    $slug = $connect->real_escape_string(filter($_GET['slug']));
    $getSubtitle = $connect->query("SELECT * FROM subtitle WHERE slug like '$slug'");

    // Jika data tidak ditemukan
    if ($getSubtitle->num_rows < 1) {
        require '404.php';
    } else {
        $dataSubtitle = mysqli_fetch_assoc($getSubtitle);
        $fileName = $dataSubtitle['file_name'];
        require 'download_section.php';
    }
}
?>

<?php
require 'footer.php';
?>