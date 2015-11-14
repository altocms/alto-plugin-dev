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

        if (!F::AjaxRequest() && !in_array(R::GetAction(), array('ajax', 'api'))) {
            if (C::Get('plugin.dev.handlers.whoops.enable')) {
                $bDisable = false;
                if (C::Get('plugin.dev.handlers.whoops.disable.ajax') && F::AjaxRequest()) {
                    $bDisable = true;
                } elseif ($aDisabledAction = C::Get('plugin.dev.handlers.whoops.disable.action')) {
                    $aDisabledAction = F::Array_Str2Array($aDisabledAction);
                    if (in_array(R::GetAction(), $aDisabledAction)) {
                        $bDisable = true;
                    }
                }
                if (!$bDisable) {
                    $oWhoops = new \Whoops\Run;
                    $oWhoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
                    $oWhoops->register();
                }
            }

            if (C::Get('plugin.dev.handlers.debugbar.enable')) {
                $aAssets = E::ModuleDev()->GetDebugAssets();
                foreach($aAssets['js'] as $sJsFile) {
                    E::ModuleViewer()->AppendScript($sJsFile, array('merge' => false));
                }
                foreach($aAssets['css'] as $sCssFile) {
                    E::ModuleViewer()->AppendStyle($sCssFile, array('merge' => false));
                }
            }
        }

        return true;
    }
}

// EOF