<?php
// function defination to convert array to xml
function array_to_xml( $data, &$xml_data) {

    foreach( $data as $key => $value ) {
         if( is_array($value) ) {
         if( is_numeric($key) ){
                $key = 'movie'.$key; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
     echo $xml_data->asXML();
     return $xml_data;
}
?>
