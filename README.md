# Employee's subordination demo

 with [CakePHP](https://cakephp.org) 5.x.

## Description

Employee's subordination demo is a PHP application using CakePHP framework
for managing an employee org chart within an organization.

Functions:
- Employee evidence with one CEO and each employee has exactly one manager and any number of subordinates
- Database structure is defined by migration
- All CRUD operations are accessible from the frontend
- Required data for new employee are first name, last name, email and manager (except for CEO)
- If one of Phone prefix or Phone number is set, second item is mandatory
- Phone number has basic validation on frontend in JavaScript. The number has different validations depending on the telephone prefix.
- The setting of the manager in Add and Edit is verified to avoid a cycle
- View page for employee contains table with their direct subordinates
- CEO could be set by frontend action `Set CEO`
- Button `Org chart` shows organization chart tree

## License

This demo is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
