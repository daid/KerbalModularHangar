<?php
require("database.conf.php");
require("util/db.php");

function typeToMysqlType($type)
{
	switch($type)
	{
	case "ID":
		return "INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY";
	case "INDEX":
		return "INT(11)";
	case "STRING":
		return "VARCHAR(255)";
	case "TEXT":
		return "TEXT";
	default:
		die("Unknown column type: $type");
	}
}

foreach($databaseScheme as $tableName => $table)
{
	if (count(db_query("SHOW TABLES LIKE '$tableName';")) < 1)
	{
		//Create new table.
		$query = "CREATE TABLE IF NOT EXISTS $tableName";
		$columns = array();
		foreach($table as $name => $type)
		{
			$columns[] = "$name ".typeToMysqlType($type);
		}
		db_insert("$query (".implode(", ", $columns).");");
		foreach($table as $name => $type)
			if ($type == "INDEX")
				db_insert("ALTER TABLE  $tableName ADD INDEX ($name);");
	}else{
		$tableInfo = db_query("DESCRIBE $tableName;");
		foreach($table as $name => $type)
		{
			$found = false;
			foreach($tableInfo as $info)
				if ($info->Field == $name)
					$found = $info;
			if($found === false)
			{
				db_insert("ALTER TABLE $tableName ADD COLUMN $name ".typeToMysqlType($type));
			}else{
				$typeName = strtoupper($found->Type);
				if ($found->Null == "NO")
					$typeName .= " NOT NULL";
				if ($found->Extra == "auto_increment")
					$typeName .= " AUTO_INCREMENT";
				if ($found->Key == "PRI")
					$typeName .= " PRIMARY KEY";
				$index = $found->Key == "MUL";
				
				if ($typeName != typeToMysqlType($type))
				{
					echo $tableName.".".$name.": ".$typeName." != ".typeToMysqlType($type)."\n";
				}
				if ($index != ($type == "INDEX"))
				{
					if ($type == "INDEX")
						db_insert("ALTER TABLE  $tableName ADD INDEX ($name);");
					else
						echo $tableName.".".$name.": ".$typeName." has index, but should not!\n";
				}
			}
		}
		foreach($tableInfo as $info)
		{
			$found = false;
			foreach($table as $name => $type)
				if ($info->Field == $name)
					$found = $info;
			if ($found === false)
			{
				db_insert("ALTER TABLE $tableName DROP COLUMN ".$info->Field);
			}
		}
		$last = false;
		foreach($table as $name => $type)
		{
			$query = "ALTER TABLE $tableName CHANGE $name $name ".typeToMysqlType($type);
			$query = str_replace(" PRIMARY KEY", "", $query);
			if ($last === false)
				db_insert($query." FIRST");
			else
				db_insert($query." AFTER ".$last);
			$last = $name;
		}
	}
}
?>