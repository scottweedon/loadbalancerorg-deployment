<?php

class LBDeploymentCERT {

    
    public $type;
    public $function;                  

    public $csrname;                                                
    public $city;                       
    public $province;                 
    public $country;               
    public $organisation;                    
    public $unit;                   
    public $domain;           
    public $email;
    public $csrsize;
    public $signalgorithm;
    public $days; 
    public $encodedcertificate;                   

    public function __construct($data = null, $template = false){

        $this->type = "certificate";

        if($data != null){

            if(isset($data->csrname)) { $this->csrname = $data->csrname; }
            if(isset($data->city)) { $this->city = $data->city; }
            if(isset($data->province)) { $this->province = $data->province; }
            if(isset($data->country)) { $this->country = $data->country; }
            if(isset($data->organisation)) { $this->organisation = $data->organisation; }
            if(isset($data->unit)) { $this->unit = $data->unit; }
            if(isset($data->domain)) { $this->domain = $data->domain; }
            if(isset($data->email)) { $this->email = $data->email; }
            if(isset($data->csrsize)) { $this->csrsize = $data->csrsize; }
            if(isset($data->signalgorithm)) { $this->signalgorithm = $data->signalgorithm; }
            if(isset($data->days)) { $this->days = $data->days; }
            if(isset($data->encodedcertificate)) { $this->encodedcertificate = $data->encodedcertificate; }

        }
    }
    
    
    public function add() {

        $this->function = "csr";

        try {
            if( $this->csrname != null ){

                $result = LBDeploymentCLI::execute("termination", $this);
                switch($result){
                    case 0: return false; break;
                    case 1: return true; break;
                    case 2: return false; break;
                }

            } else {

                return false;

            }
        } catch (Exception $e) {

            throw $e;

        }

    }


}

?>