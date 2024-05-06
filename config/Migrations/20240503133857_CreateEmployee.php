<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateEmployee extends AbstractMigration
{
    /**
     * Change
     */
    public function change(): void
    {
        $table = $this->table('employee');
        $table->addColumn('firstName', 'string', ['limit' => 30])
            ->addColumn('lastName', 'string', ['limit' => 40])
            ->addColumn('phonePrefix', 'string', ['limit' => 8])
            ->addColumn('phoneNumber', 'string', ['limit' => 20])
            ->addColumn('email', 'string', ['limit' => 50])
            ->addColumn('parentId', 'integer', ['null' => true, "signed" => false])
            ->addForeignKey('parentId', 'employee', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('lft', 'integer', ['null' => false, "signed" => true])
            ->addIndex('lft')
            ->addColumn('rght', 'integer', ['null' => false, "signed" => true])
            ->create();
    }
}
