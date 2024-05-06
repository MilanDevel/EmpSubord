<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 * @var iterable<\App\Model\Entity\Employee> $employees
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Employee'), ['action' => 'edit', $employee->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Employee'), ['action' => 'delete', $employee->id], ['confirm' => __('Are you sure you want to delete # {0}?', $employee->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Employee'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Employee'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="employee view content">
            <h3><?= h($employee->lastName . ' ' . $employee->firstName) ?></h3>
            <table>
                <tr>
                    <th><?= __('First Name') ?></th>
                    <td><?= h($employee->firstName) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Name') ?></th>
                    <td><?= h($employee->lastName) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone Prefix') ?></th>
                    <td><?= h($employee->phonePrefix) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone Number') ?></th>
                    <td><?= h($employee->phoneNumber) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($employee->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Manager') ?></th>
                    <td><?= $employee->parentId === null ? 'CEO' : h($employee->manager->full_name) ?></td>
                </tr>
            </table>

            <div class="table-responsive">
                <table>
                    <caption><h4><?= h('Subordinates') ?></h4></caption>
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('firstName', 'First name') ?></th>
                        <th><?= $this->Paginator->sort('lastName', 'Last name') ?></th>
                        <th><?= $this->Paginator->sort('merged_phone_number', 'Phone number') ?></th>
                        <th><?= $this->Paginator->sort('email') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?= h($employee->firstName) ?></td>
                            <td><?= h($employee->lastName) ?></td>
                            <td><?= h($employee->merged_phone_number) ?></td>
                            <td><?= h($employee->email) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->first('<< ' . __('first')) ?>
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                    <?= $this->Paginator->last(__('last') . ' >>') ?>
                </ul>
                <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
            </div>
        </div>
    </div>
</div>
