<?php
mysql_connect("localhost", "kerbal", "kerbal") || die();
mysql_select_db("kerbal") || die();
function e($s) {return "'".mysql_real_escape_string($s)."'";}

function db_insert($query)
{
	$res = mysql_query($query);
	if ($res === false)
	{
		echo "SQL error: " . mysql_error() . "<br>\n";
		echo "Query: ".$query."<br>\n";
		return false;
	}
	return mysql_insert_id();
}
function db_query($query)
{
	$res = mysql_query($query);
	if ($res === false)
	{
		echo "SQL error: " . mysql_error() . "<br>\n";
		echo "Query: ".$query."<br>\n";
		return array();
	}
	$ret = array();
	while($row = mysql_fetch_object($res))
	{
		$ret[] = $row;
	}
	return $ret;
}
?>