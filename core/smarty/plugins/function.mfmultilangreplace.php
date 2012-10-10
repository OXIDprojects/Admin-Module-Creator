<?php
/**
 * Smarty function
 * -------------------------------------------------------------
 * Purpose: Output multilang string
 * add [{ oxmultilang ident="..." }] where you want to display content
 * -------------------------------------------------------------
 *
 * @package  mfAdminModuleCreator
 * @author   Stefan Krenz <stefan.krenz@mayflower.de>
 * @license  BSD
 * @version  0.1
 * @link     http://amc.projects.oxidforge.org/
 */
require_once "function.oxmultilang.php";
/**
 * Loads a langauge string and replaces %s with the specified args.
 * 
 * @param array  $params  Pluginparameters
 * @param object &$smarty Templateengine object.
 * 
 * @return string
 */
function smarty_function_mfmultilangreplace( $params, &$smarty )
{
    $sIdent  = isset( $params['ident'] ) ? $params['ident'] : 'IDENT MISSING';
    $sInputString = smarty_function_oxmultilang(array('ident' => $sIdent), $smarty);
    $sprintfParams = array($sInputString);
    if (empty($params['replace'])) {
        $params['replace'] = array('');
    }

    if (!is_array($params['replace'])) {
        $params['replace'] = array( $params['replace'] );
    }
    foreach ($params['replace'] as $replacement) {
        $sReplacement = smarty_function_oxmultilang(array(
          'ident' => $replacement,
            'noerror' => true,
        ), $smarty);
        if ($sReplacement == $replacement) {
            $sprintfParams[] = $replacement;
        } else {
            $sprintfParams[] = $sReplacement;
        }
    }
    return call_user_func_array('sprintf', $sprintfParams);
}
