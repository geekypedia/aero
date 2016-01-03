<?php
require('crudapi.php');

//display_errors();

class SampleApi extends CrudApi{
    
    public $table = 'posts';
    //public $timestamps = FALSE;
    public $user = 'Om Talsania';
    
    public function PostRequiredFields(){
        return array("title", "description");
    }

    public function PutRequiredFields(){
        return array("id", "title", "description");
    }
    
}

(new SampleApi())->Execute();

?>
