<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container" style="position: relative; height: 90vh;">
    <div class="text-uppercase text-center" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width:100%;">
        <p class="fa fa-clock-o" style="font-size: 5vw;"></p>
        <p style="font-size: 4vw;"><?= $clock->Title ?></p>
        <p id="clock" style="font-size: 5vw;"></p>
        <p>Created by <a href="<?= base_url('user/'.$user['Username']) ?>"><?= $user['Username'] ?></a></p>
    </div>
</div>
<script>
    $('#clock').countdown('<?= $clock->Date ?>', function(event) {
        $(this).html(event.strftime('%-D day%!D %H:%M:%S'));
    });
</script>