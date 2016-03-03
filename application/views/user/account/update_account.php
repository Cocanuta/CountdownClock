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
                <h1>Update Profile</h1>
            </div>
            <?= form_open() ?>
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="<?= $data['FirstName'] ?>">
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?= $data['LastName'] ?>">
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control" max="2010-12-31" id="dob" name="dob" value="<?= $data['DateOfBirth'] ?>">
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <select class="form-control bfh-countries" data-country="<?= $data['Country'] ?>" id="country" name="country"></select>
            </div>
            <div class="form-group">
                <label for="timezone">Timezone</label>
                <select class="form-control bfh-timezones" data-timezone="<?= $data['Timezone'] ?>" data-country="country" id="timezone" name="timezone"></select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" value="Update">
            </div>
            </form>
        </div>
        <p><a href="<?= base_url('account/manage') ?>">Go Back</a></p>
    </div>
</div>
