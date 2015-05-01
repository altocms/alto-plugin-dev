<?php

class PluginDev_ActionDev extends ActionPlugin {

    protected $aSqlQueries = array();
    protected $aSqlExpressions = array();
    protected $aSqlChains = array();

    public function RegisterEvent() {

        $this->AddEvent('config', 'EventConfig');
        //$this->AddEvent('sql', 'EventSql');
    }

    public function Access($sEvent) {

        if (parent::Access($sEvent)) {
            return E::IsAdmin();
        }
        return false;
    }

    public function EventIndex() {

    }

    protected function _prepareArray($aConfig, $sKey, $sPrefix) {

        $iIndex = array_search($sKey, array_keys($aConfig));
        $aConfig = array_slice($aConfig, $iIndex);
        $iLen = strlen($sPrefix);
        $aValue = array();
        $sCheckPrefix = $sPrefix . '.';
        foreach($aConfig as $sKey => $xVal) {
            if (strpos($sKey, $sCheckPrefix) !== 0) {
                break;
            }
            $sSubKey = substr($sKey, 0, $iLen);
            if (substr($sSubKey, -2) == '.0') {
                list($sPrefix, $xVal) = $this->_prepareArray($aConfig, $sSubKey, substr($sSubKey, 0, strlen($sSubKey) - 2));
                $aValue = $xVal;
                break;
            } else {
                $aValue[] = $xVal;
            }
        }
        return array($sPrefix, $aValue);
    }

    public function EventConfig() {

        $aConfig = C::Get(null, null, Config::LEVEL_CUSTOM, true);
        E::ModuleViewer()->Assign('aConfig', $aConfig);

        E::ModuleViewer()->AppendStyle(Plugin::GetTemplateDir(__CLASS__) . 'assets/css/style.config.css');
    }

    protected function _sqlParseRecords($sQuery) {

        if (preg_match('/--\s\[id\]([a-z0-9]+)(.+)\s--\s(\d+.*)\s--\s\[src\](.*)$/siu', $sQuery, $aP)) {
            $sSql = $aP[2];
            //$sSql = str_replace(array("\r\n", "\n", "\r", "\t"), ' ', $aP[2]);
            //$sSql = preg_replace('/[\s]{2,}/', ' ', $sSql);
            $aQuery = array(
                'id'  => $aP[1], // ID запроса
                'sql' => trim($sSql), // SQL код
                'res' => $aP[3], // результат
                'src' => F::File_LocalDir($aP[4]), // источник вызова
            );
            return $aQuery;
        }
        return null;
    }

    public function _cmpCnt($a, $b) {

        if ($a['cnt'] == $b['cnt']) {
            if (isset($a['len']) && isset($b['len'])) {
                if ($a['len'] == $b['len']) {
                    return 0;
                }
                return ($a['len'] > $b['len']) ? -1 : 1;
            }
            return 0;
        }
        return ($a['cnt'] > $b['cnt']) ? -1 : 1;
    }

    public function _minCnt($a) {

        return $a['cnt'] > 1;
    }

    protected function _chains($aStack) {

        $aChainList = array();
        $aStack = array_slice($aStack, 0, 12);
        foreach($aStack as $sQueryId) {
            if ($aChainList) {
                $aChainList[] = $sQueryId;
                $sChainId = md5(serialize($aChainList));
                if (!isset($this->aSqlChains[$sChainId])) {
                    $aSql = array();
                    foreach($aChainList as $sId) {
                        $aSql[] = $this->aSqlExpressions[$sId];
                    }
                    $this->aSqlChains[$sChainId] = array(
                        'cnt' => 1,
                        'list' => $aChainList,
                        'sql' => $aSql,
                        'len' => sizeof($aChainList),
                    );
                } else {
                    $this->aSqlChains[$sChainId]['cnt'] += 1;
                }
            } else {
                $aChainList[] = $sQueryId;
            }
        }
    }

    public function EventSql() {

        $sFile = C::Get('sys.logs.sql_query_file');
        $sFile = 'check.log';
        $sFile = C::Get('sys.logs.dir') . $sFile;
        $sContents = file_get_contents($sFile);

        $aQueryCnt = array();
        $aSourceCnt = array();
        $aStack = array();
        if (preg_match_all('#\[LOG:([^\]]+)\].*\[\[(.*)\]\]#siU', $sContents, $aM)) {
            foreach($aM[1] as $iQueryNumber => $sQueryId) {
                $sRecord = $aM[2][$iQueryNumber];
                if ($aRecord = $this->_sqlParseRecords($sRecord)) {
                    $this->aSqlQueries[$iQueryNumber] = $aRecord;

                    // повторяющиеся запросы
                    $sId = $aRecord['id'];
                    if (isset($aQueryCnt[$sId])) {
                        $aQueryCnt[$sId]['cnt'] += 1;
                        $aQueryCnt[$sId]['list'][] = $iQueryNumber;
                    } else {
                        $aQueryCnt[$sId] = array(
                            'cnt' => 1,
                            'list' => array($iQueryNumber),
                            'data' => $aRecord,
                        );
                        $this->aSqlExpressions[$sId] = $aRecord;
                    }

                    array_unshift($aStack, $sId);
                    $this->_chains($aStack);

                    // повторяющиеся точки вызова
                    $sId = md5($aRecord['src']);
                    if (isset($aSourceCnt[$sId])) {
                        $aSourceCnt[$sId]['cnt'] += 1;
                        $aSourceCnt[$sId]['list'][] = $iQueryNumber;
                    } else {
                        $aSourceCnt[$sId] = array(
                            'cnt' => 1,
                            'list' => array($iQueryNumber),
                            'data' => $aRecord,
                        );
                    }
                } elseif ($iPos = strpos($sRecord, '--')) {
                    /*
                    $sSql = substr($s, 0, $iPos);
                    $sId = md5($sSql);
                    $sRes = substr($s, $iPos);

                    $sSql = str_replace(array("\r\n", "\n", "\r", "\t"), ' ', $sSql);
                    $sSql = preg_replace('/[\s]{2,}/', ' ', $sSql);

                    $aRecord = array(
                        'id'  => $sId,
                        'sql' => trim($sSql),
                        'res' => $sRes,
                        'src' => '',
                    );
                    $aData[$iQueryNumber] = $aRecord;
                    $sHash = md5($sSql);
                    //$sHash = md5($aP[4]);
                    $aQueryCnt[$sHash][] = $iQueryNumber;
                    $iQueryNumber += 1;
                    */
                } else {
                    //
                    //var_dump($s);
                    //exit;
                }
                if ($iQueryNumber > 50) {
                    $s=1;
                }
            }
        }
        uasort($aQueryCnt, array($this, '_cmpCnt'));
        $aQueryCnt = array_filter($aQueryCnt, array($this, '_minCnt'));

        uasort($aSourceCnt, array($this, '_cmpCnt'));
        $aSourceCnt = array_filter($aSourceCnt, array($this, '_minCnt'));

        uasort($this->aSqlChains, array($this, '_cmpCnt'));
        $this->aSqlChains = array_filter($this->aSqlChains, array($this, '_minCnt'));

        E::ModuleViewer()->Assign('aQueryCnt', $aQueryCnt);
        E::ModuleViewer()->Assign('aSourceCnt', $aSourceCnt);
        E::ModuleViewer()->Assign('aChainsCnt', $this->aSqlChains);
        //arsort($this->aSqlChains);
        //var_dump($this->aSqlChains); exit;
        //var_dump($aQueryCnt); exit;
    }

}

// EOF