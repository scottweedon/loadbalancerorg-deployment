<?php

class LBDeploymentParameter {

    public $name;
    public $type;       // text | file | REST
    public $format;     // raw | csv | xml | json
    public $method;     // string | array
    public $default;
    public $values;
    public $children;

    function __construct($data = null, $template = false){
        
        if($template && $data == null){
            
            $this->name = "param_" . rand(0,999999);
            $this->type = "text";
            $this->format = "raw";
            $this->method = "string";
            $this->default = "";
            $this->values = array("1","2","3");
            $this->children = array();
        
        }

        if($data != null){

            $this->name = $data->name;
            $this->type = $data->type;
            $this->format = $data->format;
            $this->method = $data->method;
            $this->default = $data->default;
            $this->values = $data->values;
            $this->children = $data->children;

        }
    }

}

?>