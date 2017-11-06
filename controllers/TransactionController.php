<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class TransactionController extends Controller
{

    

    /**
     * Displays about page.
     *
     * @return Response|string
     */
    public function actionIndex()
    {

        return $this->render('index');

    }

    /**
     * Displays about page.
     *
     * @return Response|string
     */
    public function actionFormatOrdernation()
    {
        header('Content-Type: application/json');
        $array_transactions= [];
        $array_paginaton= [];
        $max = 10;

        if (Yii::$app->request->post() && Yii::$app->request->post('csv')) {
            $csv = Yii::$app->request->post('csv');
            $pagination = Yii::$app->request->post('pagination');
            
            $array_lines = preg_split("/(\n)/", $csv);
            foreach ($array_lines as $key => $line) {
                $array_current = \app\models\Transaction::validateAndparseTransactionToArray($line);
                
                $key = intval(trim($array_current[0]));
                if ($array_current != false)
                {
                    if ($array_transactions[$key] ==  null)
                    {
                        $array_transactions[$key] = [];
                    }
                    array_push($array_transactions[$key], $array_current);
                }

            }
            //ordena chave do array do maior para o menor
            krsort($array_transactions,true);
            
            $count = 0;
            //$pagination = $max + $pagination;
            $array_paginaton = [];
            foreach ($array_transactions as $key_transactions => $array_transaction) {
                
                foreach ($array_transaction as $key_transaction => $transaction) {
                   //10 > 0 && 20 < 0
                  if ($pagination <= $count && ($pagination+$max) >= $count)
                  {
                      
                    if (!$array_paginaton[$key_transactions])
                    {
                        $array_paginaton[$key_transactions] = [];
                    }
                    
                    array_push($array_paginaton[$key_transactions],$transaction);
                    //array_push($array_trnsactions[$key], $array_current);
                  }elseif(($pagination+$max) == $count){
                      echo json_encode($array_paginaton);
                      exit();
                  }
                
                 $count ++;  
                }

            }
            
            
            
        }


        echo json_encode($array_paginaton);

    }

}
