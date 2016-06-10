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
F::IncludeFile(C::Get('path.dir.engine') . '/classes/core/Application.class.php');
F::IncludeFile('../classes/core/DevRouter.class.php');

C::Set('module._autoLoad_', array_merge((array)C::Get('module._autoLoad_'), ['Dev']));

// EOF