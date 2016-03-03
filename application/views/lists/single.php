<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1><?= $list->Title ?></h1>
            </div>
            <p><?= $list->Description ?></p>
            <br>

            <?php foreach($list->Clocks as $clock) : ?>
                <h5><a href="<?= base_url('clocks/'.$clock->ID) ?>"><?= $clock->Title ?></a></h5>
                <p><?= $clock->Date ?></p>
            <?php endforeach; ?>

            <p><a href="<?= base_url('user/'.$user['Username']) ?>"><?= $user['Username'] ?></a></p>

        </div>
    </div>
</div>