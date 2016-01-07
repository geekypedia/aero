<?php
require('crudapi.php');

//Uncomment the following line if you want to debug the api by displaying unhandled errors in the result.
//display_errors();

class SampleApi extends CrudApi{
    
    //This is table to which the default implementation of the CRUD APIs is mapped
    public $table = 'posts';
    
    //Uncomment the following line to disable automatic auditing - i.e. the entry in created_at, created_by, updated_at, updated_by fields of your table on POST and PUT operations
    //public $timestamps = FALSE;
    
    //Set this variable for entry in created_by and updated_by fields during automatic timestamp audits. If you don't set, the default 'Anonymous' will be used.
    public $user = 'Om Talsania';
    
    //Perform required fields validations during POST
    public function PostRequiredFields(){
        return array("title", "description");
    }

    //Perform required fields validations during PUT
    public function PutRequiredFields(){
        return array("id", "title", "description");
    }
    
    //You can also provide validations for your GET and DELETE operatioins, if they require more fields than 'id'. Just add GetAllRequiredFields, GetRequiredFields and DeleteRequiredFields methods.
    
}

(new SampleApi())->Execute();

?>
