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
                <h1>Add New Countdown Clock</h1>
            </div>
            <?= form_open() ?>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="datetime-local" class="form-control" id="date" name="date">
            </div>
            <div class="form-group">
                <label for="list">List</label>
                <select class="form-control" id="list" name="list">
                    <?php foreach($lists as $list) : ?>
                        <option value="<?= $list->ID ?>"><?= $list->Title ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" value="Add">
            </div>
            </form>
        </div>
    </div>
</div>
