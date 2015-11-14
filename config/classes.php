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
$config['classes']['alias']['R'] = 'DevRouter';

$config['classes']['namespace'] = array(
    'Whoops' => '___path.dir.plugin.dev___vendor/whoops-1.1.7/src/Whoops',
    'DebugBar' => '___path.dir.plugin.dev___vendor/php-debugbar-1.10.5/src/DebugBar',
    'Psr\Log' => '___path.dir.plugin.dev___vendor/log-1.0.0/Psr/Log',
    'Symfony\Component\VarDumper' => '___path.dir.plugin.dev___vendor/var-dumper-2.6.7',
);

// EOF