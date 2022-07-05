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
        $this->vip = $vip->name;
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

        $output=null;
        $retval=null;

        $command = "/usr/local/sbin/lbcli --action add-rip";
        
        foreach ($this as $key => $value) {
            if($value != null){
                $command .= " --$key $value";
            }
        }

        exec($command, $output, $retval);
        //var_dump($output); 

        if(strpos($output[1], '"status":') != 0){

            // test if this VS already exisits, if it does edit the VS rather than add it. 
            if(json_decode($output[1])->lbcli[0]->status == "exists"){
            
                return $this->edit();
            
            } elseif(json_decode($output[1])->lbcli[0]->status == "success"){
            
                return true;

            } else {

                // something failed, throw the error
                //throw new Exception($output);

            }
 
        } else {

            // something failed, throw the error
            //throw new Exception($output);

        }


    }

    public function edit() {

        $output=null;
        $retval=null;

        $command = "/usr/local/sbin/lbcli --action edit-rip";
        
        foreach ($this as $key => $value) {
            if($value != null){
                $command .= " --$key $value";
            }
        }

        exec($command, $output, $retval); 
        //var_dump($output);

        if(strpos($output[1], '"status":') != 0){

            if(json_decode($output[1])->lbcli[0]->status == "success"){
            
                return true;

            } else {

                // something failed, throw the error
                //throw new Exception($output);

            }
 
        } else {

            // something failed, throw the error
            //throw new Exception($output);

        }


    }

}

?>