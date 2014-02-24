<?php

function parse_cfg($filename)
{
	$fd = fopen($filename, "r");
	$ret = array();
	$obj = new stdClass();
	$obj->type = "ROOT";
	$obj->data = array();
	$obj->parent = false;
	$obj->childs = array();
	$root = $obj;
	$lastLine = "";
	while(($line = fgets($fd)) !== false)
	{
		if (strpos($line, "//") !== false)
		{
			$line = trim(substr($line, 0, strpos($line, "//")));
		}
		$line = trim($line);
		
		if (strlen($line) < 1)
			continue;
		if (substr($line, 0, 3) == "\xEF\xBB\xBF")//Strip UTF-8 byte order mark.
			$line = substr($line, 3);
		if (substr($line, strlen($line) - 1) == '{')
		{
			$pre = trim(substr($line, 0, strlen($line) - 1));
			if (strlen($pre) > 0)
				$lastLine = $pre;
			$line = '{';
		}
		
		if (strpos($line, "=") !== false)
		{
			$key = trim(substr($line, 0, strpos($line, "=")));
			$value = trim(substr($line, strpos($line, "=")+1));
			if ($obj === false)
			{
				//if ($key != "proxy")
				//	echo "Data outside of object...\n".$filename."\n";
			}else{
				$obj->data[$key] = $value;
			}
		}else if ($line == "{")
		{
			$newobj = new stdClass();
			$newobj->type = $lastLine;
			$newobj->data = array();
			$newobj->parent = $obj;
			$newobj->childs = array();
			$obj->childs[] = $newobj;
			$obj = $newobj;
		}else if ($line == "}")
		{
			if ($obj->parent === false)
				echo "Warning: Close object with no object open...\n".$filename."\n";
			else
				$obj = $obj->parent;
		}else{
			$lastLine = $line;
		}
	}
	fclose($fd);
	return $root;
}
?>