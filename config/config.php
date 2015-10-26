<?php
/*
 * DON'T CHANGE THIS SECTION PLEASE
 */
F::IncludeFile(C::Get('path.dir.engine') . '/classes/core/Application.class.php');
F::IncludeFile('../classes/core/DevRouter.class.php');

$config['$root$']['module']['_autoLoad_'] = array(
    'Dev',
);

//$config['$root$']['classes']['alias']['R'] = 'DevRouter';

$config['$root$']['classes']['namespace'] = array(
    'Whoops' => '___path.dir.plugin.dev___vendor/whoops-1.1.7/src/Whoops',
    'DebugBar' => '___path.dir.plugin.dev___vendor/php-debugbar-1.10.5/src/DebugBar',
    'Psr\Log' => '___path.dir.plugin.dev___vendor/log-1.0.0/Psr/Log',
    'Symfony\Component\VarDumper' => '___path.dir.plugin.dev___vendor/var-dumper-2.6.7',
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
 * Handlers
 */
$config['handlers']['whoops']['enable'] = true;
$config['handlers']['debugbar']['enable'] = true;

// EOF