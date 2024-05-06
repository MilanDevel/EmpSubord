<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Employee> $employees
 */
?>
<div class="employee index content">
    <?= $this->Html->link(__('New Employee'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <?= $this->Html->link(__('Org chart tree'), ['action' => 'chart'], ['class' => 'button float-right margin-right-1']) ?>
    <h3><?= __('Employee') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('firstName', 'First name') ?></th>
                    <th><?= $this->Paginator->sort('lastName', 'Last name') ?></th>
                    <th><?= $this->Paginator->sort('merged_phone_number', 'Phone number') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('manager_name', 'Manager') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= $this->Number->format($employee->id) ?></td>
                    <td><?= h($employee->firstName) ?></td>
                    <td><?= h($employee->lastName) ?></td>
                    <td><?= h($employee->merged_phone_number) ?></td>
                    <td><?= h($employee->email) ?></td>
                    <td><?= $employee->manager_name === null ? 'CEO' : h($employee->manager_name) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $employee->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $employee->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $employee->id],
                            ['confirm' => __('Are you sure you want to delete employee {0}?', $employee->full_name)]) ?>
                        <?= $employee->parentId !== null
                            ? $this->Form->postLink(__('Set CEO'), ['action' => 'setceo', $employee->id],
                                ['confirm' => __('Are you sure you want set employee {0} as CEO?', $employee->full_name)])
                            : ''
                        ?>
                    </td>
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
