# UNOFFICIAL Loadbalancer.org Deployment Concept
Method to deploy templated configuration for the Loadbalancer.org v8 appliance.

*** **NOTE:** Work in progress some functionality may not work as expected *** 

# Requirements
Loadbalancer.org Appliance running v8.6.2 or newer.

# Installation
1) Create the following "deployment" folder on the loadbalancer.org appliance - **/var/www/html/lbadmin/deployment**
2) Upload repository contents to the deployment folder.
3) Browse to **https://\<IP\>:9443/lbadmin/deployment**

# Creating the Deployment File
The deployment file is split into two sections. The **parameters** and the **config**. When combined together into a single **json** file we have a single deployment file that has the below structure.

```
{
  
  "method": "manual",
  
  "parameters": [
  ],
  
  "config": {
  }
  
 }
```

**Parameters** allow you to specify custom values that are required to complete the configuration. Once the deployment file is uploaded, the values configured within this section will appear as html input fields allowing the end user to enter unique values. These parameters may for example be IP Addresses, Ports, Names and Descriptions. **This is an array of parameter objects.**

**Config** stores the common or static configuration for this deployment. Again this could be IP Addresses, Ports, Names and Descriptions. However it is more likely this section is used for health checks or more advanced settings. **This is a single config object.**

## Creating Parameters
Each **Paramater Object** contains the following keys. ( * required )

- name *
  - string: **param_<<random_number>>**
  - A unique (to this file) name for this parameter. Used as a variable name within the config section.
- description *
  - string: **null**
  - Displays a human readable description for the form element. 
- type *
  - string: **text** | file | array
  - setting this to **text** will render a single textbox on the HTML form. 
  - setting this to **file** will render a single file upload prompt on the HTML form.
  - setting this to **array** will render multiple html textboxes side to side, depending on the contents of the values field. 
- format
  - string: **raw** | csv
  - if type is file, specify the file format.
- method
  - string: **string** | array | object
- default
  - string: **null**
  - Any value enetered here will be pre-populated into the html form. 
- values
  - array[ string ]
  - Use this when complex values need to fetch from multi-dimesional sources.
```
When using this option with "type: file, format: csv, method: string" a single string can be extracted from a CSV file.
This example shows how to request the string stored in the 3rd row and 5 column of a CSV file.
{
    "name": "VIP_NAME",
    "description": "Virtual Service Name",
    "type": "file",
    "format": "csv",
    "method": "string",
    "default": "",
    "values": [
        [3, 5]
    ]
}
```
```
When using this option with "method: object" descriptions can be linked to values specified within an object.
This example shows how to request multiple textbox inputs and link them to properties of a RIP object.
{
    "name": "RIPS",
    "description": "Real Servers",
    "type": "array",
    "method": "object",
    "default": "",
    "values": [
        ["Name", "rip"],
        ["IPAddress", "ip"]
    ]
}
```
- children
  - array[ { Parameter Object } ]
  - Using children allows a single html input value to provide answers for multiple paramaters. An example might be a single CSV file upload prompt that contains the list of Real Server IP Addresses and additionally the VIP IP Address. (see below)
 
```
{
  "name": "FILE_CONFIG",
  "description": "Upload Config File",
  "type": "file",
  "children": [
    {
      "name": "FILE_RIPS",
      "description": "Real Servers",
      "type": "file",
      "format": "csv",
      "method": "object",
      "default": "",
      "values": [
        [1, "rip"],
        [2, "ip"]
      ]
    },
    {
      "name": "FILE_VIP_NAME",
      "description": "Virtual Service IP",
      "type": "file",
      "format": "csv",
      "method": "string",
      "default": "",
      "values": [
        [0, 0]
      ]
    }
  ]
}
```


## Creating Config
The config section should contain a L7_vips and L4_vips array. Each array contains a VIP Object for either Layer7 or Layer4 VIPs.

```
"config": {
    "L7_vips": [],
    "L4_vips": []
}
```

### VIP Object

The below config would deploy a virtual service listening on ports 16080 and 9999 on port 10.0.0.200. The virtual service will have any real servers as specified in the array of RIP objects specified under rips. If placed in the L7_vips array it will provisiona layer 7 VIP, if placed in the layer 4 if will provision a layer 4 VIP.
```
{
  "name": "Virtual Service",
  "ip": "10.0.0.200",
  "ports": "16080,9999",
  "rips": array[RIP Objects]
}
```

