<?php
/**
 * Admin Module Creator main view class.
 *
 * @package  mfAdminModuleCreator
 * @author   Stefan Krenz <stefan.krenz@mayflower.de>
 * @license  BSD
 * @version  0.1
 * @link     http://amc.projects.oxidforge.org/
 */
class mf_admin_module_creator_main extends oxAdminDetails
{
    /**
     * Module version
     * @var string
     */
    const VERSION = '0.1b';

    /**
     * Default permissions for created directories.
     *
     * @var integer
     */
    private $_iDirectoryPermissions = 0775;

    /**
     * Default permissions for created Files.
     *
     * @var integer
     */
    private $_iFilePermissions = 0775;

    /**
     * XML-Document for navigation menu.
     *
     * @var DOMDocument
     */
    private $_oNavigationDom = null;

    /**
     * Basepath on disk to OXID shop.
     *
     * @var string
     */
    private $_sApplicationDir = null;

    /**
     * Mode to build a new tab in an existing menu entry.
     *
     * @var int
     */
    const BUILD_MODE_NEW_TAB = 0;

    /**
     * Mode to build a new submenu entry with tabs.
     *
     * @var int
     */
    const BUILD_MODE_NEW_SUBMENU = 1;

    /**
     * Current build mode
     *
     * @var int
     */
    private $_iBuildMode = self::BUILD_MODE_NEW_TAB;

    /**
     * Log for filesystem action results.
     *
     * @var array
     */
    private $_aResultLog = array();

    /**
     * class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $oNavDom = clone $this->getNavigation()->getDomXml();
        $this->setNavigationDOM($oNavDom);
        $this->setApplicationDir(realpath(dirname(__FILE__) . '/..') . '/');
    }

    /**
     * Prepare the data for presentation to and passes it to the template engine.
     * Returns the filename of the template.
     *
     * @return string Name of template to render.
     */
    public function render()
    {

        parent::render();

        $sChecked = 'checked="checked"';
        $aEditval = oxConfig::getParameter('editval');

        $this->_aViewData['editval'] = (object) $aEditval;
        $this->_aViewData['menustructure'] =
            $this->getNavigationDom()->documentElement->childNodes;
        $this->_aViewData['createdModules'] = $this->getCreatedModules();

        $sTemplateName = "mf_admin_module_creator_main.tpl";
        return $sTemplateName;
    }

    /**
     * Collecting the data for the new admin module and created it, based on the data entered.
     *
     * @return void
     */
    public function createModule()
    {
        $aEditval = oxConfig::getParameter('editval');

        $sErrorMessage = $this->_checkConfig($aEditval);

        if (count((array) $sErrorMessage) == 0) {
            $aVarAssignment = array(
                '%MODULE_NAME%' => str_replace(' ', '_', $aEditval['moduleName']),
                '%CORE_CLASS_NAME%' => str_replace(' ', '_', $aEditval['coreClassName']),
                '%ADMIN_VIEW_CLASS_NAME%' => str_replace(' ', '_', $aEditval['adminViewClassName']),
                '%DB_TABLE_NAME%' => str_replace(' ', '_', $aEditval['dbTableName']),
            );
            $oNav = $this->_getNavDOM();
            $oMenuLocationElement = $oNav->getElementById($aEditval['menuLocation']);
            $aXmlPathData = $this->_buildPathData($oMenuLocationElement);
            $iMenuLevel = count((array) $aXmlPathData);
            if ($iMenuLevel == 4) {
                $this->_setBuildMode(self::BUILD_MODE_NEW_TAB);
                $aXmlPathData[] = array(
                    'name' => 'TAB',
                    'value' => null,
                    'attributes' => array(
                        'id' => 'tbcl' . $aEditval['moduleName'] . '_main',
                        'cl' => $aEditval['adminViewClassName'] . '_main',
                    ),
                );
            } elseif ($iMenuLevel == 3) {
                $this->_setBuildMode(self::BUILD_MODE_NEW_SUBMENU);
                $aXmlPathData[] = array(
                    'name' => 'SUBMENU',
                    'value' => null,
                    'attributes' => array(
                        'id' => 'mx' . $aEditval['moduleName'],
                        'cl' => $aEditval['adminViewClassName'],
                        'list' => $aEditval['adminViewClassName'] . '_list',
                    ),
                );
                $aXmlPathData[] = array(
                    'name' => 'TAB',
                    'value' => null,
                    'attributes' => array(
                        'id' => 'tbcl' . $aEditval['moduleName'] . '_main',
                        'cl' => $aEditval['adminViewClassName'] . '_main',
                    ),
                );
            }

            $oXmlBuilder = oxNew('mfMenuXmlBuilder');
            $oXmlBuilder->buildMenuXml($aXmlPathData);

            $sModuleDir = $this->getApplicationDir() . 'modules/mfAdminModuleCreator/';
            $sCodeTemplatesDir = $sModuleDir . 'codeTemplates/';

            if (!$this->_writeFile(
                'modules/' . $aEditval['moduleName'] . '/menu.xml',
                $oXmlBuilder->getXmlDocument()->saveXML())) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_MENU';
                return;
            }

            $aEditLangs = explode(PHP_EOL, $aEditval['languages']);
            $aLanguages = array();
            foreach ($aEditLangs as $sLanguage) {
                list($sLangId, $sLangName) = explode('=>', $sLanguage);
                if (!$this->_applySourceTemplate(
                    'out/admin/lang/_amc_lang_.php',
                    'out/admin/' . trim($sLangId) . '/' . $aEditval['moduleName'] . '_lang.php',
                    array('%LANGUAGE_NAME%' => trim($sLangName)))
                ) {
                    $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_LANG';
                    return;
                }
                $aLanguages[$sLangId] = $sLangName;
            }

            if (!$this->_applySourceTemplate(
                'out/admin/tpl/_amc_.tpl',
                'out/admin/tpl/' . $aEditval['moduleName'] . '.tpl',
                $aVarAssignment)
            ) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_TEMPLATE';
                return;
            }

