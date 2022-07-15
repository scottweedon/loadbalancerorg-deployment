<?php

class LBDeploymentFIP {

    public $ip;

    public function __construct($data = null, $template = false){

        if($data != null){
            
            $this->ip = $data->ip;

        }
    }
    
    public function add() {

        try {
            if( $this->ip != null ){

                $result = LBDeploymentCLI::execute("add-floating-ip", $this);
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

        try {
            if( $this->ip != null ){

                $result = LBDeploymentCLI::execute("fix-floating-ip", $this);
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

        try {
            if( $this->ip != null ){

                $result = LBDeploymentCLI::execute("delete-floating-ip", $this);
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