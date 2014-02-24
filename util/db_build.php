<?php
require("database.conf.php");
require("util/db.php");

function typeToMysqlType($type)
{
	$type = explode(" ", $type)[0];
	switch($type)
	{
	case "ID":
		return "INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY";
	case "STRING":
		return "VARCHAR(255)";
	case "TEXT":
		return "TEXT";
	case "INT":
		return "INT(11)";
	case "FLOAT":
		return "DOUBLE";
	default:
		die("Unknown column type: $type");
	}
}

function typeHasIndex($type)
{
	$type = explode(" ", $type);
	for($i=1;$i<count($type);$i++)
		if ($type[$i] == "INDEX")
			return true;
	return false;
}

foreach($databaseScheme as $tableName => $table)
{
	if (count(db_query("SHOW TABLES LIKE '$tableName';")) < 1)
	{
		//Create new table.
		$query = "CREATE TABLE IF NOT EXISTS $tableName";
		echo "Creating table $tableName\n";
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
				echo "Adding missing column $tableName.$name\n";
				db_insert("ALTER TABLE $tableName ADD COLUMN $name ".typeToMysqlType($type));
			}else{
				$typeName = strtoupper($found->Type);
				if ($found->Null == "NO")
					$typeName .= " NOT NULL";
				if ($found->Extra == "auto_increment")
					$typeName .= " AUTO_INCREMENT";
				if ($found->Key == "PRI")
					$typeName .= " PRIMARY KEY";
				
				if ($typeName != typeToMysqlType($type))
				{
					echo $tableName.".".$name.": ".$typeName." != ".typeToMysqlType($type)."\n";
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
				echo "Removing column $tableName.".$info->Field."\n";
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
		
		$indexes = db_query("SHOW INDEXES IN $tableName;");
		foreach($indexes as $index)
		{
			if ($index->Key_name == "PRIMARY")
				continue;
			if (!typeHasIndex($table[$index->Column_name]))
			{
				echo "Need to remove index of ".$index->Column_name."\n";
			}
		}
		
		foreach($table as $name => $type)
		{
			if (typeHasIndex($type))
			{
				$found = false;
				foreach($indexes as $index)
				{
					if ($index->Column_name == $name)
						$found = true;
				}
				if (!$found)
				{
					echo "Adding index: $tableName.$name\n";
					db_insert("ALTER TABLE $tableName ADD INDEX $name ($name);");
				}
			}
		}
	}
}
?>