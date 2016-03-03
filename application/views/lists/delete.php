<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Are you sure?</h1>
            </div>
            <p>You are about to delete <?= $list->Title ?> list.</p></br>
            <p>Are you sure you want to do this, this can not be undone.</p></br>
            <p>All countdown clocks will be deleted permanently.</p></br>
            <form method="post">
                <div class="form-group">
                    <button type="submit" class="btn btn-danger form-control" name="confirm_delete" value="true" formaction="<?= base_url('list/delete') ?>">Remove</button>
                    <input type="hidden" name="confirm_delete" value="confirm">
                    <input type="hidden" name="listID" value="<?= $list->ID ?>">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-default form-control" onclick="javascript:window.history.go(-1);">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>