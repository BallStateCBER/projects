<?php
echo $this->element("Cuploadify.uploadify", array(
    "dom_id" => "file_upload",
    "session_id" => $session_id,
    "include_scripts" => 
            array(
            	//"jquery"=>"/cuploadify/js/jquery.js", 
            	"swfobject", 
            	"uploadify", 
            	"uploadify_css"
           	),
    		"options" => array("script" => "/cuploadify/tests/upload",
                       "folder" => "/files", 
                       "buttonText" => "ADD FILE", 
                       "auto" => true, 
                       "multi" => true,
	))
);
