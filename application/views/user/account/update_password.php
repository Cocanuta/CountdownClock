<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <?php if(validation_errors()) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($error)) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-md-12">
            <div class="page-header">
                <h1>Update Password</h1>
            </div>
            <?= form_open() ?>
            <div class="form-group">
                <label for="oldPassword">Old Password</label>
                <input type="password" class="form-control" id="oldPassword" name="oldPassword">
            </div>
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword">
            </div>
            <div class="form-group">
                <label for="newPassword_confirm">Confirm New Password</label>
                <input type="password" class="form-control" id="newPassword_confirm" name="newPassword_confirm">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" value="Update">
            </div>
            </form>
            <p><a href="<?= base_url('account/manage') ?>">Go Back</a></p>
        </div>
    </div>
</div>
