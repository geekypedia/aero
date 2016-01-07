#Aero
>It is a small footprint PHP API framework that turns your classes into full blown RESTful APIs.

####**INFO**
Version : 0.1.1  
Author : Om Talsania  
Email: [omtalsania@geekypedia.net](mailto:omtalsania@geekypedia.net)  
Website: [geekypedia.net](http://geekypedia.net)  
Blog: [blog.geekypedia.net](http://blog.geekypedia.net)  

####**DEPENDENCIES**
This framework loosely depends on the following 3rd party open source framework. The core API framework doesn't really need them, but using them will make a lot of things easier.
1. **Luracast Database** - It is a fork of the database component (Illuminate/Database) of Laravel framework. It is bundled just for the sake of ease of use. If you want to use your own version, you can simple replace the vendor folder.
2. **node-php-server** - This framework is bundled with the node-php-server. The only reason for doing so is that I am a NodeJS developer who wants to run the PHP local server right from IDE of my choice ([Visual Studio Code](https://code.visualstudio.com/)). You can remove it if you don't want it. It's in the node_modules folder.

####**DISCLAIMER**
I really appreciate the hardwork done by Luracast, Laravel and node-php-server team to create DB Connectivity Framework and Local PHP Server component for NodeJS. All files inside vendor and node_modules are just for your ease of use (download everything in one go) and all credit goes to their original creators. Everything apart from these 2 folders in this bundle is my own work and I claim its copyright.

##**Example**

>sample.php

```php
<?php
    require('crudapi.php');
    class SampleApi extends CrudApi{
        public $table = 'posts';
    }
    (new SampleApi())->Execute();
?>

```

The small piece of code written above will turn your php page into a full blown REST API.  
Say, this code is running at localhost:8000, then you can use the following APIs.  
**GET / localhost:8000/sample.php** - Returns list of records from _posts_ table.  
**GET / localhost:8000/sample.php?id=1** - Returns record with id=1 from _posts_ table.  
**DELETE / localhost:8000/sample.php?id=1** - Deletes record with id=1 from _posts_ table.  
**POST / localhost:8000/sample.php** - Inserts a new records from the payload into _posts_ table.  
**PUT / localhost:8000/sample.php** - Update a record from _posts_ table using the payload supplier.  

The database connection has to be specifid in **_config.php_**. Just replace the following line with your own.  
```php
$db = Db::Create('localhost','root','','testdb');
```

The API assumes that each table will have a field **_id_** (INT, AUTO INCREMENT) as the primary key.  
The API will also have timestamp auditing **ON** by default. So POST and PUT operations will affect the following columns in the table.  
```sql
   created_at datetime
   created_by VARCHAR(50)
   updated_at datetime
   updated_by VARCHAR(50)   
``` 

You can use the following snippet to create the table used in this sample.

```sql
CREATE TABLE posts 
(
  id            INT NOT NULL AUTO_INCREMENT,
  title         VARCHAR(50),
  description   VARCHAR(50),
  created_at    datetime,
  created_by    VARCHAR(50),
  updated_at    datetime,
  updated_by    VARCHAR(50),
  PRIMARY KEY (id)
);
```

###Sample Payload

>POST  

```json
{
    "title" : "hello world",
    "description" : "this is a sample post"
}
```

>PUT  

```json
{
    "id" : 1,
    "title" : "hello again",
    "description" : "this is a modified post"
}
```

###Validations

Adding the following code in your **SampleApi** class will provide OOTB validations for required fields.

```php
    public function PostRequiredFields(){
        return array("title", "description");
    }

    public function PutRequiredFields(){
        return array("id", "title", "description");
    }
```

You can check the sample.php file provided along with this distribution for implementation details.

Copyright &copy; 2015, [geekypedia.net](http://geekypedia.net)
