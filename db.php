<?php
require('vendor/autoload.php');

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Db{
    public $Capsule;
    
    public function CreateCapsule($driver, $host, $username, $password, $database){
        $this->Capsule = new Capsule;
        
        $this->Capsule->addConnection([
        'driver'    => $driver,
        'host'      => $host,
        'database'  => $database,
        'username'  => $username,
        'password'  => $password,
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
        ]);
        
        // Set the event dispatcher used by Eloquent models... (optional)
        $this->Capsule->setEventDispatcher(new Dispatcher(new Container));

        // Make this Capsule instance available globally via static methods... (optional)
        $this->Capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $this->Capsule->bootEloquent();
    }
    
    public function CreateMyCapsule($host, $username, $password, $database){
        $driver = 'mysql';
        $this->CreateCapsule($driver, $host, $username, $password, $database);
    }

    public static function Create($host, $username, $password, $database){
        $db = new Db();
        $db->CreateMyCapsule($host, $username, $password, $database);
        return $db;
    }
}

?>