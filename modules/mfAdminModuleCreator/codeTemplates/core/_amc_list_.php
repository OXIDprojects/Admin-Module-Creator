<?php
/**
 * %MODULE_NAME% core class. 
 * 
 * @category OxidAdminModule
 * @package  MfAdminModuleCreator
 * @author   Stefan Krenz <stefan.krenz@mdcc-fun.de>
 * @license  GPLv2 http://www.gnu.org/licenses/gpl-2.0.txt
 * @version  0.1
 * @link     http://www.oxid-esales.com/
 */

/**
 * %MODULE_NAME% core class.
 * 
 * @category OxidAdminModule
 * @package  MfAdminModuleCreator
 * @author   Stefan Krenz <stefan.krenz@mdcc-fun.de>
 * @license  GPLv2 http://www.gnu.org/licenses/gpl-2.0.txt
 * @version  0.1
 * @link     http://www.oxid-esales.com/
 */
class %CORE_CLASS_NAME%List extends oxList
{
    /**
     * Class constructor, sets callback so that Shopowner is able to add any information to the article.
     *
     * @param string $sObjectsInListName Object name (oxShop)
     *
     * @return void
     */
    public function __construct( $sObjectsInListName = '%CORE_CLASS_NAME%')
    {
        return parent::__construct($sObjectsInListName);
    }
    
    // TODO: edit your core classes
}
