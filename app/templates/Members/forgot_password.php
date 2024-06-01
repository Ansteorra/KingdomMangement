<?php $this->extend("/layout/TwitterBootstrap/signin"); ?>
<div class="card" style="width: 15rem;">
    <?= $this->Html->image($headerImage, [
        "class" => "card-img-top",
        "alt" => "site logo",
    ]) ?>
    <div class="card-body">
        <h5 class="card-title">Forgot Password</h5>
        <div class="card-text">
            <?= $this->Form->create() ?>
            <?= $this->Form->control("email_address") ?>
            <?= $this->Form->button("Send Password Reset") ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>