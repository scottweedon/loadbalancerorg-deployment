<?php

class LBDeploymentGSLB {

    public $globalnames;    //LBDeploymentGSLB_globalnames[]
    public $members;        //LBDeploymentGSLB_members[]          
    public $pools;          //LBDeploymentGSLB_pools[]
    public $topologies;     //LBDeploymentGSLB_topologies[]

    
    public function __construct($data = null, $template = false){

        $this->globalnames = array();
        $this->members = array();
        $this->pools = array();
        $this->topologies = array();

        if($data != null){
                        
            if(count($data->globalnames) > 0){
                foreach($data->globalnames as $object){
                    array_push($this->globalnames, new LBDeploymentGSLB_globalnames($object));
                }
            }

            if(count($data->members) > 0){
                foreach($data->members as $object){
                    array_push($this->members, new LBDeploymentGSLB_members($object));
                }
            }

            if(count($data->pools) > 0){
                foreach($data->pools as $object){
                    array_push($this->pools, new LBDeploymentGSLB_pools($object));
                }
            }

            if(count($data->topologies) > 0){
                foreach($data->topologies as $object){
                    array_push($this->topologies, new LBDeploymentGSLB_topologies($object));
                }
            }

        }
        
    }

    public function add() {

        try {

            if(count($this->globalnames) > 0){
                foreach($this->globalnames as $object){
                    $object->add();
                }
            }


            if(count($this->members) > 0){
                foreach($this->members as $object){
                    $object->add();
                }
            }

            if(count($this->pools) > 0){
                foreach($this->pools as $object){
                    $object->add();

                    // add all the members to this pool
                    foreach($this->members as $mobject){
                        $result = LBDeploymentCLI::execute_raw("gslb", "--section pools --function edit --name " . $object->name . " --add_member " . $mobject->name);
                    }
                }
            }

            if(count($this->topologies) > 0){
                foreach($this->topologies as $object){
                    $object->add();
                }
            }

        } catch (Excpetion $e) {


        }

    }
    
    public function edit() {

        if(count($this->globalnames) > 0){
            foreach($this->globalnames as $object){
                $object->edit();
            }
        }

        if(count($this->members) > 0){
            foreach($this->members as $object){
                $object->edit();
            }
        }

        if(count($this->pools) > 0){
            foreach($this->pools as $object){
                $object->edit();
            }
        }

        if(count($this->topologies) > 0){
            foreach($this->topologies as $object){
                $object->edit();
            }
        }

    }

    public function delete() {

        if(count($this->globalnames) > 0){
            foreach($this->globalnames as $object){
                $object->delete();
            }
        }

        if(count($this->members) > 0){
            foreach($this->members as $object){
                $object->delete();
            }
        }

        if(count($this->pools) > 0){
            foreach($this->pools as $object){
                $object->delete();
            }
        }

        if(count($this->topologies) > 0){
            foreach($this->topologies as $object){
                $object->delete();
            }
        }

    }
    

}


class LBDeploymentGSLB_globalnames {

    public $section;
    public $function;
    public $name;
    public $hostname;                   
    public $ttl;       

    public function __construct($data = null, $template = false){

        $this->section = "globalnames";

        // Set Defaults
        $this->ttl = 30;

        if($data != null){

            if(isset($data->name)) { $this->name = $data->name; }
            if(isset($data->hostname)) { $this->hostname = $data->hostname; }
            if(isset($data->ttl)) { $this->ttl = $data->ttl; }

        }

    }

    function add(){

        $this->function = "add";

        try {

            if( $this->name != null && $this->hostname != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
                switch($result){
                    case 0: return false; break;
                    case 1: return true; break;
                    case 2: $this->function = "edit"; return LBDeploymentCLI::execute("gslb", $this); break;
                    default: return false; break;
                }

            } else {

                return false;

            }

        } catch (Exception $e) {

            throw $e;

        }

    }

