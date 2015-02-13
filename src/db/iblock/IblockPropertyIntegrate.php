<?php
namespace Bim\Db\Iblock;

/**
 * Class IblockPropertyIntegrate
 * @package Bim\Db\Iblock
 */
class IblockPropertyIntegrate {
    /**
     * Add
     * @param $arFields
     * @return bool
     * @throws \Exception
     */
	public function Add($arFields)
    {
        if (isset($arFields['SORT'])) {
            if (!is_int($arFields['SORT'])) {
                if (intval($arFields['SORT'])) {
                    $arFields['SORT'] = intval($arFields['SORT']);
                } else {
                    $arFields['SORT'] = 500;
                }
            }
        } else {
            $arFields['SORT'] = 500;
        }
        # default
        $arDefaultValues = array(
            'MULTIPLE' => false,
            'IS_REQUIRED' => false,
            'ACTIVE' => true,
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => false,
            'FILE_TYPE' => '',
            'LIST_TYPE' => 'L',
            'ROW_COUNT' => 1,
            'COL_COUNT' => 30,
            'LINK_IBLOCK_ID' => null,
            'DEFAULT_VALUE' => null,
            'WITH_DESCRIPTION' => 'N',
            'SEARCHABLE' => 'N',
            'FILTRABLE' => 'N',
            'MULTIPLE_CNT' => 5,
            'HINT' => '',
            'SECTION_PROPERTY' => 'Y',
            'SMART_FILTER' => 'N',
            'USER_TYPE_SETTINGS' => array(),
            'VALUES' => array()
        );
        if ($arFields['IBLOCK_CODE']) {
            unset($arFields['IBLOCK_ID']);
            $rsIBlock = \CIBlock::GetList(array(), array('CODE' => $arFields['IBLOCK_CODE']));
            if ($arIBlock = $rsIBlock->Fetch()) {
                $arFields['IBLOCK_ID'] = $arIBlock['ID'];
            } else {
                throw new \Exception(__METHOD__ . ' Not found iblock with code ' . $arFields['IBLOCK_CODE']);
            }
        }
        if (!strlen($arFields['CODE'])) {
            throw new \Exception(__METHOD__ . ' Not found property code');
        }
        $iblockPropDbRes = \CIBlockProperty::GetList(array(), array('IBLOCK_ID' => $arFields['IBLOCK_ID'], 'CODE' => $arFields['CODE']));
        if ($iblockPropDbRes !== false && $iblockPropDbRes->SelectedRowsCount()) {
            throw new \Exception(__METHOD__ . 'Property with code = "' . $arFields['CODE'] . '" ');
        }
        if ($arFields['LINK_IBLOCK_CODE']) {
            unset($arFields['LINK_IBLOCK_ID']);
            $rsIBlock = \CIBlock::GetList(array(), array('CODE' => $arFields['LINK_IBLOCK_CODE']));
            if ($arIBlock = $rsIBlock->Fetch()) {
                $arFields['LINK_IBLOCK_ID'] = $arIBlock['ID'];
            }
        }
        foreach ($arDefaultValues as $DefaultName => $DefaultValue) {
            if (!isset($arFields[$DefaultName]) || empty($arFields[$DefaultName]))
                $arFields[$DefaultName] = $DefaultValue;
        }
        $objCIBlockProperty = new \CIBlockProperty();
        unset($arFields['ID']);
        $iId = $objCIBlockProperty->Add($arFields);
        if ($iId) {
            return $iId;
        } else {
            throw new \Exception(__CLASS__ . '::' . __METHOD__ . ' ' . $objCIBlockProperty->LAST_ERROR);
        }
        return false;
    }

    /**
     * Delete
     * @param $sIBlockCode
     * @param $sPropertyCode
     * @return array
     */
    public function Delete($sIBlockCode, $sPropertyCode)
    {
        global $RESPONSE;
        $rsProperty = CIBlockProperty::GetList(array(), array('IBLOCK_CODE' => $sIBlockCode, 'CODE' => $sPropertyCode));
        if ($arProperty = $rsProperty->Fetch()) {
            if (CIBlockProperty::Delete($arProperty['ID'])) {
                return $RESPONSE[] = array('type' => 'success');
            } else {
                return $RESPONSE[] = array('type' => 'error', 'error_text' => 'Iblock property delete error!');
            }
        } else {
            return $RESPONSE[] = array('type' => 'error', 'error_text' => 'Not find property with code ' . $sPropertyCode);
        }
    }

}