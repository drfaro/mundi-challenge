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
            [['idtransaction'], 'required'],
            [['idtransaction', 'priority', 'card_number', 'card_month', 'card_year', 'card_cvv'], 'integer'],
            [['value'], 'number'],
            [['card_brand', 'card_name'], 'string', 'max' => 45],
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
        ];
    }

    /**
     * @inheritdoc
     * @return TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionQuery(get_called_class());
    }


    public static function validateAndparseTransactionToArray($strLine)
    {
        $arrayLine = preg_split("/(;)/", $strLine);

        if (count ($arrayLine) == 8 || count ($arrayLine) == 9)
        {
            /*
            echo $arrayLine[0] . " - ";
            echo $arrayLine[1] . " - ";
            echo $arrayLine[2] . " - ";
            echo $arrayLine[3] . " - ";
            echo $arrayLine[4] . " - ";
            echo $arrayLine[5] . " - ";
            echo $arrayLine[6] . " - ";
            echo $arrayLine[7] . " - ";

            echo "<br />";*/
            return $arrayLine;
        }
        return false;
    }
}
