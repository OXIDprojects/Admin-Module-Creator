<?php
/**
 * Automatically generated file.
 */
class %ADMIN_VIEW_CLASS_NAME%_list extends oxAdminList
{
    /**
     * Name of chosen object class (default null).
     * Not the core list class!
     *
     * @var string
     */
    protected $_sListClass = '%CORE_CLASS_NAME%';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    //protected $_sDefSort = "oxgroups.oxtitle";

    /**
     * Executes parent method parent::render() and returns name of template
     * file "usergroup_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        return "%MODULE_NAME%_list.tpl";
    }
}
