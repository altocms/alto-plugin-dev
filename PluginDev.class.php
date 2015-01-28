<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @Description: Advanced Community Engine
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

class PluginDev extends Plugin {

    protected $aDelegates = array(
    );

    protected $aInherits = array(
        'module' => array(
            'ModuleViewer',
            'ModuleDev',
        ),
        'action' => array(
        ),
    );

    /**
     * Активация плагина
     */
    public function Activate() {

        return true;
    }

    /**
     * Инициализация плагина
     */
    public function Init() {

    }
}

// EOF