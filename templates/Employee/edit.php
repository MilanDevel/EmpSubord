<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 * @var iterable<\App\Model\Entity\Employee> $managers
 * @var array $prefixes
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $employee->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $employee->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Employee'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="employee form content">
            <?= $this->Form->create($employee, ['id' => 'editForm']) ?>
            <fieldset>
                <legend><?= __('Edit Employee') ?></legend>
                <?php
                    echo $this->Form->control('firstName', ['required' => true]);
                    echo $this->Form->control('lastName', ['required' => true]);
                    echo $this->Form->control('phonePrefix', ['type' => 'select', 'options' => $prefixes]);
                    echo $this->Form->control('phoneNumber');
                    echo $this->Form->control('email', ['required' => true, 'type' => 'email']);
                    if ($employee->parentId !== null)
                        echo $this->Form->control('parentId', ['type' => 'select', 'options' => $managers, 'empty' => false, 'label' => __('Manager')]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>

            <?= $this->Html->script('validators'); ?>
            <script>
                const form = document.getElementById('editForm');
                form.noValidate = true;
                form.addEventListener('submit', (e) => {
                    validatePhoneNumber(form.phonePrefix.value, form.phoneNumber);

                    if (!form.checkValidity()) {
                        e.preventDefault();
                        form.reportValidity();
                    }
                });
            </script>
        </div>
    </div>
</div>
