<?php
require("util/db.php");
require("util/ksp_cfg.php");
require("util/treewalk.php");

function import_package($path, $packageName)
{
	$package = db_query("SELECT * FROM Package WHERE Name = ".e($packageName));
	if (count($package) < 1)
		$id = db_insert("INSERT INTO Package(Name) VALUES (".e($packageName).")");
	else
		$id = $package[0]->ID;
	
	foreach(treewalk($path) as $filename)
	{
		$ext = strtolower(strrchr($filename, '.'));
		if ($ext == '.cfg')
		{
			$cfg = parse_cfg($path.$filename);
			foreach($cfg as $obj)
			{
				_import_object_definition($id, $filename, $obj);
			}
		}
		else if ($ext == '.dll')
		{
			$plugin = db_query("SELECT * FROM Plugin WHERE Package = ".e($id)." AND Filename = ".e($filename));
			if (count($plugin) < 1)
			{
				$pluginId = db_insert("INSERT INTO Plugin(Package, Filename) VALUES (".e($id).", ".e($filename).");");
				foreach(explode("\n", shell_exec("Analizer.exe \"".$path.$filename."\"")) as $partModule)
				{
					$partModule = trim($partModule);
					if (strlen($partModule) < 1)
						continue;
					db_insert("INSERT INTO PluginPartModule(Plugin, Name) VALUES (".e($pluginId).", ".e($partModule).");");
				}
			}
		}
		else if ($ext == '.mu') {}	//Model
		else if ($ext == '.mdl') {}	//Model
		else if ($ext == '.msh') {}	//Model
		else if ($ext == '.v4') {}	//Model
		else if ($ext == '.dae') {}	//Model
		else if ($ext == '.mbm') {}	//Texture
		else if ($ext == '.png') {}	//Texture
		else if ($ext == '.tga') {}	//Texture
		else if ($ext == '.wav') {}	//Sound effect
		else if ($ext == '.ogg') {}	//Music
		else if ($ext == '.meta') {} //??? (found in Squad/Sounds/sound_decoupler_fire.wav.meta)
		else
			echo $filename."\n";
	}
}

function _import_object_definition($package, $filename, $obj)
{
	if ($obj->type == "PART")
	{
		_import_part_definition($package, $filename, $obj);
	}elseif ($obj->type == "PROP")
	{
	}elseif ($obj->type == "RESOURCE_DEFINITION")
	{
		_import_resource_definition($package, $filename, $obj);
	}elseif ($obj->type == "EXPERIMENT_DEFINITION")
	{
	}elseif ($obj->type == "INTERNAL")
	{
	}else{
		echo "Unknown definition type: ".$obj->type."\n";
	}
}

function _import_part_definition($package, $filename, $part)
{
	$res = db_query("SELECT ID FROM Part WHERE Package = ".e($package)." AND Name = ".e($part->data['name']));
	if (count($res))
		return;
	$partid = db_insert("INSERT INTO Part(Package, Name, Filename) VALUES (".e($package).", ".e($part->data['name']).", ".e($filename).");");
	foreach($part->data as $name => $value)
	{
		db_insert("INSERT INTO PartProperty(Part, Name, Value) VALUES (".e($partid).", ".e($name).", ".e($value).");");
	}
	
	foreach($part->childs as $child)
	{
		if ($child->type == "MODULE")
		{
			$moduleid = db_insert("INSERT INTO PartModule(Part, Name) VALUES (".e($partid).", ".e($child->data['name']).");");
			foreach($part->data as $name => $value)
			{
				db_insert("INSERT INTO PartModuleProperty(PartModule, Name, Value) VALUES (".e($moduleid).", ".e($name).", ".e($value).");");
			}
		}elseif ($child->type == "RESOURCE")
		{
			db_insert("INSERT INTO PartResource(Part, Name, Amount, MaxAmount) VALUES (".e($partid).", ".e($child->data['name']).", ".e($child->data['amount']).", ".e($child->data['maxAmount']).");");
		}elseif ($child->type == "INTERNAL")
		{
			
		}elseif ($child->type == "EFFECTS")
		{
		}else{
			echo $child->type."|".$filename."\n";
		}
	}
}

function _import_resource_definition($package, $filename, $resource)
{
	$res = db_query("SELECT ID FROM Resource WHERE Package = ".e($package)." AND Name = ".e($resource->data['name']));
	if (count($res))
		return;
	$partid = db_insert("INSERT INTO Resource(Package, Name, Density) VALUES (".e($package).", ".e($resource->data['name']).", ".e($resource->data['density']).");");
}
?>