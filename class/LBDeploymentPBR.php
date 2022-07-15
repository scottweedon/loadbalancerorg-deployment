<?php

class LBDeploymentPBR {

    public $function;
    public $ip;
    public $gateway;

    public function __construct($data = null, $template = false){

        if($data != null){
            
            $this->ip = $data->ip;
            $this->gateway = $data->gateway;

        }
    }
    
    public function add() {

        $this->function = "set";

        try {
            if( $this->ip != null && $this->gateway != null ){

                $result = LBDeploymentCLI::execute("pbr", $this);
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

        $this->function = "set";

        try {
            if( $this->ip != null && $this->gateway != null ){

                $result = LBDeploymentCLI::execute("pbr", $this);
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

        $this->function = "delete";

        try {
            if( $this->ip != null && $this->gateway != null ){

                $result = LBDeploymentCLI::execute("pbr", $this);
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