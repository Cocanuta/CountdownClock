<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1><?= $user['Username'] ?>'s Profile</h1>
            </div>
            <p><?= $user['FirstName'] ?> <?= $user['LastName'] ?></p>
            <br>
            <?php if($user['Username'] === $_SESSION['username']) : ?>
                <p><a href="<?= base_url('account/manage') ?>">Manage Profile</a></p>
            <?php endif; ?>

            <?php foreach($lists as $list) : ?>
                <h3><a href="<?= base_url('lists/'.$list->ID) ?>"><?= $list->Title ?></a>
                    <span class="btn-group">
                        <form method="post">
                            <div class="form-group">
                                <button type="submit" class="btn btn-default btn-xs" name="delete" value="true" formaction="<?= base_url('list/delete') ?>"><i class="fa fa-trash-o"></i></button>
                                <input type="hidden" name="listID" value="<?= $list->ID ?>">
                            </div>
                        </form>
                    </span>
                </h3>
                <p><?= $list->Description ?></p>
                <?php foreach($list->Clocks as $clock) : ?>
                    <h5><a href="<?= base_url('clocks/'.$clock->ID) ?>"><?= $clock->Title ?></a>
                        <span class="btn-group">
                            <form method="post">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default btn-xs" name="delete" value="true" formaction="<?= base_url('clock/delete') ?>"><i class="fa fa-trash-o"></i></button>
                                    <input type="hidden" name="clockID" value="<?= $clock->ID ?>">
                                    <input type="hidden" name="listID" value="<?= $list->ID ?>">
                                </div>
                            </form>
                        </span>
                    </h5>
                <p id="clock<?= $clock->ID ?>"></p>
                <script>
                    $('#clock<?= $clock->ID ?>').countdown('<?= $clock->Date ?>', function(event) {
                        $(this).html(event.strftime('%-D day%!D %H:%M:%S'));
                    });
                </script>
                    </br>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <p><a href="<?= base_url('clock/add') ?>">Add Clock</a></p>
            <p><a href="<?= base_url('list/add') ?>">Add List</a></p>

        </div>
    </div>
</div>