<?php

class LBDeploymentService {

    public $action;                             // reload | restart
    public $service;                            // ldirectord | haproxy | heartbeat | pound | stunnel | collectd | firewall | syslog | snmp | waf | autoscaling | azha | apache 

    public function __construct($data = null, $template = false){

        if($data != null){
            
            $this->action = $data->action;
            $this->service = $data->service;

        }
    }
    
    public function execute() {

        try {
            if( $this->action != null && $this->service != null ){

                $result = LBDeploymentCLI::execute_raw($this->action . "-" . $this->service, "");
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