<?php
/**
 * Automatically created file.
 */
class %ADMIN_VIEW_CLASS_NAME%_main extends oxAdminDetails
{
    /**
     * Render function
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $sOxId = oxConfig::getParameter( "oxid");
        // check if we right now saved a new entry
        $sSavedID = oxConfig::getParameter( "saved_oxid");
        if ( ($sOxId == "-1" || !isset( $sOxId)) && isset( $sSavedID) ) {
            $sOxId = $sSavedID;
            oxSession::deleteVar( "saved_oxid");
            $this->_aViewData["oxid"] =  $sOxId;
            // for reloading upper frame
            $this->_aViewData["updatelist"] =  "1";
        }

        if ( $sOxId != "-1" && isset( $sOxId)) {
            // load object
            $oDep = oxNew( "%CORE_CLASS_NAME%" );
            $oDep->load($sOxId);
            //$oDep->loadInLang( $this->_iEditLang, $soxId);

            $this->_aViewData["edit"] =  $oDep;
        }
        $this->_aViewData["updatelist"] =  "1";

        return "%MODULE_NAME%_main.tpl";
    }

    /**
     * Saves changed usergroup parameters.
     *
     * @return mixed
     */
    public function save()
    {
    // TODO: put your code here
    }

    /**
     * Saves changed selected group parameters in different language.
     *
     * @return null
     */
    public function saveinlang()
    {
        $this->save();
    }
}
