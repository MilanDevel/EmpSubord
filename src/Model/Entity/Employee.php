<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Employee Entity
 *
 * @property int $id
 * @property string|null $firstName
 * @property string|null $lastName
 * @property string|null $phonePrefix
 * @property string|null $phoneNumber
 * @property string|null $email
 * @property int|null $parentId
 * @property Employee|null $manager
 * @property string|null $full_name
 */
class Employee extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'firstName' => true,
        'lastName' => true,
        'phonePrefix' => true,
        'phoneNumber' => true,
        'email' => true,
        'parentId' => true,
    ];

    /**
     * Return employee's full name in "<Last name> <First name>" format
     *
     * @return string
     */
    protected function _getFullName()
    {
        return $this->lastName . ' ' . $this->firstName;
    }

}
