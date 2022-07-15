<?php

class LBDeploymentCLI {

    public function __construct() {

    }

    public static function execute($action, $object) {

        $output=null;
        $retval=null;
        $return = 0;

        $command = "/usr/local/sbin/lbcli --action " . $action;

        foreach ($object as $key => $value) {
            if (trim($value) != "") {
                if ($value == trim($value) && strpos($value, ' ') !== false) {
                    $command .= " --$key '" . trim($value) . "'";
                } else {
                    $command .= " --$key " . trim($value);
                }
            }
        }

        //TODO: Log the command here
        LBDeployment::logger("CMD: " . $command);

        exec($command, $output, $retval); 

        LBDeployment::logger("RSP: " . json_encode($output));

        foreach($output as $line){

            if(strpos($line, '"status":') != 0){
                
                if(isset(json_decode($line)->lbcli->status)){
                    switch(json_decode($line)->lbcli->status){
                        case "success": $return = 1; break;
                        case "failed": $return = 0; break;
                        case "exists": $return = 2; break;
                        default: $return = 0; break;
                    }
                } else {
                    switch(json_decode($line)->lbcli[0]->status){
                        case "success": $return = 1; break;
                        case "failed": $return = 0; break;
                        case "exists": $return = 2; break;
                        default: $return = 0; break;
                    }
                }
        
            } else {

                if(isset($object->vip)){
                    if(strpos($line, "  exists") != 0){
                        $return = 2;
                    }
                }

            }

        }     

        return $return;

    }

    public static function execute_raw($action, $params) {

        $output=null;
        $retval=null;
        $return = 0;

        $command = "/usr/local/sbin/lbcli --action " . $action . " " . $params;

        LBDeployment::logger("CMD: " . $command);

        exec($command, $output, $retval); 

        LBDeployment::logger("RSP: " . json_encode($output));

        foreach($output as $line){

            LBDeployment::logger("Error: " . $line);

            if(strpos($line, '"status":') != 0){
                
                if(isset(json_decode($line)->lbcli->status)){
                    switch(json_decode($line)->lbcli->status){
                        case "success": $return = 1; break;
                        case "failed": $return = 0; break;
                        case "exists": $return = 2; break;
                        default: $return = 0; break;
                    }
                } else {
                    switch(json_decode($line)->lbcli[0]->status){
                        case "success": $return = 1; break;
                        case "failed": $return = 0; break;
                        case "exists": $return = 2; break;
                        default: $return = 0; break;
                    }
                }
        
            } else {

                if(strpos($line, '  exists') != 0){
                    return 2;
                }

            }

        }     
        
        return $return;

    }

}



?>