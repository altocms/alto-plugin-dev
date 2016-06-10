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
class DevRouter extends Router {

    protected $aLog = array();
    protected $iLogLevel = 0;
    protected $bLogEnabled = false;

    public function __construct() {

        parent::__construct();
        $this->bLogEnabled = C::Get('plugin.dev.logs.router.enabled');
    }

    public function __destruct() {

        if (!$this->bLogEnabled) {
            return;
        }

        $aUrlParts = F::ParseUrl();
        $sUrl = $aUrlParts['base'] . $aUrlParts['path'];
        if (!empty($aUrlParts['query'])) {
            $sUrl .= '?' . $aUrlParts['query'];
        }
        $sDir = C::Get('plugin.dev.logs.router.dir');
        $sFile = $sDir
            . (!empty($aUrlParts['host']) ? preg_replace('/[^a-zA-Z0-9]/', '-', trim($aUrlParts['host'], '/')) : '')
            . '_' . (!empty($aUrlParts['path']) ? preg_replace('/[^a-zA-Z0-9]/', '-', trim($aUrlParts['path'], '/')) : '')
            . '_' . $_SERVER['REQUEST_TIME_FLOAT']
            . '.log';

        $sData = '# ' . $sUrl . "\n";
        foreach ($this->aLog as $aData) {
            $sData .= "###\n";
            if (!is_null($aData['result'])) {
                $iLast = sizeof($aData['text']) - 1;
                $aData['text'][$iLast] = $aData['text'][$iLast] . ' --> ' . $aData['result'];
            }
            foreach($aData['text'] as $sText) {
                $sData .= str_repeat(' ', 4 * $aData['level']) . $sText . "\n";
            }
            if (!is_null($aData['vars'])) {
                $sData .= str_repeat(' ', 4 * $aData['level']) . 'vars:';
                $iCnt = 0;
                foreach($aData['vars'] as $sVarName => $sVarValue) {
                    if ($iCnt++ == 0) {
                        $sData .= ' ' . $sVarName . ': ' . $sVarValue . "\n";
                    } else {
                        $sData .= str_repeat(' ', 4 * $aData['level']) . '      ' . $sVarName . ': ' . $sVarValue . "\n";
                    }
                }
            }
        }
        F::File_PutContents($sFile, $sData);
    }

    /**
     * @param      $sText
     * @param null $aOptions
     */
    protected function _addLog($sText, $aOptions = null) {

        if (!$this->bLogEnabled) {
            return;
        }

        $aText = array($sText);
        $sVars = null;
        $sResult = null;
        if ($aOptions) {
            if (is_array($aOptions)) {
                foreach ($aOptions as $sKey => $sOption) {
                    if ($sKey == 'result') {
                        $sResult = $sOption;
                    } elseif ($sKey == 'vars') {
                        $sVars = $sOption;
                    } else {
                        $aText[] = $sOption;
                    }
                }
            } else {
                $aText[] = (string)$aOptions;
            }
        }
        $this->aLog[] = array(
            'level' => $this->iLogLevel,
            'text' => $aText,
            'result' => $sResult,
            'vars' => $sVars,
        );
    }

    /**
     * @param $sText
     * @param null $aOptions
     */
    protected function _addLogBegin($sText, $aOptions = null) {

        $this->_addLog('[begin] ' . $sText, $aOptions);
        $this->iLogLevel++;
    }

    protected function _addLogEnd($sText, $aOptions = null) {

        --$this->iLogLevel;
        $this->_addLog('[end] ' . $sText, $aOptions);
    }

    /**
     * @param $xVar
     *
     * @return string
     */
    protected function _var($xVar) {

        $sResult = '';
        if (is_array($xVar)) {
            $sResult .= 'array(';
            $iCnt = 0;
            foreach($xVar as $sKey => $xVal) {
                if (!$iCnt) {
                    $sResult .= ',';
                }
                $sResult .= $this->_var($sKey) . '=>' . $this->_var($xVal);
            }
            $sResult .= ');';
        } elseif (is_string($xVar)) {
            $sResult = "'" . $xVar . "'";
        } else {
            $sResult = (string)$xVar;
        }
        return $sResult;
    }

    /**
     * 
     */
    public function Exec() {

        parent::Exec();
    }

    /**
     * @throws Exception
     */
    public function ExecAction() {

        $this->_addLogBegin('ExecAction()');
        parent::ExecAction();
        $this->_addLogEnd('ExecAction()');
    }

    /**
     * 
     */
    protected function ParseUrl() {

        $this->_addLogBegin('ParseUrl()');
        parent::ParseUrl();
        $this->_addLogEnd('ParseUrl()', array('vars' => array('$this->aCurrentUrl' => $this->_var($this->aCurrentUrl))));
    }

    /**
     * @return string
     */
    protected function GetRequestUri() {

        $sReq = parent::GetRequestUri();
        $this->_addLog('GetRequestUri()', array('result' => $this->_var($sReq)));
        return $sReq;
    }

    /**
     * @return string
     */
    protected function DefineActionClass() {

        $this->_addLogBegin('DefineActionClass()');
        $sActionClass = parent::DefineActionClass();
        $this->_addLogEnd('DefineActionClass()', array('result' => $this->_var($sActionClass)));
        return $sActionClass;
    }

    /**
     * @return null|string
     */
    protected function FindActionClass() {

        $this->_addLogBegin('FindActionClass()');
        $sActionClass = parent::FindActionClass();
        $this->_addLogEnd('FindActionClass()', array('result' => $this->_var($sActionClass)));
        return $sActionClass;
    }

    /**
     * @param string $sAction
     * @param null $sEvent
     * @return null|string
     */
    protected function DetermineClass($sAction, $sEvent = null) {

        $this->_addLogBegin('DetermineClass(' . $this->_var($sAction) . ')');
        $sActionClass = parent::DetermineClass($sAction, $sEvent);
        $this->_addLogEnd('DetermineClass()', array('result' => $this->_var($sActionClass)));
        return $sActionClass;
    }

}

// EOF