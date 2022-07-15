<?php

class LBDeploymentRIP {

    public $vip;                             //<VIP Name>
    public $rip;                             //<RIP Name>
    public $ip;                              //<RIP IP Address>
    public $weight;                          //<Weight value>
    public $port;                            //<Port value>
    public $minconns;                        //<minconns>
    public $maxconns;                        //<maxconns>
    public $encrypted;                       //<on|off>

    function __construct($vip, $rip = null){

        // set defaults
        $this->vip = $vip->vip;
        $this->port = null;
        $this->weight = 100;
        $this->minconns = 0;
        $this->maxconns = 0;
        $this->encrypted = null;

        if($vip->layer == 7){ $this->encrypted = "off"; } 

        if($rip != null){
            
            if(isset($rip->vip) && $rip->vip != null){ $this->vip = $rip->vip; }
            if(isset($rip->rip) && $rip->rip != null){ $this->rip = $rip->rip; }
            if(isset($rip->ip) && $rip->ip != null){ $this->ip = $rip->ip; }
            if(isset($rip->port) && $rip->port != null){ $this->port = $rip->port; }
            if(isset($rip->weight) && $rip->weight != null){ $this->weight = $rip->weight; }
            if(isset($rip->minconns) && $rip->minconns != null){ $this->minconns = $rip->minconns; }
            if(isset($rip->maxconns) && $rip->maxconns != null){ $this->maxconns = $rip->maxconns; }
            if(isset($rip->encrypted) && $rip->encrypted != null){ $this->encrypted = $rip->encrypted; }        

        } 

    }

    public function add() {


        try {
            if( $this->vip != null ){

                $result = LBDeploymentCLI::execute("add-rip", $this);
                switch($result){
                    case 0: return false; break;
                    case 1: return true; break;
                    case 2: return LBDeploymentCLI::execute("edit-rip", $this); break;
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
            if( $this->vip != null ){

                $result = LBDeploymentCLI::execute("edit-rip", $this);
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