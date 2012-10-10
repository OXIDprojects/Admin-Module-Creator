<?php
/**
 * Builds a new XML-Document based on an input array.
 *
 * @package  mfAdminModuleCreator
 * @author   Stefan Krenz <stefan.krenz@mayflower.de>
 * @license  BSD
 * @version  0.1
 * @link     http://amc.projects.oxidforge.org/
 */
class mfMenuXmlBuilder
{
    /**
     * The XML-Document for the new modules menu.xml.
     *
     * @var DOMDocument
     */
    private $_oMenuXmlDocument = null;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $oDomDocument = new DOMDocument('1.0', 'ISO-8859-15');
        $this->setXmlDocument($oDomDocument);
    }

    /**
     * Builds the DOMDocument dependent on the menu data.
     *
     * @param array $aMenuData Menu data for the XML.
     *
     * @return void
     */
    public function buildMenuXml(array $aMenuData)
    {
        $oDom = $this->getXmlDocument();
        $oRootElement = $oDom->createElement($aMenuData[0]['name']);
        $oDom->appendChild($oRootElement);
        $oXmlElement = $oRootElement;
        unset($aMenuData[0]);
        foreach ($aMenuData as $oXmlEntry) {
            $oXmlElement = $this->_addElement($oXmlElement, $oXmlEntry);
        }
    }

    /**
     * Creates a new DOMElement and appends it on the specified node.
     *
     * @param DOMNode $oNode     Node, to append the new.
     * @param array   $aNodeData Data for the new node.
     *
     * @return DOMElement|null
     */
    private function _addElement($oNode, $aNodeData)
    {
        if (!isset($aNodeData['name'])) {
            return null;
        }

        if (!isset($aNodeData['value'])) {
            $aNodeData['value'] = null;
        }

        $oXmlDocument = $this->getXmlDocument();
        $oNewElement = $oXmlDocument->createElement($aNodeData['name'], $aNodeData['value']);
        $oNode->appendChild($oNewElement);

        if (isset($aNodeData['attributes'])) {
            foreach ($aNodeData['attributes'] as $sAttrName => $sAttrValue) {
                $oNewElement->setAttribute($sAttrName, $sAttrValue);
            }
        }

        $oChildNode = $oNewElement->childNodes->item(0);
        while ($oChildNode !== null) {
            $oNewElement->removeChild($oChildNode);
            $oChildNode = $oNewElement->childNodes->item(0);
        }

        return $oNewElement;
    }

    /**
     * Get the DOMDocument object, representing current menu.xml.
     *
     * @return DOMDocument
     */
    public function getXmlDocument()
    {
        return $this->_oMenuXmlDocument;
    }

    /**
     * Set the DOMDocument object, representing current menu.xml.
     *
     * @param DOMDocument $oMenuXml The new XML-Document.
     *
     * @return void
     */
    public function setXmlDocument(DOMDocument $oMenuXml)
    {
        $this->_oMenuXmlDocument = $oMenuXml;
    }
}