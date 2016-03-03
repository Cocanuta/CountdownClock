<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Manage Profile</h1>
            </div>
            <p>Welcome <?= $_SESSION['firstname'] ?>!</p>
            <br>
            <p><a href="<?= base_url('account/manage/profile') ?>">Update Profile Information</a></p>
            <p><a href="<?= base_url('account/manage/password') ?>">Update Password</a></p>

        </div>
    </div>
</div>