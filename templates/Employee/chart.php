<?php
/**
 *
 * @var iterable<\App\Model\Entity\Employee> $chartData
 */
?>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Employee'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="employee chart content">
            <h3><?= __('Organization chart') ?></h3>
            <ul>
            <?php
                $stack = [];
                $level = [];
                foreach ($chartData as $item){
                    $stack[] = $item;
                }

                while (count($stack) > 0) {
                    $item = array_pop($stack);
                    while (count($level) > 0 && $item->parentId !== $level[count($level) - 1]) {
                        array_pop($level);
                        echo '</ul></li>';
                    }
                    echo '<li>' . $this->Html->link(h($item->full_name), ['action' => 'view', $item->id]);

                    if (count($item->children) > 0) {
                        $level[] = $item->id;
                        echo '<ul>';
                        foreach (array_reverse($item->children) as $child) {
                            $stack[] = $child;
                        }
                    }
                    else
                    {
                        echo '</li>';
                    }
                }
            ?>
            </ul>
        </div>
    </div>
</div>
