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
class %CORE_CLASS_NAME% extends oxBase
{
    /**
     * Core database table name. $sCoreTbl could be only original data table name and not view name.
     *
     * @var string
     */
    protected $_sCoreTbl = '%DB_TABLE_NAME%';

    /**
     * Name of current class.
     *
     * @var string
     */
    protected $_sClassName = '%CORE_CLASS_NAME%';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( $this->_sCoreTbl );
    }

    /**
     * Get the core table name.
     *
     * @return string Core table name.
     */
    public function getCoreTableName()
    {
      return $this->_sCoreTable;
    }
}
