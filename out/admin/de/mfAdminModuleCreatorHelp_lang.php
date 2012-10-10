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
'MF_AMC_HELP_MODULE_NAME'                       => 'Name des Admin Moduls.<br>Dieser Name wird für das Ausgabeverz. und den Namen der Sprachdatei genutzt.<br>'
                                                 . '<ul><li>Ausgabeverz.: &quot;modules/mfAdminModuleCreator/output/&lt;moduleName&gt;/&quot;</li>'
                                                 . '<li>Sprachdatei: &quot;out/tpl/&lt;langId&gt;/&lt;moduleName&gt;_lang.php&quot;</li>'
                                                 . '</ul>',
'MF_AMC_HELP_CORE_CLASS_NAME'                   => 'Dieser Name wird als Basis für die Core-Klassen benutzt.<br><br>'
                                                 . 'Auf dieser Basis werden die Dateien<br>'
                                                 . '<ul><li>&quot;core/&lt;coreClassName&gt;.php&quot;</li>'
                                                 . '<li>&quot;core/&lt;coreClassName&gt;list.php&quot;</li>'
                                                 . '</ul>erstellt.<br><br>'
                                                 . 'Bitte benutze nur die nur die Zeichen a-z.',
'MF_AMC_HELP_VIEW_CLASS_NAME'                   => 'Basisname der Admin-View-Klassen.<br><br>'
                                                 . 'Es werden folgende Dateien aus Basis dieses Formularfeldes erstellt:<br>'
                                                 . '<ul><li>admin/&lt;adminViewClassName&gt;.php</li>'
                                                 . '<li>admin/&lt;adminViewClassName&gt;_list.php</li>'
                                                 . '<li>admin/&lt;adminViewClassName&gt;_main.php</li>'
                                                 . '<li>out/admin/tpl/&lt;adminViewClassName&gt;.tpl</li>'
                                                 . '<li>out/admin/tpl/&lt;adminViewClassName&gt;_list.tpl</li>'
                                                 . '<li>out/admin/tpl/&lt;adminViewClassName&gt;_main.tpl</li>'
                                                 . '</ul>',
'MF_AMC_HELP_LANGUAGES'                         => 'Definition der Sprachdateien, die erstellt werden sollen.<br>'
                                                 . 'Die Standardsprachen sind im Ident \'MF_AMC_DEFAULT_LANGUAGES\' definiert.<br><br>'
                                                 . '<u>Format:</u><ul><li>&lt;langId&gt; =&gt; &lt;Language-Name&gt;</li></ul>Leerzeichen vor und nach \'=&gt;\' werden automatisch entfernt.<br><br>'
                                                 . '<u>Beispiel:</u><ul><li>de =&gt; Deutsch</li></ul>',
'MF_AMC_HELP_DB_TABLE_NAME'                     => 'Name der Datenbanktabelle, auf die die Core-Klasse zugreift.<br><br><b><u>Hinweis:</u></b> Die DB-Tabelle wird <i>nicht</i> automatisch erstellt.',
);