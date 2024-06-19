<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Branch $branch
 */
?>
<?php $this->extend("/layout/TwitterBootstrap/dashboard"); ?>


<div class="branches form content">
    <?= $this->Form->create($branch) ?>
    <fieldset>
        <legend><?= __("Add Branch") ?></legend>
        <?php
        echo $this->Form->control("name");
        echo $this->Form->control("location");
        echo $this->Form->control("parent_id", [
            "options" => $treeList,
            "empty" => true,
        ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__("Submit")) ?>
    <?= $this->Form->end() ?>
</div>