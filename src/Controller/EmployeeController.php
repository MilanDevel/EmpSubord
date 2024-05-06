<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query\SelectQuery;

/**
 * Employee Controller
 *
 * @property \App\Model\Table\EmployeeTable $Employee
 */
class EmployeeController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Employee->find('all', contain: ['Manager']);
        $query->selectAlso([
            'merged_phone_number' => $query->func()->concat(['Employee.phonePrefix' => 'identifier', ' ', 'Employee.phoneNumber' => 'identifier']),
            'manager_name' => $query->func()->concat(['Manager.lastName' => 'identifier', ' ', 'Manager.firstName' => 'identifier'])
        ]);

        $employees = $this->paginate($query, ['sortableFields' => array_merge(['merged_phone_number', 'manager_name'], $this->Employee->getSchema()->columns())]);

        $this->set(compact('employees'));
    }

    /**
     * View method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employee = $this->Employee->get($id, contain: ['Manager']);

        $query = $this->Employee->find('all')->where(['Employee.parentId =' => $id]);
        $query->selectAlso([
            'merged_phone_number' => $query->func()->concat(['Employee.phonePrefix' => 'identifier', ' ', 'Employee.phoneNumber' => 'identifier'])
        ]);

        $employees = $this->paginate($query, ['sortableFields' => array_merge(['merged_phone_number'], $this->Employee->getSchema()->columns())]);

        $this->set(compact('employee', 'employees'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employee = $this->Employee->newEmptyEntity();
        if ($this->request->is('post')) {
            $employee = $this->Employee->patchEntity($employee, $this->request->getData());

            $ceo = $this->Employee->find('all')->where(['parentId is' => null])->first();
            if ($ceo !== null && $employee->parentId === null)
            {
                $this->Flash->error(__('The CEO is already set.'));
            } else {
                if ($this->Employee->save($employee)) {
                    $this->Flash->success(__('The employee has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
            }
        }
        $managers = $this->Employee->find('list')
            ->orderBy(['lastName', 'firstName'])
            ->all();

        $prefixes = $this->phonePrefixes();
        $this->set(compact('employee', 'prefixes', 'managers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employee = $this->Employee->get($id, contain: []);
        $subordinates = $this->Employee->find('children', for: $id)->select(['id']);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $isCeo = $employee->parentId === null;
            $employee = $this->Employee->patchEntity($employee, $this->request->getData());

            if ($employee->id === $employee->parentId){
                $this->Flash->error(__('The employee cannot be their own manager. Please, select the correct manager.'));
            } elseif ($isCeo && $employee->parentId !== null){
                $this->Flash->error(__('The CEO cannot be set as regular employee. Please, set the new CEO first.'));
            } elseif (!$isCeo && $employee->parentId === null){
                $this->Flash->error(__('Use Set CEO action to set employee as CEO.'));
            } elseif ($subordinates->cleanCopy()->where(['id =' => $employee->parentId])->first() !== null){
                $this->Flash->error(__('Incorrect manager selected.'));
            }
            else {
                if ($this->Employee->save($employee)) {
                    $this->Flash->success(__('The employee has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }

                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
            }
        }

        $managers = $this->Employee->find('list')
            ->where(['id <>' => $id])
            ->where(function (QueryExpression $exp, SelectQuery $q) use ($subordinates) {
                return $exp->notIn('id', $subordinates);
            })
            ->orderBy(['lastName', 'firstName'])
            ->all();

        $prefixes = $this->phonePrefixes();
        $this->set(compact('employee', 'managers', 'prefixes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employee = $this->Employee->get($id);

        $subordinates = $this->Employee->find()->where(['parentId =' => $id])->all();

        if ($subordinates->count() > 0){
            $this->Flash->error(__('The employee with {0} subordinates could not be deleted.', $subordinates->count()));
        } else
        {
            if ($this->Employee->delete($employee)) {
                $this->Flash->success(__('The employee has been deleted.'));
            } else {
                $this->Flash->error(__('The employee could not be deleted. Please, try again.'));
            }
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Set employee as CEO
     *
     * @param $id Employee id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function setceo($id = null){
        $this->request->allowMethod(['post', 'patch']);

        $employee = $this->Employee->get($id);

        if($employee->parentId === null){
            $this->Flash->error(__('The employee is already set as CEO.'));
        }

        $ceo = $this->Employee->find('all')->where(['parentId is' => null])->first();

        $ceo->parentId = $employee->id;
        $employee->parentId = null;

        try {
            $this->Employee->saveMany([$ceo, $employee]);
            $this->Flash->success(__('The employee {0} has been set as CEO.', $employee->full_name));
        } catch (\Exception $e) {
            $this->Flash->error(__('Saving changes failed. Please try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Organization chart
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function chart(){
        $query = $this->Employee->find('threaded',
            keyField: 'id',
            parentField: 'parentId');
        $chartData = $query->all();

        $this->set(compact('chartData'));
    }

    /**
     * Return array of defined phone prefixes for select
     *
     * @return array[]
     */
    private function phonePrefixes(): array
    {
        return [
            ['text' => '+420', 'value' => '+420'],
            ['text' => '+421', 'value' => '+421'],
            ['text' => '+1', 'value' => '+1']
        ];
    }
}
