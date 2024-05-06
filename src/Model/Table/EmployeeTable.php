<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Employee Model
 *
 * @method \App\Model\Entity\Employee newEmptyEntity()
 * @method \App\Model\Entity\Employee newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Employee> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Employee get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Employee findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Employee patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Employee> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Employee|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Employee saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Employee>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Employee>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Employee>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Employee> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Employee>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Employee>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Employee>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Employee> deleteManyOrFail(iterable $entities, array $options = [])
 */
class EmployeeTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('Tree', [
            'parent' => 'parentId'
        ]);

        $this->setTable('employee');
        $this->setDisplayField('full_name');
        $this->setPrimaryKey('id');
        $this->belongsTo('Manager')
            ->setClassName('Employee')
            ->setForeignKey('parentId')
            ->setProperty('manager');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('firstName')
            ->maxLength('firstName', 30)
            ->notEmptyString('firstName');

        $validator
            ->scalar('lastName')
            ->maxLength('lastName', 40)
            ->notEmptyString('lastName');

        $validator
            ->scalar('phonePrefix')
            ->maxLength('phonePrefix', 8)
            ->allowEmptyString('phonePrefix');

        $validator
            ->scalar('phoneNumber')
            ->maxLength('phoneNumber', 20)
            ->allowEmptyString('phoneNumber');

        $validator
            ->email('email')
            ->notEmptyString('email');

        $validator
            ->nonNegativeInteger('parentId')
            ->allowEmptyString('parentId');

        return $validator;
    }

    /**
     * Build rules that check foreign key validity
     * for example non-exist ID
     *
     * @param RulesChecker $rules
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(
            ['parentId'],
            'Manager',
            ['allowNullableNulls' => true]
        ));

        return $rules;
    }
}
