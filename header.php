<?php
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $web_info['title'] ?> - <?= $page ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url() ?>assets/index/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="<?= base_url() ?>assets/index/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/index/vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="<?= base_url() ?>assets/index/css/landing-page.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">


</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-primary static-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>"><?= $web_info['title'] ?></a>
            <a class="btn btn-danger" href="<?= base_url() ?>login/">Masuk</a>
        </div>
    </nav>