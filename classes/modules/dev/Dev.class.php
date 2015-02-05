<?php

class PluginDev_ModuleDev extends Module {

    protected $sPrefixText = '';
    protected $sPostfixText = '';

    public function Init() {

        $this->sPrefixText =
            '<?php' . PHP_EOL
            . '/*' . PHP_EOL
            . ' * This is autogenerated file' . PHP_EOL
            . ' */' . PHP_EOL;
        $this->sPostfixText = PHP_EOL . '// EOF';
    }

    /**
     * Generates aliases files during shutdown
     */
    public function Shutdown() {

        if (C::Get('plugin.dev.class_aliases.generate')) {
            $sDir = C::Get('plugin.dev.class_aliases.dir');
            $sFileName = $sDir . C::Val('plugin.dev.class_aliases.filename', 'Aliases.php');
            if (!is_file($sFileName) || C::Get('plugin.dev.class_aliases.force')) {
                if (F::File_CheckDir($sDir)) {
                    $aAliases = Loader::GetAliases();
                    $aAliasDefines = array();
                    foreach($aAliases as $sAlias => $aInfo) {
                        $aAliasDefines[] = PHP_EOL
                            . $this->_getClassDocs($sAlias)
                            . "class $sAlias extends {$aInfo['original']} { };";
                    }
                    $sAliasText = $this->sPrefixText . join(PHP_EOL, $aAliasDefines) . PHP_EOL . $this->sPostfixText;
                    F::File_PutContents($sFileName, $sAliasText);

                    $this->_createFunc($sDir);
                }
            }
        }
    }

    /**
     * Create PhpDocs for class Func
     *
     * @param string $sDir
     */
    protected function _createFunc($sDir) {

        $sFile = $sDir . 'F.php';
        if (!is_file($sFile) || C::Get('plugin.dev.class_aliases.force')) {
            $sText = $this->sPrefixText;
            $sText .= '/**' . PHP_EOL;
            //$sText .= $this->_getMethodsDocsFunc('', 'F');
            $aExtensions = Func::_getExtensions();
            if (isset($aExtensions['Main'])) {
                $sText .= $this->_getMethodsDocsFunc('', $aExtensions['Main']);
            }
            foreach($aExtensions as $sExtension => $sClass) {
                if ($sExtension != 'Main') {
                    $sText .= $this->_getMethodsDocsFunc($sExtension . '_', $sClass);
                }
            }

            $sText .= ' */' . PHP_EOL;
            $sText .= 'class F { }' . PHP_EOL;
            $sText .= $this->sPostfixText;
            F::File_PutContents($sDir . 'F.php', $sText);
        }
    }

    protected function _getMethodsDocsFunc($sPrefix, $sClass) {

        $sText = '';
        $oReflectionClass = new ReflectionClass($sClass);
        $aMethods = $oReflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($aMethods as $oMethod) {
            $sComment = $oMethod->getDocComment();
            if (preg_match('/@return\s+([a-z|\[\]]+)/i', $sComment, $aM)) {
                $ReturnType = $aM[1];
            } else {
                $ReturnType = '';
            }
            $aParams = $oMethod->getParameters();
            $sParams = '';
            if ($aParams) {
                foreach($aParams as $oParam) {
                    if ($sParams) {
                        $sParams .= ', ';
                    }
                    $sParams .= '$' . $oParam->getName();
                    if ($oParam->isOptional()) {
                        $xValue = $oParam->getDefaultValue();
                        if (is_bool($xValue)) {
                            $xValue = ($xValue ? 'true' : 'false');
                        } elseif (is_null($xValue)) {
                            $xValue = 'null';
                        } elseif (is_string($xValue)) {
                            $xValue = "'$xValue'";
                        } elseif (is_array($xValue)) {
                            $xValue = 'array()';
                        }
                        $sParams .= '=' . $xValue;
                    }
                }
            }
            $sText .= ' * @method static ' . $ReturnType . ' ' . $sPrefix . $oMethod->getName() . '(' . $sParams . ')' . PHP_EOL;
        }
        return $sText;
    }

    /**
     * Create PgpDocs for the class
     *
     * @param string $sClassName
     *
     * @return string
     */
    protected function _getClassDocs($sClassName) {

        $sText = '/**' . PHP_EOL
            . ' * Class ' . $sClassName . PHP_EOL
            . $this->_getMethodsDocs($sClassName)
            . ' */' . PHP_EOL;
        return $sText;
    }

    protected function _getMethodsDocs($sClassName) {

        $sResult = '';
        if ($sClassName == 'E') {
            $aModules = $this->_getLoadedModules();
            if ($aModules) {
                $aModules = array_keys($aModules);
            }
            $aModuleList = array();
            foreach ($aModules as $sFullModuleName) {
                $n = strpos($sFullModuleName, 'Module');
                if ($n) {
                    $sModuleName = substr($sFullModuleName, $n);
                } else {
                    $sModuleName = $sFullModuleName;
                }
                $aModuleList[$sModuleName] = $sFullModuleName;
            }
            if ($aModuleList) {
                foreach($aModuleList as $sModuleName => $sFullModuleName) {
                    $sResult .= ' * @method static ' . $sFullModuleName . ' ' . $sModuleName . '()' . PHP_EOL;
                }
            }
        }
        return $sResult;
    }

    protected function _getLoadedModules() {

        return E::getInstance()->GetLoadedModules();
    }
}

// EOF