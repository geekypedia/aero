<?php


class Api {
    
    public $db;
    protected $postedData;
    
    public function __construct (){
        $this->db = $GLOBALS['DB'];
    }
    
    public function Execute(){
        $id = 0;
        if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
        }
        if(isset($_REQUEST['Id'])){
            $id = $_REQUEST['Id'];
        } 
        if(isset($_REQUEST['ID'])){
            $id = $_REQUEST['ID'];
        } 
        
        $isValid = $this->ValidateRequiredFields($id);

        if($isValid){
            switch($_SERVER['REQUEST_METHOD']){
                case 'GET':
                if($id > 0)
                {
                    if(method_exists($this, "Get")){
                        $results = $this->Get($id);
                        if($results){
                            $this->Success($results);
                        }
                        else{
                            $this->NotFound("Resource does not exits.");
                        }
                    }
                    else{
                        $this->MethodDoesNotExist();
                    }
                }
                else
                {
                    if(method_exists($this, "GetAll")){
                        $results = $this->GetAll();
                        if($results){
                            $this->Success($results);
                        }
                        else{
                            $this->NoContent();
                        }
                    }
                    else{
                        $this->MethodDoesNotExist();
                    }
                }
                break;
                
                case 'PUT':
                if(method_exists($this, "Put")){
                    $this->Success($this->Put());
                }
                else{
                    $this->MethodDoesNotExist();
                }
                break;
                
                case 'POST':
                if(method_exists($this, "Post")){
                    $this->Created($this->Post());
                }
                else{
                    $this->MethodDoesNotExist();
                }
                break;
                
                case 'DELETE':
                if(method_exists($this, "Delete")){
                    $deleted = $this->Delete($id);
                    if($deleted){
                        print json_encode($deleted);
                        $this->NoContent();
                    }
                    else{
                        $this->NotFound("Resource does not exits.");
                    }
                }
                else{
                    $this->MethodDoesNotExist();
                }
                break;
                
                default:
                break;
            }
        }
    }
    
    public function Posted($asArray = TRUE){
        if($this->postedData) {
            $postedData = $this->postedData;
        }
        else{
            $postedData = file_get_contents('php://input');
            $this->postedData = $postedData;
        }
        $postedObject = json_decode($postedData);
        if($asArray){
            $postedArray = array();
            if(is_object($postedObject) || is_array($postedObject)){
                   foreach ($postedObject as $key => $value) {
                   $postedArray[$key] = $value;
               }
            }
            $result = $postedArray;
        }
        else{
            $result = $postedObject;
        }
        return $result;
    }
    
    public function ValidateRequiredFields($id){
        $requiredParams = array();
        $bodyAvailable = FALSE;
        
        switch($_SERVER['REQUEST_METHOD']){
            case 'GET':
              if($id > 0){
                if(method_exists($this, "GetRequiredFields")){
                    $requiredParams = $this->GetRequiredFields();
                }
              }
              else{
                if(method_exists($this, "GetAllRequiredFields")){
                    $requiredParams = $this->GetAllRequiredFields();
                }
              }
            break;
            case 'PUT':
            if(method_exists($this, "PutRequiredFields")){
                $requiredParams = $this->PutRequiredFields();
                $bodyAvailable = TRUE;
            }
            break;
            case 'POST':
            if(method_exists($this, "PostRequiredFields")){
                $requiredParams = $this->PostRequiredFields();
                $bodyAvailable = TRUE;
            }
            break;
            case 'DELETE':
            if(method_exists($this, "DeleteRequiredFields")){
                $requiredParams = $this->GetRequiredFields();
            }
            break;
            default:
            break;
            
        }
      
        if(count($requiredParams) > 0){
            $invalidParams = $this->ValidateRequiredFieldsAgainstParams($requiredParams, $bodyAvailable);
            if(count($invalidParams) > 0){
                $err = $this->ErrorDump(400, "Required fields are missing.", $invalidParams);
                return FALSE;
            }
        }
        
        return TRUE;
    }
    
    public function ValidateRequiredFieldsAgainstParams($params, $bodyAvailable){
        $p = array();
        
        if($bodyAvailable){
            $p = $this->Posted();
        }
        else{
            $p = (array) $_REQUEST;
        }
      
        $r = array();
        
        foreach ($params as $key => $value) {
            if(!array_key_exists($value, $p)){
                array_push($r, $value);
            }
        }
        return $r;
    }
    
    public function __set($name, $val)
    {
        throw new Exception("Unknown property $name");
    }
    
    private function MethodDoesNotExist(){
        http_response_code(405);
    }
    
    public function Json($d){
        return json_encode($d);
    }
    
    public function Success($data){
        return $this->Normal(200, $data);
    }
    
    public function SuccessOnly(){
        http_response_code(200);
    }
    
    public function Created($data){
        return $this->Normal(201, $data);
    }
    
    public function CreatedOnly(){
        http_response_code(201);
    }
    
    public function NoContent(){
        http_response_code(204);
    }
    
    public function Normal($code, $data){
        http_response_code($code);
        $res = $this->Json($data);
        print $res;
        return $res;
    }
    
    public function Error($code, $message){
        http_response_code($code);
        $r = new stdClass();
        $r->error = new StdClass();
        $r->error->code = $code;
        $r->error->message = $message;
        $res = $this->Json($r);
        print $res;
        return $res;
    }
    
    public function ErrorDump($code, $message, $details){
        http_response_code($code);
        $r = new stdClass();
        $r->error = new StdClass();
        $r->error->code = $code;
        $r->error->message = $message;
        $r->error->details = $details;
        $res = $this->Json($r);
        print $res;
        return $res;
    }
    
    public function BadRequest($message){
        return $this->Error(400, $message);
    }
    
    public function NotFound($message){
        return $this->Error(404, $message);
    }
    
    public function InternalServerError($message){
        return $this->Error(500, $message);
    }
    
}

?>