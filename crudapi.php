<?php

require('config.php');

class CrudApi extends Api{
    
    public $table;
    public $user = 'Anonymous';
    public $timestamps = TRUE;
    
    
    public function context(){
        return $this->db->table($this->table);
    }
    
    public function GetAll(){
       $r = $this->context()->get(); 
       return $r;
    }

    public function Get($id){
        $r = $this->context()->find($id);
        return $r;
    }

    public function Post(){
        $posted = $this->Posted();
        if($this->timestamps){
            $now = date("Y-m-d H:i:s");
            $user = $this->user;
            $posted['created_at'] = $now;
            $posted['created_by'] = $user;
            $posted['updated_at'] = $now;
            $posted['updated_by'] = $user;
            
        }
        $id = $this->context()->insertGetId($posted);
        return $id;
    }

    public function Put(){
        $posted = $this->Posted();
        $r = $this->context()->where('id',$posted['id']);
        if($r){
                if($this->timestamps){
                    $now = date("Y-m-d H:i:s");
                    $user = $this->user;
                    $posted['updated_at'] = $now;
                    $posted['updated_by'] = $user;
                }
                $r->update($posted);
                $updated = $this->context()->find($posted['id']);
                return $updated;
        }
    }

    public function Delete($id){
        $r = $this->context()->find($id);
        if($r) $this->context()->delete($id);
        return $r;
    }
    
}

?>
