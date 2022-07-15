<?php

include("LBDeploymentCLI.php");
include("LBDeploymentConfig.php");
include("LBDeploymentParameter.php");

class LBDeployment {

    public $method;
    public $parameters;
    public $config;          

    public function __construct($data = null, $template = false){

        $this->method = "manual";

        if($template){
        
            $this->parameters = array(new LBDeploymentParameter(null, $template));
            $this->config = new LBDeploymentConfig(null, $template);
        
        } else {

            if($data != null){
                
                $this->method = $data->method;
                
                $this->parameters = array();
                foreach($data->parameters as $parameter){ 
                    array_push($this->parameters, new LBDeploymentParameter($parameter)); 
                }

                $this->config = new LBDeploymentConfig($data->config, false);

            } else {

                $this->parameters = array();
                $this->config = new LBDeploymentConfig(null);
            }

        }

        

    }

    public static function logger($logline) {

        $logfile = "/etc/loadbalancer.org/deploy.log";
        $log = fopen($logfile, "a+") or die("Unable to open file!");
        fwrite($log, date("Y/m/d h:i:s") . " - " . $logline . "\r\n");

    }

    public function apply(){

        $this->config->apply();
        
    }

}

?>