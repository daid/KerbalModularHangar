<?php

require("lib/import_package.php");

$ksp_path = "C:/KSP_win";

//Import the default parts.
import_package($ksp_path."/GameData/Squad/", "Squad");
//Import the dlls to get the PluginPartModule references.
import_package($ksp_path."/KSP_Data/Managed/", "Squad");
//Import the default ships
import_package($ksp_path."/Ships/", "Squad");
?>