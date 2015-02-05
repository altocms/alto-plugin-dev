<?php
/*
 * DON'T CHANGE THIS SECTION PLEASE
 */
$config['$root$']['module']['_autoLoad_'] = array(
    'Dev',
);

/*
 * CONFIG OPTION
 */
$config['smarty']['options']['mark_templates'] = true;  //

$config['class_aliases'] = array(
    'generate' => true,             // надо ли генерить файл алиасов
    'force' => true,                // принудительная генерация файла алиасов, даже если он есть
    'filename' => 'Aliases.php',    // имя файла алиасов
    'dir' => '___path.root.dir___/_dev/',
);

// EOF