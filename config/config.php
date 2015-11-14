<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @Description: Advanced Community Engine
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

/**
 * @package plugins.dev
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

// Router logs
$config['logs']['router']['dir']            = '___sys.logs.dir___/router/'; // папка для логов
$config['logs']['router']['enabled']        = true;       // файл общего лога

// EOF