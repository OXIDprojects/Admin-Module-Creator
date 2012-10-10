<?php
/**
 * OXID language file. 
 * 
 * @package  mfAdminModuleCreator
 * @author   Stefan Krenz <stefan.krenz@mayflower.de>
 * @license  BSD
 * @version  0.1
 * @link     http://amc.projects.oxidforge.org/
 */

$sLangName = "Deutsch";

$aLang = array(
'charset'                                       => 'UTF-8',
'MF_AMC_HELP_MODULE_NAME'                       => 'Name of the new module.<br>This is used for output module directory and the name of the language files.<br>'
                                                 . '<ul><li>Output directory.: &quot;modules/mfAdminModuleCreator/output/&lt;moduleName&gt;/&quot;</li>'
                                                 . '<li>Language file: &quot;out/tpl/&lt;langId&gt;/&lt;moduleName&gt;_lang.php&quot;</li>'
                                                 . '</ul>',
'MF_AMC_HELP_CORE_CLASS_NAME'                   => 'This is used as base of the core classes.<br><br>'
                                                 . 'The following file will be created:<br>'
                                                 . '<ul><li>&quot;core/&lt;coreClassName&gt;.php&quot;</li>'
                                                 . '<li>&quot;core/&lt;coreClassName&gt;list.php&quot;</li>'
                                                 . '</ul><br><br>'
                                                 . 'Please use only the characters a-z and A-Z.',
'MF_AMC_HELP_VIEW_CLASS_NAME'                   => 'Base name for the admin views.<br><br>'
                                                 . 'The following file will be created:<br>'
                                                 . '<ul><li>admin/&lt;adminViewClassName&gt;.php</li>'
                                                 . '<li>admin/&lt;adminViewClassName&gt;_list.php</li>'
                                                 . '<li>admin/&lt;adminViewClassName&gt;_main.php</li>'
                                                 . '<li>out/admin/tpl/&lt;adminViewClassName&gt;.tpl</li>'
                                                 . '<li>out/admin/tpl/&lt;adminViewClassName&gt;_list.tpl</li>'
                                                 . '<li>out/admin/tpl/&lt;adminViewClassName&gt;_main.tpl</li>'
                                                 . '</ul>',
'MF_AMC_HELP_LANGUAGES'                         => 'Language definitions for the new module.<br>'
                                                 . 'The default languages are defined in \'MF_AMC_DEFAULT_LANGUAGES\' ident.<br><br>'
                                                 . '<u>Format:</u><ul><li>&lt;langId&gt; =&gt; &lt;Language-name&gt;</li></ul>Spaces before and after \'=&gt;\' will be removed automically.<br><br>'
                                                 . '<u>Example:</u><ul><li>en =&gt; English</li></ul>',
'MF_AMC_HELP_DB_TABLE_NAME'                     => 'Name of the database table, which are used by the core classes.<br><br><b><u>Hint:</u></b> The table <i>isn\'t</i> created automically.',
);