**Additional Layer7 VIP Object Parameters**
```
    mode;                            //<http:tcp> Mode of the Layer7 VIP it is either http or tcp, tcp is an alias of other_tcp and either can be specified
    persistence;                     //<http:appsession:sslsesid:rdp-session:rdpcookie:ip:http_ip:xff:none:fallback_persist>
    cookiename;                      //<SERVERID> only available when persistence is http,http_ip
    fallback_ip;                     //<127.0.0.1> Fallback Server IP Address, this is either the internal NGINX fallback or external or VIP of fallback server
    fallback_port;                   //<9081> Fallback Port, 9081 by default of that of the external fallback server ports
    persist_time;                    //<30> Persistence timeout available when persistence=appsession,sslsesid,rdpcookie,ip,http_ip,xff
    persist_table_size;              //<10240> Persistence table size available when persistence=appsession,sslsesid,rdp-cookie,ip,http_ip,xff
    maxconn;                         //<40000> max conns allowed to the VIP
    scheduler;                       //<roundrobin:leastconn> Weighted Round Robin or Weighted Least Connections
    check_port;                      //<Port of Service> Check port is available when check is negotiate_http,negotiate_https,connect,mysql
    check_request;                   //<check.txt> name of file to request
    check_receive;                   //<OK> response expected from check request
    check_host;                      //<VHOST> Check host header for checking a virtual host with host header
    check_username;                  //<mysql> Healthcheck username, only available with check type=mysql
    appsession_cookie;               //<JSESSIONID:PHPSESSIONID:ETC> The application session ID provided by your real server.
    forward_for;                     //<on:off> Insert X-Forward-For only available in http mode.
    http_pipeline;                   //<http_keep_alive|http_close|http_server_close|http_force_close> This is only available in mode=http
    http_pretend_keepalive;          //<on:off> Work around broken connection: close This is only available in mode=http
    stunneltproxy;                   //<on:off> Only select on if behind an stunnel ssl termination and where stunnel proxy is also enabled on the SSL Termination
    feedback_method;                 //<agent:none> The feedback method is either the feedback agent or none. This is available in mode http or tcp
    fallback_persist;                //<on:off> Is the fallback server persistent on or off
    feedback_port;                   //<3333> Port used for the feedback agent by befault is 3333 only when method=agent
    check_type;                      //<negotiate_http:negotiate_http_head:negotiate_https:negotiate_https_head:connect:external:mysql:none> Type of health check to use negotiate_https or negotiate_httpd_head are only available when backend is encrypted
    external_check_script;           //<scriptname.sh> This is the filename of external check scripts in /var/lib/loadbalancer.org/check/ available when check_type=external
    tcp_keep_alive;                  //
    force_to_https;                  //<on:off> Force connection to https, if used then no other options need be configured and no real servers need be present in the VIP. take care when using stunnel_proxy=on
    timeout;                         //<on:off> Enable or disable client / real server timeout
    timeout_client;                  //<12h> Client Timeout by default 12 hours
    timeout_server;                  //<12h> Real Server Timeout by default 12 hours
    redirect_code;                   //<301:302:303:307:308> Only used if force_to_https=on 301 (Moved Permanently), 302 (Found), 303 (See Other), 307 (Temporary Redirect), 308 (Permanent Redirect)
    no_write;                        //<on:off> This is used to enable manual configuration of the VIP. Not suggested for full lbcli use as you can not edit the manual configuration unless you upload it manually
    waf_label;                       //<WAF_VIP_NAME> When creating a WAF the WAF Service will add this to the VIP, Care needs to be taken when changing this as the WAF also needs updating
    clear_stick_drain;               //<on:off> Do you want to clear the stick table on drain of the RIP in the VIP
    compression;                     //<on:off> Do we enable compression on the VIP, only available in mode=http
    autoscale_group;                 //<YOUR AUTOSCALE GROUP NAME> if in AWS the name of the autoscale group you have defined
    cookie_maxidle;                  //<30m> Cookie Max Idle Duration
    cookie_maxlife;                  //<12h> Cookie Max Life Duration
    source_address;                  //<192.168.2.21> IP Address used for health check source IP
    backend_encryption;              //<on:off> Only available on mode=http. Do we want to re-encrypt to the real server?
    enable_hsts;                     //<on:off> Only available in mode=http
    hsts_month;                      //<6> Months the HSTS is valid 3-24 months, Only available in mode=http
    xff_ip_pos;                      //<-1> Move the XFF header back one in the list to show client IP in correct place. This is only available when persistence=xff
    invalid_http;                    //<on:off> Accept invalid http requests. this is only available in mode=http
    send_proxy;                      //<none:v1:v2:v2_ssl:v2_ssn_cn> Send Proxy Protocol None, Send Proxy V1, Send Proxy V2, Send Proxy V2 SSL, Send Proxy V2 SSL CN
    as_port;                         //<1234> Autoscale Port on the real servers you have defined in AWS
    http_request;                    //<on:off> Default is on to enable Slowlaris protection. You would usually not need to disable this unless the headers are delayed more than 5 seconds
    stunnel_source;                  //<1.2.3.4> Source IP of Stunnel VIP
    proxy_bind;                      //<name of Layer7 VIP> Name of the Layer7 VIP to bind to.
    slave_ip;                        //<1.2.3.4> #Azure Only
    tunneltimeout;                   //Value in seconds for WebSockets
    redispatch;                      //<on:off> turn redispatch on or off
    fallback_encrypt;                //<on:off> Encrypt connection to the fallback server if it is a TLS Connection
    http_reuse_connection;           //<on:off> It is possible to reuse idle connections to serve requests from the same session which can be beneficial in terms of performance. It is important to note that the first request of a session is always sent over its own connection, and only subsequent requests may be dispatched over other existing connections.
    tproxy;                          //<on:off> Turn tproxy on and off on a VIP level.
```

