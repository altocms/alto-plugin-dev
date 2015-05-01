<?php
class PluginDev_ModuleViewer extends PluginDev_Inherits_ModuleViewer {

    const TEMPLATE_MARK_BEGIN = 'TEMPLATE BEGIN';
    const TEMPLATE_MARK_END = 'TEMPLATE END';

    protected function _tplInit() {

        parent::_tplInit();

        $this->oSmarty->registerFilter('pre', array($this, '_Prefilters'));
        $this->oSmarty->registerFilter('post', array($this, '_Postfilters'));
        $this->oSmarty->registerPlugin('modifier', 'value', 'PluginDev_ModuleDev::Value');
    }

    public function _Prefilters($sSource, Smarty_Internal_Template $oTemplate) {

        if (Config::Get('plugin.dev.smarty.options.mark_templates')) {
            $sSource = $this->_filter_mark_templates($sSource, $oTemplate);
        }
        return $sSource;
    }

    public function _Postfilters($sSource, Smarty_Internal_Template $oTemplate) {

        if (Config::Get('plugin.dev.smarty.options.mark_templates')) {
            $sSource = $this->_filter_mark_templates_fix($sSource, $oTemplate);
        }
        return $sSource;
    }

    public function _filter_mark_templates($sSource, Smarty_Internal_Template $oTemplate) {

        $sTemplateFile = F::File_LocalDir($oTemplate->smarty->_current_file);
        if ($sTemplateFile) {
            $sTemplateFile = '@' . $sTemplateFile;
        } else {
            $sTemplateFile = $oTemplate->smarty->_current_file;
        }

        $nLevel = intval(E::Cache_Get('smarty.options.mark_template_lvl', 'tmp'));

        $sSource = ($nLevel ? "\n\n" : "")
            . '<!-- ' . self::TEMPLATE_MARK_BEGIN . ' [lvl:' . $nLevel . ', tpl:' . $sTemplateFile . '] -->' . ($nLevel ? "\n" : "")
            . $sSource . ($nLevel ? "\n" : "")
            . '<!-- ' . self::TEMPLATE_MARK_END . ' [lvl:' . $nLevel . ', tpl:' . $sTemplateFile . '] -->' . ($nLevel ? "\n" : "");

        E::Cache_Set('smarty.options.mark_template_lvl', ++$nLevel, array(), 0, 'tmp');
        return $sSource;
    }

    function _filter_mark_templates_fix($sSource, Smarty_Internal_Template $oTemplate) {

        if (preg_match('/^(\s*<!--\s+' . preg_quote(self::TEMPLATE_MARK_BEGIN) . '\s+[^>]+>\s*)(<!DOCTYPE\s[^>]+>)/siu', $sSource, $aM)) {
            $sPreStr = $aM[1];
            $sSource = $aM[2] . "\n" . $aM[1] . substr($sSource, strlen($aM[1]) + strlen($aM[2]));
        }

        return $sSource;
    }

}
// EOF