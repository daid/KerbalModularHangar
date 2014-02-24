<?php
$databaseScheme = array();

$table = array();
$table['ID'] = 'ID';
$table['Name'] = 'STRING INDEX';
$databaseScheme['Package'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Package'] = 'INT INDEX';
$table['URL'] = 'STRING INDEX';
$table['Name'] = 'STRING INDEX';
$table['Author'] = 'STRING';
$table['ShortDescription'] = 'TEXT';
$table['LongDescription'] = 'TEXT';
$table['UseInstructions'] = 'TEXT';
$table['InstallInstructions'] = 'TEXT';
$table['Download_URL'] = 'STRING';
$databaseScheme['SpacePortData'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Package'] = 'INT INDEX';
$table['URL'] = 'STRING INDEX';
$table['FirstPostContents'] = 'TEXT';
$table['Author'] = 'STRING';
$databaseScheme['ForumData'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Package'] = 'INT INDEX';
$table['Name'] = 'STRING INDEX';
$table['Filename'] = 'STRING';
$databaseScheme['Part'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Part'] = 'INT INDEX';
$table['Name'] = 'STRING INDEX';
$table['Value'] = 'STRING';
$databaseScheme['PartProperty'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Part'] = 'INT INDEX';
$table['Name'] = 'STRING';
$databaseScheme['PartModule'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['PartModule'] = 'INT INDEX';
$table['Name'] = 'STRING INDEX';
$table['Value'] = 'STRING';
$databaseScheme['PartModuleProperty'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Part'] = 'INT INDEX';
$table['Name'] = 'STRING INDEX';
$table['Amount'] = 'INT';
$table['MaxAmount'] = 'INT';
$databaseScheme['PartResource'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Package'] = 'INT INDEX';
$table['Name'] = 'STRING INDEX';
$table['Density'] = 'FLOAT';
$databaseScheme['Resource'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Package'] = 'INT INDEX';
$table['Filename'] = 'STRING';
$databaseScheme['Plugin'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Plugin'] = 'INT INDEX';
$table['Name'] = 'STRING | INDEX';
$databaseScheme['PluginPartModule'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Package'] = 'INT INDEX';
$table['Name'] = 'STRING';
$table['Description'] = 'TEXT';
$table['Filename'] = 'STRING';
$databaseScheme['Craft'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Craft'] = 'INT INDEX';
$table['Name'] = 'STRING | INDEX';
$databaseScheme['CraftPart'] = $table;

?>