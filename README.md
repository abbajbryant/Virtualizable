CakePHP 1.3 VirtualizableBehavior
=

Installation
-

Clone or download the plugin into one of your plugin paths (app/plugins by default).

Model Configuration
-
Using an example Model (app/models/user.php). This User Model has a first_name and last_name varchar fields.

```php

    class User extends AppModel
    {
        public $name = "User";
    
        public $actsAs = array(
            'Virtualizable' => array(
                'virtualFields' => array(
                    'name' => 'CONCAT( User.first_name, " ", User.last_name )',
                    'otherField' => 'CONCAT( User.first_name, "-", User.id )',
                ),
            ),
        );
        //...
    }
```

Attach the Behavior to the actsAs property. The one available option is an array of virtualFields in the same format as the Model's virtualFields property.

Using the Behavior with Model::find
-

The behavior allows you to decide which virtualFields to use (if any) in a find operation. The basic virtualField implemenation in the core requires you to define the fields property and specify all the fields you want if you wish to return results with only a subset of the available virtualField's for the Model.

Using this behavior we have access to a 'virtualFields' key in the Model::find options array. This key can be either a string (a virtualField array key corresponding to the virtualField definition) or an array (of virtualField keys) or a boolen (true to return all virtualFields or false to return no virtualFields).

```php

    class UsersController extends AppController
    {
        public $name = "Users";

        public function index( ){
            debug( $this->User->find( 'all', array(
            )));

            debug( $this->User->find( 'all', array(
                'fields' => array( 'first_name' ),
            )));

            debug( $this->User->find( 'all', array(
                'virtualFields' => false,
            )));

            debug( $this->User->find( 'all', array(
                'virtualFields' => true,
            )));

            debug( $this->User->find( 'all', array(
                'virtualFields' => 'name',
            )));

            debug( $this->User->find( 'all', array(
                'virtualFields' => array( 'name' ),
            )));

            debug( $this->User->find( 'all', array(
                'fields' => array( 'id', 'slug' ),
                'virtualFields' => array( 'name', 'otherField' ),
            )));            
        }
    }
```