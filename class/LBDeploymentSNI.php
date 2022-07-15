<?php

class LBDeploymentSNI {

    
    public $type;
    public $function;                  
    public $sni;

    public $vip;                                                
    public $sni_name;                       
    public $sni_rule;                 
    public $sni_cert;               
    public $sni_backend_proxyprotocol;                    
    public $sni_backend_service;                   
    public $sni_backend_ip;           
    public $sni_backend_port;                    

    public function __construct($data = null, $template = false){

        $this->type = "stunnel";
        $this->function = "edit";

        if($data != null){

            if(isset($data->vip)) { $this->vip = $data->vip; }
            if(isset($data->sni_name)) { $this->sni_name = $data->sni_name; }
            if(isset($data->sni_rule)) { $this->sni_rule = $data->sni_rule; }
            if(isset($data->sni_cert)) { $this->sni_cert = $data->sni_cert; }
            if(isset($data->sni_backend_proxyprotocol)) { $this->sni_backend_proxyprotocol = $data->sni_backend_proxyprotocol; }
            if(isset($data->sni_backend_service)) { $this->sni_backend_service = $data->sni_backend_service; }
            if(isset($data->sni_backend_ip)) { $this->sni_backend_ip = $data->sni_backend_ip; }
            if(isset($data->sni_backend_port)) { $this->sni_backend_port = $data->sni_backend_port; }

        }
    }
    
    
    public function add() {

        $this->sni = "add";

        try {
            if( $this->vip != null ){

                $result = LBDeploymentCLI::execute("termination", $this);
                switch($result){
                    case 0: return false; break;
                    case 1: return true; break;
                    case 2: $this->edit(); break;
                }

            } else {

                return false;

            }
        } catch (Exception $e) {

            throw $e;

        }

    }
    
    public function edit() {

        $this->sni = "edit";

        try {
            if( $this->vip != null ){

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

    public function delete() {

        $this->sni = "delete";

        try {
            if( $this->vip != null ){

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