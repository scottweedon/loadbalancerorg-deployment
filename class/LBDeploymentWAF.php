<?php

class LBDeploymentWAF {

    public $waf;                            //WAF name
    public $vip;                            //VIP name
    public $in_anom_score;                  //<1:100>
    public $out_anom_score;                  //<1:100>
    public $req_data;                        //<on:off>
    public $resp_data;                       //<on:off>
    public $audit;                           //<on:off>
    public $proxytimeout;
    public $dlogin;                          //<on:off>
    public $dlogin_mode;                     //<static:openid_google>
    public $dlogin_location;                 //</:/dir:/file.html>
    public $dlogin_static_username;          //<username>
    public $dlogin_static_password;          //<password>
    public $dlogin_google_clientid;          //<Google API Client ID>
    public $dlogin_google_clientsecret;      //<secret>
    public $dlogin_google_redirect_uri;      //<redirect uri>
    public $dlogin_google_passphrase;        //<passphrase>
    public $dlogin_google_allowed_domain;    //<example.com email domain>
    public $rule_engine;                     //<on:off>
    public $disable_waf;                     //<on|off>
    public $cacheaccel;                      //<on|off>
    public $cache_nocache_files;             //<file or regex>
    public $cache_force_cache;               //<on|off>
    public $cache_object_size;


    public function __construct($data = null, $template = false){

        if($data != null){
            
            $this->waf = str_replace(" ", "_", $data->waf);
            $this->vip = $data->vip;
            $this->in_anom_score = $data->in_anom_score;
            $this->out_anom_score = $data->out_anom_score; 
            $this->req_data = $data->req_data;
            $this->resp_data = $data->resp_data;
            $this->audit = $data->audit;
            $this->proxytimeout = $data->proxytimeout;
            $this->dlogin = $data->dlogin;
            $this->dlogin_mode = $data->dlogin_mode;
            $this->dlogin_location = $data->dlogin_location;
            $this->dlogin_static_username = $data->dlogin_static_username;
            $this->dlogin_static_password = $data->dlogin_static_password;
            $this->dlogin_google_clientid = $data->dlogin_google_clientid;
            $this->dlogin_google_clientsecret = $data->dlogin_google_clientsecret;
            $this->dlogin_google_redirect_uri = $data->dlogin_google_redirect_uri;
            $this->dlogin_google_passphrase = $data->dlogin_google_passphrase;
            $this->dlogin_google_allowed_domain = $data->dlogin_google_allowed_domain;
            $this->rule_engine = $data->rule_engine;
            $this->disable_waf = $data->disable_waf;
            $this->cacheaccel = $data->cacheaccel;
            $this->cache_nocache_files = $data->cache_nocache_files;
            $this->cache_force_cache = $data->cache_force_cache;
            $this->cache_object_size = $data->cache_object_size;

        }
    }

    
    public function add() {

        try {
            if( $this->vip != null && $this->waf != null ){

                $result = LBDeploymentCLI::execute("add-waf", $this);
                switch($result){
                    case 0: return false; break;
                    case 1: return true; break;
                    case 2: return LBDeploymentCLI::execute("edit-waf", $this); break;
                }

            } else {

                return false;

            }
        } catch (Exception $e) {

            throw $e;

        }

    }
    
    public function edit() {

        if( $this->waf != null ){

            $result = LBDeploymentCLI::execute("edit-waf", $this);
            switch($result){
                case 0: return false; break;
                case 1: return true; break;
                case 2: return false; break;
            }

        } else {

            return false;

        }

    }

    public function delete() {

        if( $this->vip != null && $this->waf != null ){

            $result = LBDeploymentCLI::execute("delete-waf", $this);
            switch($result){
                case 0: return false; break;
                case 1: return true; break;
                case 2: return false; break;
            }

        } else {

            return false;

        }

    }
    

}

?>