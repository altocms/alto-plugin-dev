<?php
/*
 * DON'T CHANGE THIS SECTION PLEASE
 */
$config['$root$']['module']['_autoLoad_'] = array(
    'Dev',
);

$config['$root$']['classes']['namespace'] = array(
    'Whoops' => '___path.dir.plugin.dev___vendor/whoops-1.1.5/src/Whoops',
);

/*
 * CONFIG OPTION
 */
$config['smarty']['options']['mark_templates'] = true;  //

/*
 * PhpDocs autogenertion
 */
$config['class_aliases'] = array(
    'generate' => true,             // надо ли генерить файл алиасов
    'force' => true,                // принудительная генерация файла алиасов, даже если он есть
    'filename' => 'Aliases.php',    // имя файла алиасов
    'dir' => '___path.root.dir___/_dev/',
);

/*
 * Error handler
 */
$config['errors']['whoops'] = true;

// EOF