    function edit(){

        $this->function = "edit";

        try {
            if( $this->name != null && $this->hostname != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
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

    function delete(){

        $this->function = "delete";

        try {
            if( $this->name != null && $this->hostname != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
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


class LBDeploymentGSLB_members {

    public $section;
    public $function;
    public $name;
    public $ip;                   
    public $monitor_ip;
    public $weight;      

    public function __construct($data = null, $template = false){

        $this->section = "members";

        // Set Defaults
        $this->weight = 1;

        if($data != null){

            if(isset($data->name)) { $this->name = $data->name; }
            if(isset($data->ip)) { $this->ip = $data->ip; }
            if(isset($data->monitor_ip)) { $this->monitor_ip = $data->monitor_ip; }
            if(isset($data->weight)) { $this->weight = $data->weight; }

        }

    }

    function add(){

        $this->function = "add";

        try {
            if( $this->name != null && $this->ip != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
                switch($result){
                    case 0: return false; break;
                    case 1: return true; break;
                    case 2: $this->function = "edit"; return LBDeploymentCLI::execute("gslb", $this); break;
                }

            } else {

                return false;

            }
        } catch (Exception $e) {

            throw $e;

        }

    }

    function edit(){

        $this->function = "edit";

        try {
            if( $this->name != null && $this->hostname != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
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

    function delete(){

        $this->function = "delete";

        try {
            if( $this->name != null && $this->hostname != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
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


class LBDeploymentGSLB_pools {

    public $section;
    public $function;
    public $name;
    public $monitor;                    //<http|tcp|forced|external>            
    public $monitor_interval;           //<5000>
    public $monitor_hostname;
    public $monitor_timeout;
    public $monitor_retries;
    public $monitor_use_ssl;
    public $monitor_status;
    public $monitor_expected_codes; 
    public $monitor_send_string;
    public $monitor_url_path;
    public $monitor_port;
    public $monitor_match_response;
    public $monitor_script;
    public $monitor_parameters;
    public $monitor_result;
    public $lb_method; 
    public $add_globalname;  
    //public $add_member;
    public $delete_globalname;
    public $delete_member;
    public $fallback;
    public $max_addrs_returned;

    public function __construct($data = null, $template = false){

        $this->section = "pools";

        // Set Defaults
        //if(!isset($data->add_member)) {
            $this->monitor = "tcp";                   
            $this->monitor_interval = 10;
            $this->monitor_timeout = 5000;
            $this->monitor_retries = 2;
            $this->monitor_use_ssl = "no";
            $this->monitor_status = "up";
            $this->monitor_expected_codes = "200"; 
            $this->monitor_send_string = ""; 
            $this->monitor_url_path  = "/";
            $this->monitor_port = "80";
            $this->monitor_match_response = "";
            $this->monitor_script = "";
            $this->monitor_parameters = "";
            $this->monitor_result = "";
            $this->lb_method = "wrr"; 
            $this->fallback = "any";
            $this->max_addrs_returned = 1;
        //}

        if($data != null){

            if(isset($data->name)) { $this->name = $data->name; }
            if(isset($data->monitor)) { $this->monitor = $data->monitor; }                   
            if(isset($data->monitor_interval)) { $this->monitor_interval = $data->monitor_interval; }
            if(isset($data->monitor_hostname)) { $this->monitor_hostname = $data->monitor_hostname; }
            if(isset($data->monitor_timeout)) { $this->monitor_timeout = $data->monitor_timeout; }
            if(isset($data->monitor_retries)) { $this->monitor_retries = $data->monitor_retries; }
            if(isset($data->monitor_use_ssl)) { $this->monitor_use_ssl = $data->monitor_use_ssl; }
            if(isset($data->monitor_use_ssl)) { $this->monitor_status = $data->monitor_status; }
            if(isset($data->monitor_expected_codes)) { $this->monitor_expected_codes = $data->monitor_expected_codes; } 
            if(isset($data->monitor_send_string)) { $this->monitor_send_string = $data->monitor_send_string; } 
            if(isset($data->monitor_url_path)) { $this->monitor_url_path  = $data->monitor_url_path; }
            if(isset($data->monitor_port)) { $this->monitor_port = $data->monitor_port; }
            if(isset($data->monitor_match_response)) { $this->monitor_match_response = $data->monitor_match_response; }
            if(isset($data->monitor_script)) { $this->monitor_script = $data->monitor_script; }
            if(isset($data->monitor_parameters)) { $this->monitor_parameters = $data->monitor_parameters; }
            if(isset($data->monitor_result)) { $this->monitor_result = $data->monitor_result; }
            if(isset($data->lb_method)) { $this->lb_method = $data->lb_method; } 
            if(isset($data->add_globalname)) { $this->add_globalname = $data->add_globalname; }  
            //if(isset($data->add_member)) { $this->add_member = $data->add_member; }
            if(isset($data->delete_globalname)) { $this->delete_globalname = $data->delete_globalname; }
            if(isset($data->delete_member)) { $this->delete_member = $data->delete_member; }
            if(isset($data->fallback)) { $this->fallback = $data->fallback; }
            if(isset($data->max_addrs_returned)) { $this->max_addrs_returned = $data->max_addrs_returned; }

        }

    }

    function add(){

        $this->function = "add";

        try {
            if( $this->name != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
                switch($result){
                    case 0: return false; break;
                    case 1: return true; break;
                    case 2: $this->function = "edit"; return LBDeploymentCLI::execute("gslb", $this); break;
                }

            } else {

                return false;

            }
        } catch (Exception $e) {

            throw $e;

        }

    }

    function edit(){

        $this->function = "edit";

        try {
            if( $this->name != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
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

    function delete(){

        $this->function = "delete";

        try {
            if( $this->name != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
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


class LBDeploymentGSLB_topologies {

    public $section;
    public $function;
    public $name;
    public $add_ips; 
    public $delete_ips;       

    public function __construct($data = null, $template = false){

        $this->section = "topologies";

        // Set Defaults
        // - no defaults for topologies

        if($data != null){

            if(isset($data->name)) { $this->name = $data->name; }
            if(isset($data->add_ips)) { $this->add_ips = $data->add_ips; }
            if(isset($data->delete_ips)) { $this->delete_ips = $data->delete_ips; }

        }

    }

    function add(){

        $this->function = "add";

        try {
            if( $this->name != null && $this->add_ips != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
                switch($result){
                    case 0: return false; break;
                    case 1: return true; break;
                    case 2: $this->function = "edit"; return LBDeploymentCLI::execute("gslb", $this); break;
                }

            } else {

                return false;

            }
        } catch (Exception $e) {

            throw $e;

        }

    }

    function edit(){

        $this->function = "edit";

        try {
            if( $this->name != null && $this->add_ips != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
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

    function delete(){

        $this->function = "delete";

        try {
            if( $this->name != null && $this->add_ips != null ){

                $result = LBDeploymentCLI::execute("gslb", $this);
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