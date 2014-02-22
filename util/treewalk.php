<?php

function treewalk($path, $subPath = '')
{
	$ret = array();
	$dh = opendir($path.$subPath);
	while(($file = readdir($dh)) !== false)
	{
		if ($file[0] == '.')
			continue;
		if (is_dir($path.$subPath.$file))
		{
			$ret = array_merge($ret, treewalk($path, $subPath.$file.'/'));
		}else{
			$ret[] = $subPath.$file;
		}
	}
	closedir($dh);
	return $ret;
}

?>