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



