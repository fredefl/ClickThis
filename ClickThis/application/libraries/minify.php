<?php
class minify
{
	function __construct ()
	{
		
	}

	public static function Html ($buffer) 
	{
		/* remove tabs, spaces, newlines, etc. */
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
		// Remove comments
		$buffer = preg_replace("/<!--.*?-->/",'',$buffer);
		// Remove space after semicolon
		$buffer = str_replace("; ",";",$buffer);
		// Remove unneccecary quotes
		//$buffer = preg_replace('/"([a-zA-Z0-9]+)"/iU','$1',$buffer);
		//$buffer = preg_replace("/'([a-zA-Z0-9]+)'/iU",'$1',$buffer);
		// Remove spaces before > and />
		$buffer = str_replace(" >",">",$buffer);
		return $buffer;
	}
	
	public static function css ($buffer) {
		/* remove comments */
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	/* remove tabs, spaces, newlines, etc. */
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
	// Remove unnecessary spaces
	$buffer = str_replace("; ",";",$buffer);
	$buffer = str_replace(": ",":",$buffer);
	// Remove style tags
	$buffer = str_replace("<style type=\"text/css\">",'',$buffer);
	$buffer = str_replace("</style>",'',$buffer);
	return $buffer;
	}
}
?>