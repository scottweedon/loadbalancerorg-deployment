<?php

include("LBDeploymentRIP.php");
include("LBDeploymentVIP.php");

class LBDeploymentConfig {

    public $L7_vips;         // Layer 7 VIPs - Array of L7DeploymentVIP
    public $L4_vips;         // Layer 4 VIPs - Array of L4DeploymentVIP

    public function __construct($data = null, $template = false){

        $this->L7_vips = array();
        $this->L4_vips = array();

        // Echo out an empty config
        if($template && $data == null){
            array_push($this->L7_vips, new L7LBDeploymentVIP());
            array_push($this->L4_vips, new L4LBDeploymentVIP());
        }

        if($data != null){

            foreach($data->L7_vips as $vip){
                array_push($this->L7_vips, new L7LBDeploymentVIP($vip, $template));
            }

            foreach($data->L4_vips as $vip){
                array_push($this->L4_vips, new L4LBDeploymentVIP($vip, $template));
            }

        }

    }

    public function apply() {

        try {

            $reload_haproxy = false;
            $reload_lvs = false;

            foreach($this->L7_vips as $vip){
                $vip->add();
                $reload_haproxy = true;
            }

            foreach($this->L4_vips as $vip){
                $vip->add();
                $reload_lvs = true;
            }

            if($reload_haproxy){

                $command = "/usr/local/sbin/lbcli --action reload-haproxy";
                exec($command, $output, $retval); 

            }

        } catch (Exception $e){

            throw $e;
            
        }

    }

}

?>