**Additional Layer4 VIP Object Parameters**
```
    protocol;                        //<tcp:udp:ops:fwm> # We do not support manual firewall marks where IP = FWM Number as you need to manually add the firewall rules
    forwarding;                      //<gate:masq:ipip> Gate = L4 DR masq=L4 NAT ipip=TUN Mode
    granularity;                     //<255.255.255.255> This is the subnet or single ip range for persistence
    fallback_ip;                     //<127.0.0.1> This is the fallback server IP Address, It may be an external IPAddress
    fallback_port;                   //<9081> This is the fallback server port, it may be the port of an external webserver.    
    fallback_local;                  //<on:off> MASQ Fallback. Allows fallback server port to be different to that of the real server.
    persistent;                      //<on:off> Are we a persistent Layer4 VIP , this is simply on or off
    persist_time;                    //<300> The persistent time in seconds by default is 300
    scheduler;                       //<wlc:wrr:dh> wlc=Weighted Least Connection, wrr=Weighted Round Robin, dh=Destination Hash
    feedback;                        //<agent:http:none> agent=Feedback Agent, http=HTTP, none=No Feedback
    email;                           //<recpt@email.com> Your email address to receive email alerts
    email_from;                      //<sender@email.com> Sending email address of email alerts
    check_service;                   //<http:https:http_proxy:imap:imaps:pop:pops:ldap:smtp:nntp:dns:mysql:sip:simpletcp:radius:none> If check type = Negotiate then Layer4 knows about various service
    check_vhost;                     //<host header> When using a Negotiate check we can enable a host header to check a known site status used for HTTP,HTTPS
    check_database;                  //<db> Database to check if check_service=mysql
    check_login;                     //<username> used when check_service is MySQL,FTP,IMAP,IMAPS,POP,POPS,LDAP,SIP
    check_password;                  //<password> This is the password used with the check_login when required, FTP,IMAP,IMAPS,POP,POPS,LDAP,MYSQL,SIP
    check_type;                      //<negotiate|connect|ping|external|off|on|5|10> This is the check type, Negotiate, Connect to port, External script, no checks, always off, No checks, always on, 5 Connects, 1Negotiate, 10 Connects, 1 Negotiate
    check_port;                      //<80> Port to check when using Negotiate check
    check_request;                   //<check.txt> used for check_service= http, https
    check_response;                  //<OK> Response expected to the check_request
    check_secret;                    //<secret> This is used only if check_service = RADIUS
    check_command;                   //<external_script.sh> This is used when check_type=external
    autoscale_group;                 //<YOUR AUTO SCALE GROUP NAME> if in AWS the name of the auto scale group you have defined.
```

