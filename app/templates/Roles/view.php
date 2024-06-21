<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
?>
<?php

use Cake\I18n\DateTime;
use Cake\Log\Log;

$this->extend("/layout/TwitterBootstrap/view_record");

echo $this->KMP->startBlock("title");
echo $this->KMP->getAppSetting("KMP.ShortSiteTitle", "KMP") . ': View Role - ' . $role->name;
$this->KMP->endBlock();

echo $this->KMP->startBlock("pageTitle") ?>
<?= h($role->name) ?>
<?php $this->KMP->endBlock() ?>
<?= $this->KMP->startBlock("recordActions") ?>
<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
<?= $this->Form->postLink(
    __("Delete"),
    ["action" => "delete", $role->id],
    [
        "confirm" => __(
            "Are you sure you want to delete {0}?",
            $role->name,
        ),
        "title" => __("Delete"),
        "class" => "btn btn-danger btn-sm",
    ],
) ?>
<?php $this->KMP->endBlock() ?>
<?php $this->KMP->startBlock("recordDetails") ?>
<?php $this->KMP->endBlock() ?>
<?php $this->KMP->startBlock("tabButtons") ?>
<button class="nav-link" id="nav-assignedMembers-tab" data-bs-toggle="tab" data-bs-target="#nav-assignedMembers"
    type="button" role="tab" aria-controls="nav-assignedMembers" aria-selected="false"><?= __("Assigned Members") ?>
</button>
<button class="nav-link" id="nav-rolePermissions-tab" data-bs-toggle="tab" data-bs-target="#nav-rolePermissions"
    type="button" role="tab" aria-controls="nav-rolePermissions" aria-selected="false"><?= __("Permissions") ?>
</button>
<?php $this->KMP->endBlock() ?>
<?php $this->KMP->startBlock("tabContent") ?>
<div class="related tab-pane fade m-3" id="nav-assignedMembers" role="tabpanel"
    aria-labelledby="nav-assignedMembers-tab">
    <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal"
        data-bs-target="#addMemberModal">Add
        Member</button>

    <?php if (!$isEmpty) {
        echo $this->element('turboActiveTabs', [
            'user' => $user,
            'tabGroupName' => "authorizationTabs",
            'tabs' => [
                "active" => [
                    "label" => __("Active"),
                    "id" => "current-memberRoles",
                    "selected" => true,
                    "turboUrl" => $this->URL->build(["controller" => "MemberRoles", "action" => "RoleMemberRoles", "current", $id])
                ],
                "pending" => [
                    "label" => __("Upcoming"),
                    "id" => "pending-memberRoles",
                    "selected" => false,
                    "turboUrl" => $this->URL->build(["controller" => "MemberRoles", "action" => "RoleMemberRoles", "upcoming", $id])
                ],
                "previous" => [
                    "label" => __("Previous"),
                    "id" => "previous-memberRoles",
                    "selected" => false,
                    "turboUrl" => $this->URL->build(["controller" => "MemberRoles", "action" => "RoleMemberRoles", "previous", $id])
                ]
            ]
        ]);
    } else {
        echo "<p>No Members Assigned</p>";
    } ?>
</div>
<div class="related tab-pane fade m-3" id="nav-rolePermissions" role="tabpanel"
    aria-labelledby="nav-rolePermissions-tab">
    <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal"
        data-bs-target="#addPermissionModal">Add
        Permission</button>
    <?php if (!empty($role->permissions)) : ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col" colspan='1'></th>
                    <th scope="col" colspan='4' class="text-center table-active">Requirements</th>
                    <th scope="col" colspan='3'></th>
                </tr>
                <tr>
                    <th scope="col"><?= __("Name") ?></th>
                    <th scope="col" class="text-center"><?= __(
                                                                "Membership",
                                                            ) ?></th>
                    <th scope="col" class="text-center"><?= __(
                                                                "Background Check",
                                                            ) ?></th>
                    <th scope="col" class="text-center"><?= __(
                                                                "Minimum Age",
                                                            ) ?></th>
                    <th scope="col" class="text-center"><?= __(
                                                                "Warrant",
                                                            ) ?></th>
                    <th scope="col" class="text-center"><?= __(
                                                                "Super User",
                                                            ) ?></th>
                    <th scope="col" class="text-center"><?= __(
                                                                "System",
                                                            ) ?></th>
                    <th scope="col" class="actions"><?= __(
                                                            "Actions",
                                                        ) ?></th>
                </tr>
            </thead>
            <?php foreach ($role->permissions as $permission) : ?>
            <tr>
                <td><?= h($permission->name) ?></td>

                <td class="text-center"><?= $this->Kmp->bool(
                                                    $permission->require_active_membership,
                                                    $this->Html,
                                                ) ?></td>
                <td class="text-center"><?= $this->Kmp->bool(
                                                    $permission->require_active_background_check,
                                                    $this->Html,
                                                ) ?>
                </td>
                <td class="text-center"><?= h(
                                                    $permission->require_min_age,
                                                ) ?></td>
                <td class="text-center"><?= $this->Kmp->bool(
                                                    $permission->requires_warrant,
                                                    $this->Html,
                                                ) ?></td>
                <td class="text-center"><?= $this->Kmp->bool(
                                                    $permission->is_super_user,
                                                    $this->Html,
                                                ) ?></td>
                <td class="text-center"><?= $this->Kmp->bool(
                                                    $permission->is_system,
                                                    $this->Html,
                                                ) ?></td>
                <td class="actions">
                    <?= $this->Form->postLink(
                                __("Remove"),
                                [
                                    "controller" => "Roles",
                                    "action" => "deletePermission",
                                ],
                                [
                                    "confirm" => __(
                                        "Are you sure you want to remove for {0}?",
                                        $permission->name,
                                    ),
                                    "class" => "btn btn-danger",
                                    "data" => [
                                        "permission_id" => $permission->id,
                                        "role_id" => $role->id,
                                    ],
                                ],
                            ) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php $this->KMP->endBlock() ?>


<?php
echo $this->KMP->startBlock("modals");

echo $this->element('roles/addMemberModal', []);
echo $this->element('roles/addPermissionModal', []);
echo $this->element('roles/editModal', []);

$this->KMP->endBlock();
?>




<?php
echo $this->KMP->startBlock("script"); ?>
<script>
class rolesView {
    constructor() {
        this.ac = null;
    };
    run() {
        var me = this;
        var searchUrl = '<?= $this->URL->build(['controller' => 'Members', 'action' => 'SearchMembers']) ?>';
        KMP_utils.configureAutoComplete(me.ac, searchUrl, 'add_member__sca_name', 'id', 'sca_name',
            'add_member__member_id')
        $('#add_member__member_id').change(function() {
            if ($('#add_member__member_id').val() > 0) {
                //enable button
                $('#add_member__submit').prop('disabled', false);
            } else {
                //disable button
                $('#add_member__submit').prop('disabled', true);
            }
        });
        $('#add_member__submit').on('click', function() {
            if ($('#add_member__member_id').val() > 0) {
                $('#add_member__form').submit();
            }
        });
        $("#add_permission__permission_id").change(function() {
            var end = this.value;
            if (end > 0) {
                $('#add_permission__submit').prop('disabled', false);
            } else {
                $('#add_permission__submit').prop('disabled', true);
            }
        });
        $('#add_permission__submit').on('click', function() {
            if ($('#add_permission__permission_id').val() > 0) {
                $('#add_permission__form').submit();
            }
        });
    }
}
window.addEventListener('DOMContentLoaded', function() {
    var pageControl = new rolesView();
    pageControl.run();
});
</script>
<?php echo $this->KMP->endBlock(); ?>