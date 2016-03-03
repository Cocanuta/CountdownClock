<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Countdown Clocks <?= $pageTitle ?></title>

    <!-- css -->
    <link rel="stylesheet" href="<?php echo base_url(''); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(''); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(''); ?>assets/css/bootstrap-formhelpers.min.css">
    <link rel="stylesheet" href="<?php echo base_url(''); ?>assets/css/main.css">

    <!-- js -->
    <script src="<?php echo base_url(''); ?>assets/js/jquery-1.12.1.min.js"></script>
    <script src="<?php echo base_url(''); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(''); ?>assets/js/bootstrap-formhelpers.min.js"></script>
    <script src="<?php echo base_url(''); ?>assets/js/jquery.countdown.min.js"></script>
</head>
<body>
<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-toggle=".navbar-collapse">
                <span class="sr-only">Toggle naviation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url(''); ?>"><strong>Countdown Clocks</strong></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php if(isset($_SESSION['username']) && $_SESSION['logged_in'] === true) : ?>
                    <li><a href="<?= base_url('user') ?>"><?= $_SESSION['username'] ?></a></li>
                    <li><a href="<?= base_url('logout') ?>">Logout</a></li>
                <?php else : ?>
                    <li><a href="<?= base_url('register') ?>">Register</a></li>
                    <li><a href="<?= base_url('login') ?>">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>