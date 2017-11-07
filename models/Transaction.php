<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property integer $idtransaction
 * @property string $value
 * @property integer $priority
 * @property string $card_brand
 * @property integer $card_number
 * @property integer $card_month
 * @property integer $card_year
 * @property string $card_name
 * @property integer $card_cvv
 * @property string $status
 * @property string $response
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'number'],
            [['priority', 'card_number', 'card_month', 'card_year', 'card_cvv'], 'integer'],
            [['response'], 'string'],
            [['card_brand', 'card_name'], 'string', 'max' => 45],
            [['status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idtransaction' => 'Idtransaction',
            'value' => 'Value',
            'priority' => 'Priority',
            'card_brand' => 'Card Brand',
            'card_number' => 'Card Number',
            'card_month' => 'Card Month',
            'card_year' => 'Card Year',
            'card_name' => 'Card Name',
            'card_cvv' => 'Card Cvv',
            'status' => 'Status',
            'response' => 'Response',
        ];
    }
    public static function validateAndparseTransactionToArray($str_line)
    {
        $array_line = preg_split("/(;)/", $str_line);

        if (count ($array_line) == 8 || count ($array_line) == 9)
        {
            return $array_line;
        }
        return false;
    }
    
    public function createTable()
    {
        $sql = "CREATE DATABASE IF NOT EXISTS `mundi-challenge`;
                USE `mundi-challenge`;
                 CREATE TABLE `transaction` (
                   `idtransaction` int(11) NOT NULL AUTO_INCREMENT,
                   `value` decimal(20,0) DEFAULT NULL,
                   `priority` int(11) DEFAULT NULL,
                   `card_brand` varchar(45) DEFAULT NULL,
                   `card_number` int(11) DEFAULT NULL,
                   `card_month` int(11) DEFAULT NULL,
                   `card_year` int(11) DEFAULT NULL,
                   `card_name` varchar(45) DEFAULT NULL,
                   `card_cvv` int(11) DEFAULT NULL,
                   `status` varchar(50) DEFAULT NULL,
                   `response` text,
                   PRIMARY KEY (`idtransaction`)
                 ) 
               ";
        Yii::$app->db->createCommand($sql)->execute();
        
    }

}
