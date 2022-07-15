<?php

include("LBDeploymentRIP.php");
include("LBDeploymentVIP.php");
include("LBDeploymentWAF.php");
include("LBDeploymentGSLB.php");

class LBDeploymentConfig {

    public $L7_vips;         // Layer 7 VIPs - Array of L7DeploymentVIP
    public $L4_vips;         // Layer 4 VIPs - Array of L4DeploymentVIP
    public $wafs;            // Array of LBDeploymentWAF
    public $gslb;            // Array of LBDeploymentGSLB

    public function __construct($data = null, $template = false){

        $this->L7_vips = array();
        $this->L4_vips = array();
        $this->wafs = array();
        $this->gslb = array();

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

            foreach($data->wafs as $waf){
                array_push($this->wafs, new LBDeploymentWAF($waf));
            }

            foreach($data->gslb as $gslb){
                array_push($this->gslb, new LBDeploymentGSLB($gslb));
            }

        }

    }

    public function apply() {

        try {

            $reload_haproxy = false;
            $reload_lvs = false;

            foreach($this->L7_vips as $vip){
                $vip->add();
                foreach($vip->rips as $rip){
                    $rip->add();
                }
                $reload_haproxy = true;
            }

            foreach($this->L4_vips as $vip){
                $vip->add();
                foreach($vip->rips as $rip){
                    $rip->add();
                }
                $reload_lvs = true;
            }

            foreach($this->wafs as $waf){

                try {

                    $waf->add();

                } catch (Exception $e) {

                    var_dump($e);

                }
            }

            foreach($this->gslb as $gslb){

                try {

                    $gslb->add();

                } catch (Exception $e) {

                    var_dump($e);

                }
            }

            if($reload_haproxy){

                $command = "/usr/local/sbin/lbcli --action reload-haproxy";
                exec($command, $output, $retval); 

            }

        } catch (Exception $e) {

            throw $e;
            
        }

    }

}

?>