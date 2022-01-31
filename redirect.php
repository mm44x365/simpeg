<center>
    <h1>Anda tau tempik?</h1>
</center>
<?php
$_SESSION['notification'] = array('alert' => 'danger', 'title' => 'Gagal', 'message' => 'Ada sesuatu yang salah hehe.');
?>
<script>
    window.location.replace('<?= base_url() ?>login');
</script>