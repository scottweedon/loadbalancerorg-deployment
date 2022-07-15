<?php

class LBDeploymentHEADER {

    public $function;          // list | add | delete
    public $vip;               // <VIP Name>
    public $header_type;       // <http-request|http-response>           
    public $header_option;     // <add|set|del|replace>
    public $header_name;       // <X-Custom-Header>               
    public $header_value;      // <X-Custom-Value>

    public function __construct($data = null, $template = false){

        if($data != null){
            
            $this->vip = $data->vip;
            $this->header_type = $data->header_type;
            $this->header_option = $data->header_option;
            $this->header_name = $data->header_name;
            $this->header_value = $data->header_value;

        }
    }

    public function add() {

        $this->function = "add";

        try {
            if( $this->vip != null ){

                $result = LBDeploymentCLI::execute("headers", $this);
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
            if( $this->vip != null ){

                $result = LBDeploymentCLI::execute("delete", $this);
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