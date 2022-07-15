<?php

class LBDeploymentSSL {

    
    public $type;
    public $function;                   // < add | edit | delete >
    public $vip;                        // <VIPNAME>
    public $ip;                         // <IP ADDRESS>
    public $port;                       // <PORT>
    public $backend_ip;                 // <BACKEND IP>
    public $backend_port;               // <BACKEND PORT>
    public $sslcert;                    // <SSLCERTNAME>
    public $sslmode;                    // <high|fips|compatable|custom>
    public $haproxy_ssl_link;           // (This is a combination of VIP_Name^VIP^PORT or custom)
    public $ciphers;                    
    public $disablesslv2;
    public $disablesslv3;
    public $disabletlsv1;
    public $stunneldnsdelay;
    public $stunnelproxy;
    public $servercipherorder;
    public $emptyfragments;
    public $stunnelrenegotiation;
    public $proxy_bind;
    public $slave_ip;
    public $disabletlsv1_1;
    public $disabletlsv1_2;
    public $disabletlsv1_3;
    

    public function __construct($data = null, $template = false){

        $this->type = "stunnel";

        if($data != null){

            if(isset($data->vip)) { $this->vip = $data->vip; }
            if(isset($data->ip)) { $this->ip = $data->ip; }
            if(isset($data->port)) { $this->port = $data->port; }
            if(isset($data->backend_ip)) { $this->backend_ip = $data->backend_ip; }
            if(isset($data->backend_port)) { $this->backend_port = $data->backend_port; }
            if(isset($data->sslcert)) { $this->sslcert = $data->sslcert; }
            if(isset($data->sslmode)) { $this->sslmode = $data->sslmode; }
            if(isset($data->haproxy_ssl_link)) { $this->haproxy_ssl_link = $data->haproxy_ssl_link; }
            if(isset($data->ciphers)) { $this->ciphers = $data->ciphers; }
            if(isset($data->disablesslv2)) { $this->disablesslv2 = $data->disablesslv2; }
            if(isset($data->disablesslv3)) { $this->disablesslv3 = $data->disablesslv3; }
            if(isset($data->disabletlsv1)) { $this->disabletlsv1 = $data->disabletlsv1; }
            if(isset($data->stunneldnsdelay)) { $this->stunneldnsdelay = $data->stunneldnsdelay; }
            if(isset($data->stunnelproxy)) { $this->stunnelproxy = $data->stunnelproxy; }
            if(isset($data->servercipherorder)) { $this->servercipherorder = $data->servercipherorder; }
            if(isset($data->emptyfragments)) { $this->emptyfragments = $data->emptyfragments; }
            if(isset($data->stunnelrenegotiation)) { $this->stunnelrenegotiation = $data->stunnelrenegotiation; }
            if(isset($data->proxy_bind)) { $this->proxy_bind = $data->proxy_bind; }
            if(isset($data->slave_ip)) { $this->slave_ip = $data->slave_ip; }
            if(isset($data->disabletlsv1_1)) { $this->disabletlsv1_1 = $data->disabletlsv1_1; }
            if(isset($data->disabletlsv1_2)) { $this->disabletlsv1_2 = $data->disabletlsv1_2; }
            if(isset($data->disabletlsv1_3)) { $this->disabletlsv1_3 = $data->disabletlsv1_3; }

        }
    }
    
    
    public function add() {

        $this->function = "add";

        try {
            if( $this->vip != null && $this->ip != null ){

                $result = LBDeploymentCLI::execute("termination", $this);
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

        $this->function = "edit";

        try {
            if( $this->vip != null && $this->ip != null ){

                $result = LBDeploymentCLI::execute("termination", $this);
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

                $result = LBDeploymentCLI::execute("termination", $this);
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