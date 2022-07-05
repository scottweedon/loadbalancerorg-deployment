<?php

//require_once("lbadmin_config.inc");
//require_once("page.inc");
//require_once("form.inc");
//require_once("header.inc");
//require_once('httpd.inc');

require_once("form.php");
require_once("language.php");
require_once("classes/MainMenu.class.php");
require_once("lbadmin_config.php");
require_once("utils.php");
require_once("page.php");
require_once("licence.php");
require_once("exceptions.php");
require_once("eval.php");
require_once("header.php");
require_once("lbModel.class.php");
require_once('licenceV2.php');

include('class/LBDeployment.php');

switch ($action) {
    case 'edit':
        edit_data();
        break;
}

secure_deploymentform();
require_once("footer.inc");
exit();

function resolve_parameter($param, $deployment_string, $parent = ""){

    $data_array = array();

    // Create a data array based on the parameter type
    switch($param->type){

        case "file": 

            // traverse the the input format and convert to a 2d array
            if(strtolower($param->format) == "csv"){

                if($parent != null){
                    $csv_data = file_get_contents($_FILES[$parent->name]["tmp_name"]);
                } else {
                    $csv_data = file_get_contents($_FILES[$param->name]["tmp_name"]);
                }

                $lines = explode("\n", $csv_data);

                foreach ($lines as $line) {
                    if(trim($line) != ""){
                        array_push($data_array, str_getcsv($line));
                    }
                }
            }

        break;

        case "array": 

            for($x = 0; $x <= $_POST[$param->name . "_array_counter"]; $x++){
                $data_array[$x] = array();
                foreach($param->values as $value){
                    $data_array[$x][$value[1]] = $_POST[$param->name . "_" . $value[0] . "_" . $x];   
                }
            }

        break;

        case "text":

            $data_array[0][0] = $_POST[$param->name];   

        break;

    }

    // Replace the deployment string based on the parameter method
    switch($param->method){

        case "string": 
            
            $deployment_string = str_replace('!#' . $param->name . '#!', $data_array[0][0], $deployment_string);

        break;

        case "object": 
            
            if($param->type != "array"){
                $arr = array();
                $i = 0;

                foreach($data_array as $data_row){
                    $arr[$i] = array();
                    foreach($param->values as $value){
                        $arr[$i][$value[1]] = $data_row[$value[0]];   
                    }
                    $i++;
                }
            } else {
                $arr = $data_array;
            }

            $deployment_string = str_replace('"!#' . $param->name . '#!"', json_encode($arr), $deployment_string);

        break;

    }

    return $deployment_string;

}

