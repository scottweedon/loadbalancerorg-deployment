<?php

class LBDeploymentACL {

    public $function;           // list | add | delete
    public $vip;                // <L7VIPNAME>                  
    public $pathtype;           // <path_beg|path_end|hdr_host|hdr_beg|query|src_blk>             
    public $path;               // <URI PATH>
    public $redirecttype;       // <url_loc|url_pre|backend|use_server>               
    public $location;           // <URL|BACKEND>
    public $bool;               // <equal|notequal>

    public function __construct($data = null, $template = false){

        if($data != null){
            
            $this->vip = $data->vip;
            $this->pathtype = $data->pathtype;
            $this->path = $data->path;
            $this->redirecttype = $data->redirecttype;
            $this->location = $data->location;
            $this->bool = $data->bool;

        }
    }

    public function add() {

        $this->function = "add";

        try {
            if( $this->vip != null ){

                $result = LBDeploymentCLI::execute("acl", $this);
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

                $result = LBDeploymentCLI::execute("acl", $this);
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