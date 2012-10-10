[{include file="mf_admin_module_creator_head.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]
[{assign_adv var="moduleCoreName" value="array('MF_AMC_MODULE_NAME', 'MF_AMC_CORE_CLASS_NAME')"}]
[{assign_adv var="moduleViewName" value="array('MF_AMC_MODULE_NAME', 'MF_AMC_ADMIN_VIEW_CLASS_NAME')"}]
[{assign_adv var="moduleTableName" value="array('MF_AMC_MODULE_NAME', 'MF_AMC_DB_TABLE_NAME')"}]
[{assign_adv var="coreViewName" value="array('MF_AMC_CORE_CLASS_NAME', 'MF_AMC_ADMIN_VIEW_CLASS_NAME')"}]
[{assign_adv var="coreTableName" value="array('MF_AMC_CORE_CLASS_NAME', 'MF_AMC_DB_TABLE_NAME')"}]
[{assign_adv var="viewTableName" value="array('MF_AMC_ADMIN_VIEW_CLASS_NAME', 'MF_AMC_DB_TABLE_NAME')"}]

<script type="text/javascript">
function checkFormData()
{
    var objModuleName = document.getElementById('moduleName');
    var objCoreClassName = document.getElementById('coreClassName');
    var objAdminViewClassName = document.getElementById('adminViewClassName');
    var objDbTableName = document.getElementById('dbTableName');
    if(objModuleName.value == objCoreClassName.value)
    {
        alert("[{mfmultilangreplace ident="MF_AMC_MESSAGE_DOUBLE_NAMES" replace=$moduleCoreName}]");
        return false;
    }
    if(objModuleName.value == objAdminViewClassName.value)
    {
        alert("[{mfmultilangreplace ident="MF_AMC_MESSAGE_DOUBLE_NAMES" replace=$moduleViewName}]");
        return false;
    }
    if(objModuleName.value == objDbTableName.value)
    {
        alert("[{mfmultilangreplace ident="MF_AMC_MESSAGE_DOUBLE_NAMES" replace=$moduleTableName}]");
        return false;
    }
    if(objCoreClassName.value == objAdminViewClassName.value)
    {
        alert("[{mfmultilangreplace ident="MF_AMC_MESSAGE_DOUBLE_NAMES" replace=$coreViewName}]");
        return false;
    }
    if(objCoreClassName.value == objDbTableName.value)
    {
        alert("[{mfmultilangreplace ident="MF_AMC_MESSAGE_DOUBLE_NAMES" replace=$coreTableName}]");
        return false;
    }
    if(objAdminViewClassName.value == objDbTableName.value)
    {
        alert("[{mfmultilangreplace ident="MF_AMC_MESSAGE_DOUBLE_NAMES" replace=$viewTableName}]");
        return false;
    }
    return true;
}
</script>
<div class="moduleContent">
    <div class="mfHead">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" height="30">
            <tr>
                <td align="left" valign="middle">Admin Module Creator</td>
                <td class="poweredBy">Powered by</td>
                <td align="right" class="logo"><a href="http://www.mayflower.de/" target="_blank"><img src="[{$oViewConf->getImageUrl()}]lg_mayflower.png" alt="Mayflower Logo"></a></td>
            </tr>
        </table>
    </div>

    <div class="mfContent">
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td valign="top" class="spaceRight">
                    <div class="boxTitle">[{oxmultilang ident="MF_AMC_CREATE_MODULE"}]</div>
                    <div class="mfBox">
                        <form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post" enctype="multipart/form-data" onsubmit="return checkFormData();">
                            [{ $shop->hiddensid }]
                            <input type="hidden" name="cl" value="mf_admin_module_creator_main">
                            <input type="hidden" name="fnc" value="createModule">
                            <input type="hidden" name="editval[menuLocation]" id="menuLocation" value="[{ $editval->menuLocation }]">

                            <table cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td class="edittext" valign="top">
                                        <label for="editModuleName">[{ oxmultilang ident="MF_AMC_MODULE_NAME" }]:</label>
                                    </td>
                                    <td class="edittext">
                                        <input type="text" class="editinput" name="editval[moduleName]" id="moduleName" value="[{ $editval->moduleName }]" [{ $readonly }] [{ $disableSharedEdit }]>
                                        [{ oxinputhelp ident="MF_AMC_HELP_MODULE_NAME" }]
                                        [{if $errorMessage->moduleName}]
                                            [{include file="mf_admin_module_creator_error.tpl" message=$errorMessage->moduleName}]
                                        [{/if}]
                                    </td>
                                </tr>
                                <tr>
                                    <td class="edittext" valign="top">
                                        <label for="coreClassName">[{ oxmultilang ident="MF_AMC_CORE_CLASS_NAME" }]:</label>
                                    </td>
                                    <td class="edittext">
                                        <input type="text" class="editinput" name="editval[coreClassName]" id="coreClassName" value="[{ $editval->coreClassName }]" [{ $readonly }] [{ $disableSharedEdit }] [{$createTable}]>
                                        [{ oxinputhelp ident="MF_AMC_HELP_CORE_CLASS_NAME" }]
                                        [{if $errorMessage->coreClassName}]
                                            [{include file="mf_admin_module_creator_error.tpl" message=$errorMessage->coreClassName}]
                                        [{/if}]
                                    </td>
                                </tr>
                                <tr>
                                    <td class="edittext" valign="top">
                                        <label for="adminViewClassName">[{ oxmultilang ident="MF_AMC_ADMIN_VIEW_CLASS_NAME" }]:</label>
                                    </td>
                                    <td class="edittext">
                                        <input type="text" class="editinput" name="editval[adminViewClassName]" id="adminViewClassName" value="[{ $editval->adminViewClassName }]" [{ $readonly }] [{ $disableSharedEdit }] [{$createSQL}]>
                                        [{ oxinputhelp ident="MF_AMC_HELP_VIEW_CLASS_NAME" }]
                                        [{if $errorMessage->adminViewClassName}]
                                            [{include file="mf_admin_module_creator_error.tpl" message=$errorMessage->adminViewClassName}]
                                        [{/if}]
                                    </td>
                                </tr>
                                <tr>
                                    <td class="edittext" valign="top">
                                        <label for="dbTableName">[{ oxmultilang ident="MF_AMC_DB_TABLE_NAME" }]:</label>
                                    </td>
                                    <td class="edittext" valign="top">
                                        <input class="editinput" name="editval[dbTableName]" id="dbTableName" value="[{ $editval->dbTableName }]">
                                        [{ oxinputhelp ident="MF_AMC_HELP_DB_TABLE_NAME" }]
                                        [{if $errorMessage->dbTableName}]
                                            [{include file="mf_admin_module_creator_error.tpl" message=$errorMessage->dbTableName}]
                                        [{/if}]
                                    </td>
                                </tr>
                                <tr>
                                    <td class="edittext" valign="top">
                                        <label for="languages">[{ oxmultilang ident="MF_AMC_LANGUAGES" }]:</label>
                                    </td>
                                    <td class="edittext" valign="top">
                                        <textarea class="editinput" name="editval[languages]" id="languages" rows="3" cols="20">[{strip}]
                                            [{if $editval->languages}]
                                                [{ $editval->languages }]
                                            [{else}]
                                                [{oxmultilang ident="MF_AMC_DEFAULT_LANGUAGES"}]
                                            [{/if}][{/strip}]</textarea>
                                        [{ oxinputhelp ident="MF_AMC_HELP_LANGUAGES" }]
                                        [{if $errorMessage->languages}]
                                            [{include file="mf_admin_module_creator_error.tpl" message=$errorMessage->languages}]
                                        [{/if}]
                                    </td>
                                </tr>
                                <tr>
                                    <td class="edittext"></td>
                                    <td class="edittext"><b>[{oxmultilang ident="MF_AMC_GENERIC_HINT"}]:</b> [{oxmultilang ident="MF_AMC_MESSAGE_USE_UNIQUE_NAMES"}]</td>
                                </tr>
                                <tr>
                                    <td class="edittext"></td>
                                    <td class="edittext">
                                        <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="MF_AMC_CREATE_MODULE" }]" [{ $readonly }] [{ $disableSharedEdit }]>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    [{if $resultLog }]
                        [{include file="mf_admin_module_creator_result_log.tpl" log=$resultLog errorMessage=$errorMessage}]
                    [{/if}]
                    <form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post" enctype="multipart/form-data">
                        [{ $shop->hiddensid }]
                        <input type="hidden" name="cl" value="mf_admin_module_creator_main">
                        <input type="hidden" name="fnc" value="deleteModule">
                        <div class="boxTitle">[{oxmultilang ident="MF_AMC_DELETE_MODULE"}]</div>
                        <div class="mfBox">
                            [{if $createdModules|@count > 0}]
                            <select name="editval[deleteModuleName]">
                                [{html_options options=$createdModules}]
                            </select>
                            <input type="submit" name="delete" value="[{oxmultilang ident="MF_AMC_DELETE_MODULE"}]" onclick="return confirm('[{oxmultilang ident="MF_AMC_CONFIRM_DELETE"}]');">
                            [{else}]
                                [{oxmultilang ident="MF_AMC_MESSAGE_NO_INSTALLED_MODULES"}]
                            [{/if}]
                        </div>
                    </form>
                    <form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post" enctype="multipart/form-data">
                        [{ $shop->hiddensid }]
                        <input type="hidden" name="cl" value="mf_admin_module_creator_main">
                        <input type="hidden" name="fnc" value="moduleInfo">
                        <div class="boxTitle">[{oxmultilang ident="MF_AMC_MODULE_INFO"}]</div>
                        <div class="mfBox">
                        [{if $createdModules|@count > 0}]
                        <select name="editval[infoModuleName]">
                            [{if $moduleInfo|@count > 0}]
                                [{html_options options=$createdModules selected=$moduleInfo.module.moduleName}]
                            [{else}]
                                [{html_options options=$createdModules}]
                            [{/if}]
                        </select>
                        <input type="submit" name="info" value="[{oxmultilang ident="MF_AMC_MODULE_INFO"}]">
                        [{else}]
                            [{oxmultilang ident="MF_AMC_MESSAGE_NO_INSTALLED_MODULES"}]
                        [{/if}]
                            [{if $moduleInfo|@count > 0}]
                                <hr>
                                <table cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td class="edittext" valign="top">[{oxmultilang ident="MF_AMC_MODULE_NAME"}]</td>
                                    <td class="edittext">[{$moduleInfo.module.moduleName}] ([{oxmultilang ident="MF_AMC_MODULE_CREATION_DATE"}] [{$moduleInfo.module.creationDate|date_format:"%d.%m.%Y %H:%M:%S"}])</td>
                                </tr>
                                <tr>
                                    <td class="edittext" valign="top">[{oxmultilang ident="MF_AMC_CORE_CLASS_NAME"}]</td>
                                    <td class="edittext">[{$moduleInfo.module.coreClassName}]</td>
                                </tr>
                                <tr>
                                    <td class="edittext" valign="top">[{oxmultilang ident="MF_AMC_ADMIN_VIEW_CLASS_NAME"}]</td>
                                    <td class="edittext">[{$moduleInfo.module.adminViewClassName}]</td>
                                </tr>
                                <tr>
                                    <td class="edittext" valign="top">[{oxmultilang ident="MF_AMC_DB_TABLE_NAME"}]</td>
                                    <td class="edittext">[{$moduleInfo.module.dbTableName}]</td>
                                </tr>
                                <tr>
                                    <td class="edittext" valign="top">[{oxmultilang ident="MF_AMC_LANGUAGES"}]</td>
                                    <td class="edittext">
                                        [{foreach from=$moduleInfo.languages key="langId" item="langName"}]
                                        [{$langId}] =&gt; [{$langName}]<br>
                                        [{/foreach}]
                                    </td>
                                </tr>
                                    <tr>
                                        <td class="edittext" valign="top">[{oxmultilang ident="MF_AMC_MENU_PATH" noerror=true}]</td>
                                        <td class="edittext">[{$oView->getModuleNavTreePath($moduleInfo.module.moduleName)}]</td>
                                    </tr>
                                <tr>
                                    <td class="edittext" valign="top">[{oxmultilang ident="MF_AMC_CREATED_MODULE_FILES"}]</td>
                                    <td class="edittext">
                                        [{foreach from=$moduleInfo.module.files item="fileName"}]
                                        [{$fileName}]<br>
                                        [{/foreach}]
                                    </td>
                                </tr>
                            </table>
                            [{/if}]
                        </div>
                    </form>
                </td>

                <!-- Anfang rechte Seite -->
                <td valign="top" class="spaceLeft" align="left" width="50%">
                    <div class="boxTitle">[{ oxmultilang ident="MF_AMC_MENU_LOCATION" }]</div>
                    <div class="mfBox">
                        [{if $errorMessage->menuLocation}]
                        [{include file="mf_admin_module_creator_error.tpl" message=$errorMessage->menuLocation}]
                        [{/if}]
                        [{assign var="curLocation" value=$editval->menuLocation}]
                        [{assign var="oFirstMenu" value=$menustructure->item(0)}]
                        [{assign var="mainMenu" value=$oFirstMenu}]
                            <h3>[{oxmultilang ident=$mainMenu->getAttribute('name')|default:$mainMenu->getAttribute('id') noerror=true}]</h3>
                        <ul>
                            [{foreach from=$mainMenu->childNodes item=subMenu}]
                            [{if $curLocation !== null && $subMenu->getAttribute('id') == $curLocation}]
                                [{assign var="class" value='act'}]
                            [{else}]
                                [{assign var="class" value=''}]
                            [{/if}]
                            <li class="menu"><a href="#" class="[{$class}]" id="[{$subMenu->getAttribute('id')}]" onclick="selectMenuLocation(this);">[{oxmultilang ident=$subMenu->getAttribute('name')|default:$subMenu->getAttribute('id') noerror=true}]</a>
                                <ul>
                                    [{foreach from=$subMenu->childNodes item=menuItem}]
                                    [{if $curLocation !== null && $menuItem->getAttribute('id') == $editval->menuLocation}]
                                    [{assign var="class" value='act'}]
                                    [{else}]
                                    [{assign var="class" value=''}]
                                    [{/if}]
                                    <li class="menuItem"><a href="#" class="[{$class}]" id="[{$menuItem->getAttribute('id')}]" onclick="selectMenuLocation(this);">[{oxmultilang ident=$menuItem->getAttribute('name')|default:$menuItem->getAttribute('id') noerror=true}]</a></li>
                                    [{/foreach}]
                                </ul>
                            </li>
                            [{/foreach}]
                        </ul>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<script type="text/javascript">
[{if $editval->menuLocation}]
var oOld = document.getElementById('[{$editval->menuLocation}]');
[{else}]
var oOld = null;
[{/if}]

function selectMenuLocation(obj)
{
    obj.setAttribute('class', 'act');
    var menuLoc = document.getElementById('menuLocation');
    menuLoc.value = obj.id;
    if((oOld == null) || (obj == oOld))
    {
        oOld = obj;
        return;
    }
    oOld.removeAttribute('class');
    oOld = obj;
}
</script>

[{include file="mf_admin_module_creator_bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