            if (!$this->_applySourceTemplate(
                'out/admin/tpl/_amc_list_.tpl',
                'out/admin/tpl/' . $aEditval['moduleName'] . '_list.tpl',
                $aVarAssignment)
            ) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_TEMPLATE';
                return;
            }

            if (!$this->_applySourceTemplate(
                'out/admin/tpl/_amc_main_.tpl',
                'out/admin/tpl/' . $aEditval['moduleName'] . '_main.tpl',
                $aVarAssignment)
            ) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_TEMPLATE';
                return;
            }

            if (!$this->_applySourceTemplate(
                'core/_amc_.php',
                'core/' . strtolower($aEditval['coreClassName']) . '.php',
                $aVarAssignment)
            ) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_CORE_CLASS';
                return;
            }

            if (!$this->_applySourceTemplate(
                'core/_amc_list_.php',
                 'core/' . strtolower($aEditval['coreClassName']) . 'list.php',
                $aVarAssignment)
            ) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_CORE_CLASS';
                return;
            }

            if (!$this->_applySourceTemplate(
                'admin/_amc_.php',
                'admin/' . strtolower($aEditval['adminViewClassName']) . '.php',
                $aVarAssignment)
            ) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_VIEW_CLASS';
                return;
            }

            if (!$this->_applySourceTemplate('admin/_amc_list_.php',
                'admin/' . strtolower($aEditval['adminViewClassName']) . '_list.php',
                $aVarAssignment)
            ) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_VIEW_CLASS';
                return;
            }

            if (!$this->_applySourceTemplate(
                'admin/_amc_main_.php',
                'admin/' . strtolower($aEditval['adminViewClassName']) . '_main.php',
                $aVarAssignment)
            ) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_VIEW_CLASS';
                return;
            }

            $sModuleConfig = '[module]' . PHP_EOL
                . 'moduleName = ' . $aEditval['moduleName'] . PHP_EOL
                . 'coreClassName = ' . $aEditval['coreClassName'] . PHP_EOL
                . 'adminViewClassName = ' . $aEditval['adminViewClassName'] . PHP_EOL
                . 'dbTableName = ' . $aEditval['dbTableName'] . PHP_EOL
                . 'creationDate = ' . time() . PHP_EOL;

            $sHtaccess = 'Order deny,allow' . PHP_EOL
                      . 'Deny from all' . PHP_EOL;
            if (!$this->_writeFile(
                'modules/' . $aEditval['moduleName'] . '/.htaccess',
                $sHtaccess)
            ) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_HTACCESS';
                return;
            }

            foreach ($this->getResultLog() as $sLogEntry) {
                if ($sLogEntry['result'] == 'success') {
                    $sModuleConfig .= 'files[] = ' . $sLogEntry['filename'] . PHP_EOL;
                }
            }

            $sModuleConfig .= 'files[] = ' . 'modules/' . $aEditval['moduleName'] . '/.amc' . PHP_EOL
                           . '[languages]' . PHP_EOL;

            foreach ($aLanguages as $sLangId => $sLanguage) {
                $sModuleConfig .= $sLangId . ' = ' . $sLanguage . PHP_EOL;
            }

            if (!$this->_writeFile(
                'modules/' . $aEditval['moduleName'] . '/.amc',
                $sModuleConfig)
            ) {
                $this->_aViewData['errorMessage']->buildError = 'MF_AMC_MESSAGE_CANT_CREATE_INFO_FILE';
                return;
            }
            $this->_finalize($sErrorMessage);
        } else {
            $this->_finalize($sErrorMessage);
        }
    }

    /**
     * Removes an admin module that was created with the Admin Module Creator.
     *
     * @return void
     */
    public function deleteModule()
    {
        $aEditval = oxConfig::getParameter('editval');
        $aResultLog = array();

        $aModuleInfo = $this->getModuleInfo($aEditval['deleteModuleName']);
        foreach ($aModuleInfo['module']['files'] as $sFile) {
            $sModuleFile = $this->getApplicationDir() . $sFile;
            $sUnlinkResult = @unlink($sModuleFile);
            $this->addResultLogEntry(array(
                'filename' => $sModuleFile,
                'result' => $sUnlinkResult ? 'success' : 'error',
                'type' => 'file_delete',
            ));
        }

        $sModuleFile = $this->getApplicationDir() . 'modules/' . $aEditval['deleteModuleName'];
        $sUnlinkResult = @rmdir($sModuleFile);
        $this->addResultLogEntry(array(
            'filename' => $sModuleFile,
            'result' => $sUnlinkResult ? 'success' : 'error',
            'type' => 'remove_dir',
        ));

        $this->_passResultLogToTemplate();
        $this->_deleteCacheFiles();
    }

    /**
     * Get information about the selected module and passes it to the template engine.
     *
     * @return void
     */
    public function moduleInfo()
    {
        $aEditval = oxConfig::getParameter('editval');
        $this->_aViewData['moduleInfo'] = $this->getModuleInfo($aEditval['infoModuleName']);
    }

    /**
     * Removes filesystem changes.
     *
     * @return void
     */
    private function _rollback()
    {
        $aResultLog = $this->getResultLog();
        foreach ($aResultLog as $sLogEntry) {
            if ($sLogEntry['result'] == 'success') {
                $sUnlinkResult = @unlink($this->getApplicationDir(). $sLogEntry['filename']);
                $sLogEntry = array(
                    'filename' => $sLogEntry['filename'],
                    'result' => $sUnlinkResult ? 'success' : 'error',
                    'type' => 'rollback',
                );
                $this->addResultLogEntry($sLogEntry);
            }
        }
    }

    /**
     * Set data for template engine.
     *
     * @param string $sErrorMessage Error message, if any error occurs.
     *
     * @return void
     */
    private function _finalize($sErrorMessage = null)
    {
        $this->_passResultLogToTemplate();
        $this->_aViewData['errorMessage'] = $sErrorMessage;
        $this->_deleteCacheFiles();
    }

    /**
     * Adds an entry to the result log.
     *
     * @param array $aLogEntry Entry to add.
     *
     * @return void
     */
    public function addResultLogEntry($aLogEntry)
    {
        $this->_aResultLog[] = $aLogEntry;
    }

    /**
     * Passes the result log to template.
     *
     * @return void
     */
    private function _passResultLogToTemplate()
    {
        $aResultLog = $this->getResultLog();
        $this->_aViewData['resultLog'] = $aResultLog;
    }

    /**
     * Get the result log.
     *
     * @return array Result log.
     */
    public function getResultLog()
    {
        $aResultLog = $this->_aResultLog;
        return $aResultLog;
    }

    /**
     * Copies folders and files recursively to another directory.
     *
     * @param string $sSrc Source directory
     * @param string $sDst Target directory
     *
     * @return void
     */
    private function _recurseCopy($sSrc, $sDst)
    {
        $oDir = opendir($sSrc);
        @mkdir($sDst);
        while (false !== ($sFile = readdir($oDir))) {
            if (($sFile != '.') && ($sFile != '..')) {
                if (is_dir($sSrc . '/' . $sFile)) {
                    $this->_recurseCopy($sSrc . '/' . $sFile, $sDst . '/' . $sFile);
                } else {
                    copy($sSrc . '/' . $sFile, $sDst . '/' . $sFile);
                }
            }
        }
        closedir($oDir);
    }

    /**
     * Get the current directory permissions.
     *
     * @return int Directory permission.
     */
    public function getDirectoryPermissions()
    {
        $iDirectoryPermissions = $this->_iDirectoryPermissions;
        return $iDirectoryPermissions;
    }

    /**
     * Get the current file permissions.
     *
     * @return int File permissions.
     */
    public function getFilePermissions()
    {
        $iFilePermissions = $this->_iFilePermissions;
        return $iFilePermissions;
    }

    /**
     * Loads a source template, replaces the placeholders and write it to the target directory.
     *
     * @param string $sTemplateFile   Name of source template
     * @param string $sTargetFileName Name of target
     * @param array  $aReplaceData    Replacements
     *
     * @return bool True if succeeded, false if not.
     */
    private function _applySourceTemplate($sTemplateFile, $sTargetFileName, $aReplaceData = array())
    {
        $sModuleDir = $this->getApplicationDir() . 'modules/mfAdminModuleCreator/';
        $sCodeTemplatesDir = $sModuleDir . 'codeTemplates/';

        $sTemplateContent = file_get_contents($sCodeTemplatesDir . $sTemplateFile);
        $sTemplateContent = $this->replaceVars($aReplaceData, $sTemplateContent);
        $blResult = $this->_writeFile($sTargetFileName, $sTemplateContent);
        return $blResult;
    }

    /**
     * Writes data to the specified file and generates a log entry and returns it.
     *
     * @param string $sFileName    Name of file to write.
     * @param string $sFileContent Content to write.
     * @param bool   $blOverwrite  If set to true, an existing file is overwritten, otherwise not.
     *
     * @return bool True if succeeded, false if not.
     */
    private function _writeFile($sFileName, $sFileContent, $blOverwrite = false)
    {
        $sTargetFileName = $this->getApplicationDir() . $sFileName;
        $sTargetDir = dirname($sTargetFileName);
        if ($blOverwrite || !file_exists($sTargetFileName)) {
            if (!file_exists($sTargetDir)) {
                $blDirCreated = mkdir($sTargetDir, $this->getDirectoryPermissions(), true);
                $aLogEntry = array(
                    'filename' => str_replace($this->getApplicationDir(), '', $sTargetDir),
                    'result' => $blDirCreated ? 'success' : 'error',
                    'type' => 'dir_create',
                );
                $this->addResultLogEntry($aLogEntry);
            }
            $iBytesWritten = file_put_contents($sTargetFileName, $sFileContent);
            chmod($sTargetFileName, $this->getFilePermissions());
            $aLogEntry = array(
                'filename' => str_replace($this->getApplicationDir(), '', $sTargetFileName),
                'result' => ($iBytesWritten !== false) ? 'success' : 'error',
                'type' => 'file_write',
            );
        } else {
            $aLogEntry = array(
                'filename' => str_replace($this->getApplicationDir(), '', $sTargetFileName),
                'result' => 'error',
                'type' => 'file_write',
            );
        }

        $this->addResultLogEntry($aLogEntry);

        if ($aLogEntry['result'] != 'success') {
            $this->_rollback();
            $this->_finalize();
            return false;
        } else {
            return true;
        }
    }

    /**
     * Replaces variables in a string with the assigned data.
     *
     * @param array  $aVariableData Associative array with vars and its data.
     * @param string $sInputString  String which includes vars to replace.
     *
     * @return string String where the vars are replaced by data.
     */
    public function replaceVars($aVariableData, $sInputString)
    {
        $sOutputString = $sInputString;
        foreach ($aVariableData as $sVarName => $sVarValue) {
            $sOutputString = str_replace($sVarName, $sVarValue, $sOutputString);
        }
        return $sOutputString;
    }

    /**
     * Get validated navigation XML-Document.
     * This function gets the original DOMDocument and
     * add document type definition for validate.
     *
     * @return DOMDocument New navigation XML-Document.
     */
    private function _getNavDOM()
    {
        $oNav = $this->getNavigationDom()->documentElement;
        $oDomImpl = new DOMImplementation();
        $oDtd = $oDomImpl->createDocumentType('OX', null, 'mfmenu.dtd');
        $oNewNav = $oDomImpl->createDocument('', '', $oDtd);
        $oNewNode = $oNewNav->importNode($oNav, true);
        $oNewNav->appendChild($oNewNode);
        $blValidXml = @$oNewNav->validate();
        if (!$blValidXml) {
            $sOutputXml = str_replace('><', '>' . PHP_EOL . '<', $oNewNav->saveXML());
            $oNewNav->loadXML($sOutputXml);
        };
        return $oNewNav;
    }

    /**
     * Build an array, that contains the data for the modules menu.xml.
     *
     * @param DOMNode $oNode Node where the new menu entry will be created.
     *
     * @return array Data for new navigation menu entry.
     */
    private function _buildPathData(DOMNode $oNode)
    {
        $aPathData = array();
        do {
            $aNodeData = array(
                'name' => $oNode->nodeName,
                'value' => null,
            );
            if ($oNode->hasAttributes()) {
                $aNodeAttributes = array();
                $iAttributeIndex = 0;
                $oNodeAttribute = $oNode->attributes->item($iAttributeIndex);
                while ($oNodeAttribute !== null) {
                    if ($oNodeAttribute->nodeName != 'link') {
                        $aNodeAttributes[$oNodeAttribute->nodeName] = $oNodeAttribute->nodeValue;
                    }
                    $iAttributeIndex++;
                    $oNodeAttribute = $oNode->attributes->item($iAttributeIndex);
                }
                $aNodeData['attributes'] = $aNodeAttributes;
            }
            $oNode     = $oNode->parentNode;
            $aPathData[] = $aNodeData;

        } while (!$oNode instanceof DOMDocument);
        krsort($aPathData);
        $aResult = array_values($aPathData);
        return $aResult;
    }

    /**
     * Get the current build mode.
     *
     * @return int Current build mode.
     */
    private function _getBuildMode()
    {
        $iBuildMode = $this->_iBuildMode;
        return $iBuildMode;
    }

    /**
     * Set the current build mode.
     *
     * @param int $iNewBuildMode New build mode.
     *
     * @return void
     */
    private function _setBuildMode($iNewBuildMode)
    {
        $this->_iBuildMode = $iNewBuildMode;
    }

    /**
     * Set the current navigation XML-Document.
     *
     * @param DOMDocument $oNavigationDom Current navigation XML-Document.
     *
     * @return void
     */
    public function setNavigationDOM(DOMDocument $oNavigationDom)
    {
        $oMenuNodes = $oNavigationDom->getElementsByTagName('OXMENU');
        $oOxNode = $oNavigationDom->getElementsByTagName('OX');
        foreach ($oMenuNodes as $oMenuNode) {
            if ($oMenuNode->getAttribute('id') == 'mxservicearea') {
                try {
                    $oOxNode->item(0)->removeChild($oMenuNode);
                } catch (Exception $oException) {
                }
            }
        }
        $this->_oNavigationDom = $oNavigationDom;
    }

    /**
     * Get the navigation DOMDocument.
     *
     * @return DOMDocument Navigation DOMDocument.
     */
    public function getNavigationDom()
    {
        $oNavigationDom = $this->_oNavigationDom;
        return $oNavigationDom;
    }

    /**
     * Get the current OXID application directory.
     *
     * @return string Current OXID application directory.
     */
    public function getApplicationDir()
    {
        $sApplicationDir = $this->_sApplicationDir;
        return $sApplicationDir;
    }

    /**
     * Set the OXID application directory.
     *
     * @param string $sAppDir New OXID application directory.
     *
     * @return void
     */
    public function setApplicationDir($sAppDir)
    {
        $this->_sApplicationDir = $sAppDir;
    }

    /**
     * Get all the modules that were created with the Admin Module Creator.
     *
     * @return array List of AMC modules.
     */
    public function getCreatedModules()
    {
        $aCreatedModules = array();
        $oIterator = new DirectoryIterator($this->getApplicationDir() . 'modules');
        foreach ($oIterator as $oEntry) {
            if (!$oEntry->isDir()) {
                continue;
            }
            $sInfoFile = $oEntry->getPathname() . '/.amc';
            if (file_exists($sInfoFile)) {
                $aModuleInfo = parse_ini_file($sInfoFile, true);
                $aCreatedModules[$aModuleInfo['module']['moduleName']] = $aModuleInfo['module']['moduleName'] . ' '
                    . date('d.m.Y H:i:s', $aModuleInfo['module']['creationDate']);
            }
        }
        return $aCreatedModules;
    }

    /**
     * Get information about a specific module.
     *
     * @param string $sModuleName Name of module.
     *
     * @return array|false Module information or false.
     */
    public function getModuleInfo($sModuleName)
    {
        $sModuleName = str_replace('../', '', $sModuleName);
        $sInfoFile = $this->getApplicationDir() . 'modules/' . $sModuleName . '/.amc';
        if (file_exists($sInfoFile)) {
            $aResult = parse_ini_file($sInfoFile, true);
            $aResult['menuId'] = 'mx' . $aResult['module']['moduleName'];
            return $aResult;
        }

        return false;
    }

    /**
     * Deletes files from cache. These files are language and menu cache files.
     *
     * @return void
     */
    private function _deleteCacheFiles()
    {
        $aDeleteCacheFiles = array(
            'oxeec_menu_*_xml.txt',
            'oxeec_langcache_*_basic.txt',
            '*.tpl.php',
            'de_dynscreen.xml'
        );
        $sCacheDir = $this->getApplicationDir() . 'tmp/';
        foreach ($aDeleteCacheFiles as $sDeleteCacheFile) {
            foreach (glob($sCacheDir . $sDeleteCacheFile) as $sDeleteFilename) {
                @unlink($sDeleteFilename);
            }
        }
    }

    /**
     * Get the current module Version
     *
     * @return string Current module version
     */
    public function getVersion()
    {
        $sVersion = self::VERSION;
        return $sVersion;
    }

    /**
     * Check the post data.
     *
     * @param array $aEditval Post data
     *
     * @return stdClass
     */
    private function _checkConfig($aEditval)
    {
        $oErrorMessage = new stdClass();

        if (empty($aEditval['moduleName'])) {
            $oErrorMessage->moduleName = 'MF_AMC_MESSAGE_NO_MODULE_NAME';
        }

        if (empty($aEditval['coreClassName'])) {
            $oErrorMessage->coreClassName = 'MF_AMC_MESSAGE_NO_CORE_CLASS_NAME';
        }

        if (empty($aEditval['adminViewClassName'])) {
            $oErrorMessage->adminViewClassName = 'MF_AMC_MESSAGE_NO_ADMIN_VIEW_CLASS_NAME';
        }

        if (empty($aEditval['dbTableName'])) {
            $oErrorMessage->dbTableName = 'MF_AMC_MESSAGE_NO_DB_TABLE_NAME';
        }

        if (empty($aEditval['languages'])) {
            $oErrorMessage->languages = 'MF_AMC_MESSAGE_NO_LANGUAGES';
        }

        if (empty($aEditval['menuLocation'])) {
            $oErrorMessage->menuLocation = 'MF_AMC_MESSAGE_NO_MENU_LOCATION';
        }

        return $oErrorMessage;
    }

    /**
     * Builds the translated (implode() isn't usable in a smarty template) path in the admin navigation tree.
     *
     * @param string $sModuleName Name of the module
     *
     * @return string
     */
    public function getModuleNavTreePath($sModuleName)
    {
        $oNav = new DOMDocument();
        $oNav->load("../modules/$sModuleName/menu.xml");
        $oNav = $oNav->documentElement;
        $oDomImpl = new DOMImplementation();
        $oDtd = $oDomImpl->createDocumentType('OX', null, 'mfmenu.dtd');
        $oXML = $oDomImpl->createDocument('', '', $oDtd);
        $oNewNode = $oXML->importNode($oNav, true);
        $oXML->appendChild($oNewNode);
        $oXML->validate();
        $oNode = $oXML->getElementById('tbcl' . $sModuleName . '_main');
        $aPath = array();
        $oLang = oxLang::getInstance();
        do {
            $sID = $oNode->getAttribute('id');
            $aPath[] = $oLang->translateString($sID);
            $oNode = $oNode->parentNode;
        } while ($oNode->nodeName != 'OX');
        krsort($aPath);
        $sPath = implode(' <span style="font-size:1.1em;font-weight:bold;">&#8658;</span> ', $aPath);
        return $sPath;
    }
}
