<?php

function parse_cfg($filename)
{
	$fd = fopen($filename, "r");
	$ret = array();
	$obj = false;
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
		if (strpos($line, "=") !== false)
		{
			$key = trim(substr($line, 0, strpos($line, "=")));
			$value = trim(substr($line, strpos($line, "=")+1));
			$obj->data[$key] = $value;
		}else if ($line == "{")
		{
			$newobj = new stdClass();
			$newobj->type = $lastLine;
			$newobj->data = array();
			$newobj->parent = $obj;
			$newobj->childs = array();
			if ($obj === false)
			{
				$ret[] = $newobj;
			}else{
				$obj->childs[] = $newobj;
			}
			$obj = $newobj;
		}else if ($line == "{")
		{
			$obj = $obj->parent;
		}else{
			$lastLine = $line;
		}
	}
	fclose($fd);
	return $ret;
}

var_dump(parse_cfg("C:/KSP_win/GameData/Squad/Parts/Aero/advancedCanard/part.cfg"));
?>