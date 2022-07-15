<?php

class LBDeploymentVIP {

    public $vip;                            // VIP name
    public $ip;                              //<IP Address of VIP>
    public $ports;                           //(Ports can be 80 80:81 or 800-900 or 80:90-100:3443 as a mix of port:separated:values and also port-ranges values)
    public $rips;                            // Array of LBDeploymentRIP
    public $layer;                           // Network Layer <4|7>

    public function __construct($data = null, $template = false){

        if($data != null){
            
            $this->vip = $data->vip;
            $this->ip = $data->ip;
            $this->ports = $data->ports;
            
            $this->rips = array();
            foreach($data->rips as $rip){
                $rip = new LBDeploymentRIP($this, $rip);
                array_push($this->rips, $rip);
            }
            
        }
    }

    public function add() {

        try {
            if( $this->vip != null ){

                $result = LBDeploymentCLI::execute("add-vip", $this);
                switch($result){
                    case 0: return false; break;
                    case 1: return true; break;
                    case 2: return LBDeploymentCLI::execute("edit-vip", $this); break;
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

                $result = LBDeploymentCLI::execute("edit-vip", $this);
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

    }

}

class L4LBDeploymentVIP extends LBDeploymentVIP {

    public $protocol;                        //<tcp:udp:ops:fwm> # We do not support manual firewall marks where IP = FWM Number as you need to manually add the firewall rules
    public $forwarding;                      //<gate:masq:ipip> Gate = L4 DR masq=L4 NAT ipip=TUN Mode
    public $granularity;                     //<255.255.255.255> This is the subnet or single ip range for persistence
    public $fallback_ip;                     //<127.0.0.1> This is the fallback server IP Address, It may be an external IPAddress
    public $fallback_port;                   //<9081> This is the fallback server port, it may be the port of an external webserver.    
    public $fallback_local;                  //<on:off> MASQ Fallback. Allows fallback server port to be different to that of the real server.
    public $persistent;                      //<on:off> Are we a persistent Layer4 VIP , this is simply on or off
    public $persist_time;                    //<300> The persistent time in seconds by default is 300
    public $scheduler;                       //<wlc:wrr:dh> wlc=Weighted Least Connection, wrr=Weighted Round Robin, dh=Destination Hash
    public $feedback;                        //<agent:http:none> agent=Feedback Agent, http=HTTP, none=No Feedback
    public $email;                           //<recpt@email.com> Your email address to receive email alerts
    public $email_from;                      //<sender@email.com> Sending email address of email alerts
    public $check_service;                   //<http:https:http_proxy:imap:imaps:pop:pops:ldap:smtp:nntp:dns:mysql:sip:simpletcp:radius:none> If check type = Negotiate then Layer4 knows about various service
    public $check_vhost;                     //<host header> When using a Negotiate check we can enable a host header to check a known site status used for HTTP,HTTPS
    public $check_database;                  //<db> Database to check if check_service=mysql
    public $check_login;                     //<username> used when check_service is MySQL,FTP,IMAP,IMAPS,POP,POPS,LDAP,SIP
    public $check_password;                  //<password> This is the password used with the check_login when required, FTP,IMAP,IMAPS,POP,POPS,LDAP,MYSQL,SIP
    public $check_type;                      //<negotiate|connect|ping|external|off|on|5|10> This is the check type, Negotiate, Connect to port, External script, no checks, always off, No checks, always on, 5 Connects, 1Negotiate, 10 Connects, 1 Negotiate
    public $check_port;                      //<80> Port to check when using Negotiate check
    public $check_request;                   //<check.txt> used for check_service= http, https
    public $check_response;                  //<OK> Response expected to the check_request
    public $check_secret;                    //<secret> This is used only if check_service = RADIUS
    public $check_command;                   //<external_script.sh> This is used when check_type=external
    public $autoscale_group;                 //<YOUR AUTO SCALE GROUP NAME> if in AWS the name of the auto scale group you have defined.

    function __construct($data = null, $template = false){

        if($data != null){

            $this->protocol = $data->protocol;                    
            $this->forwarding = $data->forwarding;                      
            $this->granularity = $data->granularity;                     
            $this->fallback_ip = $data->fallback_ip;                     
            $this->fallback_port = $data->fallback_port;                      
            $this->fallback_local = $data->fallback_local;                  
            $this->persistent = $data->persistent;                     
            $this->persist_time = $data->persist_time;                    
            $this->scheduler = $data->scheduler;                       
            $this->feedback = $data->feedback;                        
            $this->email = $data->email;                          
            $this->email_from = $data->email_from;                     
            $this->check_service = $data->check_service;                  
            $this->check_vhost = $data->check_vhost;                    
            $this->check_database = $data->check_database;                  
            $this->check_login = $data->check_login;                     
            $this->check_password = $data->check_password;                  
            $this->check_type = $data->check_type;                      
            $this->check_port = $data->check_port;                      
            $this->check_request = $data->check_request;                   
            $this->check_response = $data->check_response;                  
            $this->check_secret = $data->check_secret;                    
            $this->check_command = $data->check_command;                   
            $this->autoscale_group = $data->autoscale_group;
            $this->vip = $data->vip;
            $this->ip = $data->ip;
            $this->ports = $data->ports;
            $this->layer = 4;

            $this->rips = array();
            foreach($data->rips as $rip){
                $rip = new LBDeploymentRIP($this, $rip);
                array_push($this->rips, $rip);
            }

        }                

    }

}

class L7LBDeploymentVIP extends LBDeploymentVIP {

    public $mode;                            //<http:tcp> Mode of the Layer7 VIP it is either http or tcp, tcp is an alias of other_tcp and either can be specified
    public $persistence;                     //<http:appsession:sslsesid:rdp-session:rdpcookie:ip:http_ip:xff:none:fallback_persist>
    public $cookiename;                      //<SERVERID> only available when persistence is http,http_ip
    public $fallback_ip;                     //<127.0.0.1> Fallback Server IP Address, this is either the internal NGINX fallback or external or VIP of fallback server
    public $fallback_port;                   //<9081> Fallback Port, 9081 by default of that of the external fallback server ports
    public $persist_time;                    //<30> Persistence timeout available when persistence=appsession,sslsesid,rdpcookie,ip,http_ip,xff
    public $persist_table_size;              //<10240> Persistence table size available when persistence=appsession,sslsesid,rdp-cookie,ip,http_ip,xff
    public $maxconn;                         //<40000> max conns allowed to the VIP
    public $scheduler;                       //<roundrobin:leastconn> Weighted Round Robin or Weighted Least Connections
    public $check_port;                      //<Port of Service> Check port is available when check is negotiate_http,negotiate_https,connect,mysql
    public $check_request;                   //<check.txt> name of file to request
    public $check_receive;                   //<OK> response expected from check request
    public $check_host;                      //<VHOST> Check host header for checking a virtual host with host header
    public $check_username;                  //<mysql> Healthcheck username, only available with check type=mysql
    public $appsession_cookie;               //<JSESSIONID:PHPSESSIONID:ETC> The application session ID provided by your real server.
    public $forward_for;                     //<on:off> Insert X-Forward-For only available in http mode.
    public $http_pipeline;                   //<http_keep_alive|http_close|http_server_close|http_force_close> This is only available in mode=http
    public $http_pretend_keepalive;          //<on:off> Work around broken connection: close This is only available in mode=http
    public $stunneltproxy;                   //<on:off> Only select on if behind an stunnel ssl termination and where stunnel proxy is also enabled on the SSL Termination
    public $feedback_method;                 //<agent:none> The feedback method is either the feedback agent or none. This is available in mode http or tcp
    public $fallback_persist;                //<on:off> Is the fallback server persistent on or off
    public $feedback_port;                   //<3333> Port used for the feedback agent by befault is 3333 only when method=agent
    public $check_type;                      //<negotiate_http:negotiate_http_head:negotiate_https:negotiate_https_head:connect:external:mysql:none> Type of health check to use negotiate_https or negotiate_httpd_head are only available when backend is encrypted
    public $external_check_script;           //<scriptname.sh> This is the filename of external check scripts in /var/lib/loadbalancer.org/check/ available when check_type=external
    public $tcp_keep_alive;                  //
    public $force_to_https;                  //<on:off> Force connection to https, if used then no other options need be configured and no real servers need be present in the VIP. take care when using stunnel_proxy=on
    public $timeout;                         //<on:off> Enable or disable client / real server timeout
    public $timeout_client;                  //<12h> Client Timeout by default 12 hours
    public $timeout_server;                  //<12h> Real Server Timeout by default 12 hours
    public $redirect_code;                   //<301:302:303:307:308> Only used if force_to_https=on 301 (Moved Permanently), 302 (Found), 303 (See Other), 307 (Temporary Redirect), 308 (Permanent Redirect)
    public $no_write;                        //<on:off> This is used to enable manual configuration of the VIP. Not suggested for full lbcli use as you can not edit the manual configuration unless you upload it manually
    public $waf_label;                       //<WAF_VIP_NAME> When creating a WAF the WAF Service will add this to the VIP, Care needs to be taken when changing this as the WAF also needs updating
    public $clear_stick_drain;               //<on:off> Do you want to clear the stick table on drain of the RIP in the VIP
    public $compression;                     //<on:off> Do we enable compression on the VIP, only available in mode=http
    public $autoscale_group;                 //<YOUR AUTOSCALE GROUP NAME> if in AWS the name of the autoscale group you have defined
    public $cookie_maxidle;                  //<30m> Cookie Max Idle Duration
    public $cookie_maxlife;                  //<12h> Cookie Max Life Duration
    public $source_address;                  //<192.168.2.21> IP Address used for health check source IP
    public $backend_encryption;              //<on:off> Only available on mode=http. Do we want to re-encrypt to the real server?
    public $enable_hsts;                     //<on:off> Only available in mode=http
    public $hsts_month;                      //<6> Months the HSTS is valid 3-24 months, Only available in mode=http
    public $xff_ip_pos;                      //<-1> Move the XFF header back one in the list to show client IP in correct place. This is only available when persistence=xff
    public $invalid_http;                    //<on:off> Accept invalid http requests. this is only available in mode=http
    public $send_proxy;                      //<none:v1:v2:v2_ssl:v2_ssn_cn> Send Proxy Protocol None, Send Proxy V1, Send Proxy V2, Send Proxy V2 SSL, Send Proxy V2 SSL CN
    public $as_port;                         //<1234> Autoscale Port on the real servers you have defined in AWS
    public $http_request;                    //<on:off> Default is on to enable Slowlaris protection. You would usually not need to disable this unless the headers are delayed more than 5 seconds
    public $stunnel_source;                  //<1.2.3.4> Source IP of Stunnel VIP
    public $proxy_bind;                      //<name of Layer7 VIP> Name of the Layer7 VIP to bind to.
    public $slave_ip;                        //<1.2.3.4> #Azure Only
    public $tunneltimeout;                   //Value in seconds for WebSockets
    public $redispatch;                      //<on:off> turn redispatch on or off
    public $fallback_encrypt;                //<on:off> Encrypt connection to the fallback server if it is a TLS Connection
    public $http_reuse_connection;           //<on:off> It is possible to reuse idle connections to serve requests from the same session which can be beneficial in terms of performance. It is important to note that the first request of a session is always sent over its own connection, and only subsequent requests may be dispatched over other existing connections.
    public $tproxy;                          //<on:off> Turn tproxy on and off on a VIP level.

    function __construct($data = null, $template = false){

        if($data != null){

            $this->mode = $data->mode;              
            $this->persistence = $data->persistence;                    
            $this->cookiename = $data->cookiename;                     
            $this->fallback_ip = $data->fallback_ip;                  
            $this->fallback_port = $data->fallback_port;                 
            $this->persist_time = $data->persist_time;                   
            $this->persist_table_size = $data->persist_table_size;            
            $this->maxconn = $data->maxconn;                        
            $this->scheduler = $data->scheduler;                      
            $this->check_port = $data->check_port;                     
            $this->check_request = $data->check_request;                  
            $this->check_receive = $data->check_receive;                  
            $this->check_host = $data->check_host;                    
            $this->check_username = $data->check_username;                 
            $this->appsession_cookie = $data->appsession_cookie;            
            $this->forward_for = $data->forward_for;                    
            $this->http_pipeline = $data->http_pipeline;                  
            $this->http_pretend_keepalive = $data->http_pretend_keepalive;         
            $this->stunneltproxy = $data->stunneltproxy;                  
            $this->feedback_method = $data->feedback_method;              
            $this->fallback_persist = $data->fallback_persist;               
            $this->feedback_port = $data->feedback_port;                 
            $this->check_type = $data->check_type;                     
            $this->external_check_script = $data->external_check_script;          
            $this->tcp_keep_alive = $data->tcp_keep_alive;                 
            $this->force_to_https = $data->force_to_https;                 
            $this->timeout = $data->timeout;                        
            $this->timeout_client = $data->timeout_client;                 
            $this->timeout_server = $data->timeout_server;                 
            $this->redirect_code = $data->redirect_code;                  
            $this->no_write = $data->no_write;                       
            $this->waf_label = $data->waf_label;                      
            $this->clear_stick_drain = $data->clear_stick_drain;              
            $this->compression = $data->compression;                   
            $this->autoscale_group = $data->autoscale_group;               
            $this->cookie_maxidle = $data->cookie_maxidle;
            $this->cookie_maxlife = $data->cookie_maxlife;
            $this->source_address = $data->source_address;
            $this->backend_encryption = $data->backend_encryption;
            $this->enable_hsts = $data->enable_hsts;
            $this->hsts_month = $data->hsts_month;
            $this->xff_ip_pos = $data->xff_ip_pos;                    
            $this->invalid_http = $data->invalid_http;                   
            $this->send_proxy = $data->send_proxy;                     
            $this->as_port = $data->as_port;                        
            $this->http_request = $data->http_request;                  
            $this->stunnel_source = $data->stunnel_source;                 
            $this->proxy_bind = $data->proxy_bind;                     
            $this->slave_ip = $data->slave_ip;                       
            $this->tunneltimeout = $data->tunneltimeout;                
            $this->redispatch = $data->redispatch;                   
            $this->fallback_encrypt = $data->fallback_encrypt;               
            $this->http_reuse_connection = $data->http_reuse_connection;         
            $this->tproxy = $data->tproxy;
            $this->vip = $data->vip;
            $this->ip = $data->ip;
            $this->ports = $data->ports;
            $this->layer = 7; 

            $this->rips = array();
            foreach($data->rips as $rip){
                $rip = new LBDeploymentRIP($this, $rip);
                array_push($this->rips, $rip);
            }

        }   
        
        
        
    }

}

?>