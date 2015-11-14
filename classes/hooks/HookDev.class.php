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
class PluginDev_HookDev extends Hook {

    /**
     * Hooks registration
     */
    public function RegisterHook() {

        if (C::Get('plugin.dev.handlers.debugbar.enable')) {
            $this->AddHookTemplate('layout_body_end', array($this, 'TplHtmlBodyEnd'));
        }
    }

    public function TplHtmlBodyEnd() {

        return E::ModuleDev()->DebugRender();
    }

}

// EOF
