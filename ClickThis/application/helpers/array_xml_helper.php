<?php
/**
 * The main function for converting to an XML document.
 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
 *
 * @param array $data
 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
 * @param SimpleXMLElement $xml - should only be used recursively
 * @return string XML
 */
function array_to_xml($data = NULL, $rootNodeName = 'response', $xml = null)
{
	if(!is_null($data) && is_array($data)){
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
			ini_set ('zend.ze1_compatibility_mode', 0);
		}

		if ($xml == null)
		{
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}

		// loop through the data passed in.
		foreach($data as $key => $value)
		{
			if(is_object($value)){
				$value = get_object_vars($value);
			}
			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				// make string key...
				$key = "element_". (string) $key;
			}

			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z]/i', '', $key);

			// if there is another array found recrusively call this function
			if (is_array($value))
			{
				$node = $xml->addChild($key);
				// recrusive call.
				array_to_xml($value, $rootNodeName, $node);
			}
			else 
			{
				// add single node.
                            	if(is_bool($value)){
                            		$value = (int) $value;
                            	}
                            	$value = htmlentities($value);

				$xml->addChild($key,$value);
			}

		}
		// pass back as string. or simple xml object if you want!
		$Return = $xml->asXML();
		return str_replace("unknownNode", "element", $Return);
	} else {
		return "";
	}
}

/**
 * This function converts an object to an array
 * @param  object $object The object to convert to array
 * @return array
 */
function object_to_array ( $object = null ) {
	$array = array();
	if(!is_null($object) && is_object($object)){
		foreach (get_object_vars($object) as $key => $value) {
			if(is_object($value)){
				$array[$key] = object_to_array($value);
			} else {
				$array[$key] = $value;
			}
		}
	}
	return $array;
}

/**
 * This function is the recursive function that converts the array to the correct format
 * @param  array $array The array currently converting
 * @return array
 */
function xml_import_loop($array){
	$temp = array();
	if(is_array($array)){
		foreach ($array as $key => $value) {
			if($key == "element"){
				if(is_array($value) && count($value) > 1){
					$temp[] = xml_import_loop($value);
				} else {
					$temp[] = $value;
				}
			} else {
				if(is_array($value)){
					if(count($value) == 1){
						$value = current($value);
					}
					$temp[$key] = xml_import_loop($value);
				} else {
					$temp[$key] = $value;
				}
			}
		}
	} else {
		$temp = $array;
	}
	return $temp;
}

/**
 * This function imports raw XML and converts it to the Standard Library Import Format
 * @param  string $xml The raw xml string to inport
 * @return array
 */
function xml_import($xml){
	$object = simplexml_load_string($xml);
	$array = xml_to_array($object);
	return xml_import_loop($array);
}

/**
 * This function converts a SimpleXML element to an array
 * @param  object  $obj   The XML element to convert
 * @param  boolean $extra If attributes etc should be included
 * @return array
 * @author Xaviered <xaviered@gmail.com>
 */
function xml_object_to_array($obj, $extra = true) { 
    $namespace = $obj->getDocNamespaces(true); 
    $namespace[NULL] = NULL; 
    
    $children = array(); 
    $attributes = array(); 
    $name = strtolower((string)$obj->getName()); 
    
    $text = trim((string)$obj); 
    if( strlen($text) <= 0 ) { 
        $text = NULL; 
    } 
    
    // get info for all namespaces 
    if(is_object($obj)) { 
        foreach( $namespace as $ns=>$nsUrl ) { 
            // atributes 
            $objAttributes = $obj->attributes($ns, true); 
            foreach( $objAttributes as $attributeName => $attributeValue ) { 
                $attribName = strtolower(trim((string)$attributeName)); 
                $attribVal = trim((string)$attributeValue); 
                if (!empty($ns)) { 
                    $attribName = $ns . ':' . $attribName; 
                } 
                $attributes[$attribName] = $attribVal; 
            } 
            
            // children 
            $objChildren = $obj->children($ns, true); 
            foreach( $objChildren as $childName=>$child ) { 
                $childName = strtolower((string)$childName); 
                if( !empty($ns) ) { 
                    $childName = $ns.':'.$childName; 
                } 
                $children[$childName][] = xml_object_to_array($child); 
            } 
        } 
    } 
    if($extra){
        return array( 
            'name'=>$name, 
            'text'=>$text, 
            'attributes'=>$attributes, 
            'children'=>$children 
        ); 
	} else {
		return $children;
	}
}

function xml_to_array($xml) {
    $array = json_decode(json_encode($xml), TRUE);
    
    foreach ( array_slice($array, 0) as $key => $value ) {
        if ( empty($value) ) $array[$key] = NULL;
        elseif ( is_array($value) ) $array[$key] = xml_to_array($value);
    }

    return $array;
}
?>