function secure_deploymentform()
{

    $deployment = null;
    $config = null;

    // read the config
    if(isset($_POST["submit"])) {
        
        $config = file_get_contents($_FILES["deployment_upload"]["tmp_name"]);

        try {
            
            $config = json_decode($config);

        } catch (Exception $e) {

            echo "Invalid JSON";

        }
        
    }

    if(isset($_POST["deployment"])) {

        try {

            echo "<pre>";

            $deployment_string = base64_decode($_POST["deployment"]);
            $deployment = json_decode($deployment_string);
            
            foreach($deployment->parameters as $param){

                foreach(explode(",", $_POST[$param->name]) as $paramitem){

                    if(count($param->children) > 0){

                        foreach($param->children as $child_param){

                            $deployment_string = resolve_parameter($child_param, $deployment_string, $param);

                        }
                    
                    } else {

                        $deployment_string = resolve_parameter($param, $deployment_string);
                    
                    }

                    $i++;

                }

            }

            $deployment = json_decode($deployment_string);
            $deployment = new LBDeployment($deployment);
            
            try {
                $deployment->config->apply();
            } catch (Exception $e){
                var_dump($e);
            }
            
            echo "</pre>";

            $deployment->apply();
            
        } catch (Exception $e) {

            echo "Invalid JSON";
            var_dump($e);

        }
        
    }

    global $submnp, $mnp, $t, $l, $lb, $webUICiphers;
    $xml = physical_read_xml(); ?>
    <script type="text/javascript" src="/lbadmin/js/secure.js"></script>

    <div id="contentArea">
        <h1>Upload Deployment</h1>
        
        <div class="border">

            <form method="post" action="?l=<?php echo $l ?>&t=<?php echo $t ?>&submnp=<?php echo $submnp ?>&mnp=<?php echo $mnp ?>" enctype="multipart/form-data">
            
            <?php if($config !== null) { ?>

                <table class="form" align="center" cellspacing="0" border="0">
                <?php 
                echo "<input type='hidden' id='deployment' name='deployment' value='" . base64_encode(json_encode($config)) . "'></input>";
                    $toggle = true;
                    foreach($config->parameters as $param){                    
                        echo "<tr";
                        if($toggle == true){ echo " class='altLine'>"; } else { echo ">"; }
                        echo "  <td valign='top'>" . $param->description . "</td>
                                <td>";

                        switch($param->type){
                            case "text": echo "<input style='width:300px;' type='" . $param->type . "' name='" . $param->name . "' id='" . $param->name . "' value='" . $param->default . "'></input>"; break;
                            case "file": echo "<input style='width:300px;' type='" . $param->type . "' name='" . $param->name . "' id='" . $param->name . "' value='" . $param->default . "'></input>"; break;
                            case "array": 
                                echo "
                                    <div class='" . $param->name . "_array'>
                                        <input type='hidden' id='" . $param->name . "_array_counter' name='" . $param->name . "_array_counter' value='0'></input>";
                                 
                                        echo "<script>
                                            function " . $param->name . "_addrow(){ 

                                                row = '<div style=\'width:100%\'>';
                                                $('#" . $param->name . "_array_counter').val( parseInt($('#" . $param->name . "_array_counter').val()) + 1 );";

                                                foreach($param->values as $col){
                                                    echo "row = row + '<input style=\'width:140px;\' type=\'text\' name=\'" . $param->name . "_" . $col[0] . "_' + $('#" . $param->name . "_array_counter').val() + '\' id=\'" . $param->name . "_" . $col[0] . "_' + $('#" . $param->name . "_array_counter').val() + '\' placeholder=\'" . $col[0] . "\' value=\'" . $param->default . "\'></input>';";
                                                }

                                            echo "                                       
                                            row = row + '</div>';
                                            $('#" . $param->name . "_array_row').append(row);
                                            }
                                        </script>";

                                    echo "<div style='width:100%' id='" . $param->name . "_array_row'>";
                                    
                                    foreach($param->values as $col){
                                        echo "<input style='width:140px;' type='text' name='" . $param->name . "_" . $col[0] . "_0' id='" . $param->name . "_" . $col[0] . "_0' placeholder='" . $col[0] . "' value='" . $param->default . "'></input>";
                                    }
                                    echo "</div>";
                               
                                
                                echo "</div>";
                                echo "<input style='height:30px' class='button bgreen' type='button' onclick='" . $param->name . "_addrow()' value='+ Add Row'></input>";
                                break;
                        }

                        echo "       </td>
                                <td class='helpbutton'></td>
                            </tr>";
                        
                        ($toggle == true) ? $toggle = false : $toggle = true;
                    }
                ?>
                </table>
                <p class="submit">
                    <input class='button bgreen' type="submit" value="Apply Configuration" name="go">
                    <input class='button bred' onclick="window.location.replace('/lbadmin/deployment/index.php')" type="button" value="Cancel" name="cancel">
                </p>
                
            <?php } elseif($deployment == null) { ?>
            
                <input type="file" id="deployment_upload" name="deployment_upload">
                <input class='button bgreen' type="submit" value="Upload Deployment File" name="submit">

            <?php } else { ?>

                <p>Config Applied</p>

            <?php } ?>

            </form>
        </div>
    </div>
    <?
}

/**
 * @throws LBCallError
 */
function edit_data()
{
    global $lb;
    $xml = physical_read_xml();

    //var_dump($xml);

    //defaults are set to off for check boxs, if empty they are not submitted with post data
    $vip = form_value('deployment_vip', 'off');
    $managementip = form_value('deployment_vip_ip');

    if (!is_numeric($vip)) {
        show_warning("Please enter a valid ip for the Virtual IP");
        return;
    }

    if (!is_numeric($vip)) {
        show_warning("Please enter a valid ip for the Management IP");
        return;
    }

    physical_write_xml($xml, False);

    try {
        
    } catch (Exception $e) {
        lb_log("Failed to update security configuration config $e");
    }
}
?>
