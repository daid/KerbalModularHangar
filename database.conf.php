<?php
$databaseScheme = array();

$table = array();
$table['ID'] = 'ID';
$table['Name'] = 'STRING';
$databaseScheme['Package'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Package'] = 'INDEX';
$table['URL'] = 'STRING';
$table['Name'] = 'TEXT';
$table['Author'] = 'STRING';
$table['ShortDescription'] = 'TEXT';
$table['LongDescription'] = 'TEXT';
$table['UseInstructions'] = 'TEXT';
$table['InstallInstructions'] = 'TEXT';
$table['Download_URL'] = 'STRING';
$databaseScheme['SpacePortData'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Package'] = 'INDEX';
$table['URL'] = 'STRING';
$table['FirstPostContents'] = 'TEXT';
$table['Author'] = 'STRING';
$databaseScheme['ForumData'] = $table;

$table = array();
$table['ID'] = 'ID';
$table['Package'] = 'INDEX';
$table['Name'] = 'STRING';
$table['Filename'] = 'STRING';
$databaseScheme['Part'] = $table;


?>