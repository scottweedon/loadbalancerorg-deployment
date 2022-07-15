<?php

include("LBDeploymentRIP.php");
include("LBDeploymentVIP.php");
include("LBDeploymentACL.php");
include("LBDeploymentHEADER.php");
include("LBDeploymentWAF.php");
include("LBDeploymentGSLB.php");
include("LBDeploymentService.php");
include("LBDeploymentPBR.php");
include("LBDeploymentFIP.php");
include("LBDeploymentCERT.php");
include("LBDeploymentSSL.php");
include("LBDeploymentSNI.php");


class LBDeploymentConfig {

    public $L7_vips;         // Layer 7 VIPs - Array of L7DeploymentVIP
    public $L4_vips;         // Layer 4 VIPs - Array of L4DeploymentVIP
    public $wafs;            // Array of LBDeploymentWAF
    public $gslb;            // Array of LBDeploymentGSLB
    public $service;         // Array of LBDeploymentService
    public $pbr;             // Array of LBDeploymentPBR
    public $fip;             // Array of LBDeploymentFIP
    public $header;          // Array of LBDeploymentHEADER
    public $ssl;             // Array of LBDeploymentSSL
    public $sni;             // Array of LBDeploymentSNI
    public $cert;            // Array of LBDeploymentCERT

    public function __construct($data = null, $template = false){

        $this->L7_vips = array();
        $this->L4_vips = array();
        $this->acl = array();
        $this->header = array();
        $this->wafs = array();
        $this->gslb = array();
        $this->service = array();
        $this->pbr = array();
        $this->fip = array();
        $this->ssl = array();
        $this->sni = array();
        $this->cert = array();

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

            foreach($data->acl as $acl){
                array_push($this->acl, new LBDeploymentACL($acl));
            }

            foreach($data->header as $header){
                array_push($this->header, new LBDeploymentHEADER($header));
            }

            foreach($data->wafs as $waf){
                array_push($this->wafs, new LBDeploymentWAF($waf));
            }

            foreach($data->gslb as $gslb){
                array_push($this->gslb, new LBDeploymentGSLB($gslb));
            }

            foreach($data->service as $service){
                array_push($this->service, new LBDeploymentService($service));
            }

            foreach($data->pbr as $pbr){
                array_push($this->pbr, new LBDeploymentPBR($pbr));
            }

            foreach($data->fip as $fip){
                array_push($this->pbr, new LBDeploymentFIP($fip));
            }

            foreach($data->cert as $cert){
                array_push($this->cert, new LBDeploymentCERT($cert));
            }

            foreach($data->ssl as $ssl){
                array_push($this->ssl, new LBDeploymentSSL($ssl));
            }

            foreach($data->sni as $sni){
                array_push($this->sni, new LBDeploymentSNI($sni));
            }

        }

    }

    public function apply() {

        try {

            foreach($this->L7_vips as $vip){
                
                $vip->add();
                
                foreach($vip->rips as $rip){
                    $rip->add();
                }
                
            }

            foreach($this->L4_vips as $vip){
                
                $vip->add();
                
                foreach($vip->rips as $rip){
                    $rip->add();
                }
                
            }

            foreach($this->acl as $acl){ $acl->add(); }
            foreach($this->wafs as $waf){ $waf->add(); }
            foreach($this->gslb as $gslb){ $gslb->add(); }
            foreach($this->service as $service){ $service->execute(); }
            foreach($this->pbr as $pbr){ $pbr->add(); }
            foreach($this->fip as $fip){ $fip->add(); }
            foreach($this->cert as $cert){ $cert->add(); }
            foreach($this->ssl as $ssl){ $ssl->add(); }
            foreach($this->sni as $sni){ $sni->add(); }

        } catch (Exception $e) {

            throw $e;
            
        }

    }

}

?>