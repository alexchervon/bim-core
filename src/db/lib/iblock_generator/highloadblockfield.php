<?php



/**
 * Class HighloadblockField
 * класс для генерации кода изменений полей сущностей highload инфоблока
 *
 * @package Bitrix\Adv_Preset\HighloadblockFieldGen
 */
class HighloadblockFieldGen extends CodeGenerator
{


    public function __construct(){
        \CModule::IncludeModule("highloadblock");
    }
    /**
     * метод для генерации кода добавления новых полей сущностей highload инфоблока
     * @param $params array
     * @return mixed
     */
    public function generateAddCode( $params ){
        $this->checkParams( $params );

        $code = '<?php'.PHP_EOL.'/*  Добавляем поля сущностей highload инфоблока */'.PHP_EOL.PHP_EOL;
        $hlblockData = $this->ownerItemDbData['hlblockData'];
        foreach( $this->ownerItemDbData['hlFieldData'] as $hlFieldData  ){

            $code = $code . $this->buildCode('HighloadblockFieldIntegrate', 'Add', array( $hlblockData['NAME'], $hlFieldData ) ) .PHP_EOL.PHP_EOL;
        }


        return $code;

    }
    /**
     * метод для генерации кода обновления полей сущностей highload инфоблока
     * @param $params array
     * @return mixed
     */
    public function generateUpdateCode( $params ){
        $this->checkParams( $params );

        $code = '<?php'.PHP_EOL.'/*  Обновляем поля сущностей highload инфоблока */'.PHP_EOL.PHP_EOL;
        $hlblockData = $this->ownerItemDbData['hlblockData'];
        foreach( $this->ownerItemDbData['hlFieldData'] as $hlFieldData  ){

            $code = $code . $this->buildCode('HighloadblockFieldIntegrate', 'Update', array( $hlblockData['NAME'], $hlFieldData['FIELD_NAME'], $hlFieldData ) ) .PHP_EOL.PHP_EOL;
        }


        return $code;

    }

    /**
     * метод для генерации кода удаления полей сущностей highload инфоблока
     * @param $params array
     * @return mixed
     */
    public function generateDeleteCode( $params ){
        $this->checkParams( $params );

        $code = '<?php'.PHP_EOL.'/*  Удаляем  поля сущностей highload инфоблока   */'.PHP_EOL.PHP_EOL;
        $hlblockData = $this->ownerItemDbData['hlblockData'];
        foreach( $this->ownerItemDbData['hlFieldData'] as $hlFieldData  ){

            $code = $code . $this->buildCode('HighloadblockFieldIntegrate', 'Delete', array( $hlblockData['NAME'], $hlFieldData['FIELD_NAME'] ) ) .PHP_EOL.PHP_EOL;
        }

        return $code;

    }




    /**
     * метод проверки передаваемых параметров
     * @param $params array(
                hlblockId => id highload инфоблока
     *          hlFieldId => id полей
     * )
     * @return mixed
     */
    public function checkParams( $params  ) {

        if ( !isset( $params['hlblockId'] ) || empty( $params['hlblockId'] ) ) {
            throw new \Exception( 'В параметрах не найден hlblockId' );
        }

        if ( !isset( $params['hlFieldId'] ) || empty( $params['hlFieldId'] ) ) {
            throw new \Exception( 'В параметрах не найден hlFieldId' );
        }

        $hlblock = HL\HighloadBlockTable::getById( $params['hlblockId'] )->fetch();
        if ( !$hlblock ) {
            throw new \Exception( 'В системе не найден highload инфоблок с id = ' . $params['hlblockId'] );
        }
        $this->ownerItemDbData['hlblockData'] = $hlblock;
        foreach( $params['hlFieldId'] as $hlFieldId ) {

            $userFieldData = \CUserTypeEntity::GetByID( $hlFieldId  );
            if ( $userFieldData === false || empty( $userFieldData ) ) {
                throw new \Exception( 'Не найдено пользовательское поле с id = ' . $hlFieldId );
            }

            $this->ownerItemDbData['hlFieldData'][] = $userFieldData;
        }





    }



}

?>
