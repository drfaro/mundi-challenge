<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!--script src="../views/lib/jquery/jquery-3.2.1.min.js"></script-->
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My transactions',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Create', 'url' => ['/transaction']],
            ['label' => 'Liste', 'url' => ['/transaction/list']],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Mundi Challenge <?= date('Y') ?></p>

    </div>
</footer>

<?php $this->endBody() ?>


<script type="text/javascript">
    $(document).ready(function(){

        $("#form_transaction").submit(function(){
            sendCsv(0);
            return false;
        })
        
        $(".submit-file").change(function(){
            url = $(this).val();
            $("#form-file").submit();
        })
            
        var sendCsv = function(pagination){
            count = 0;
            csv = $("#csv").val()
            var jqxhr = $.ajax({
                method:"post", 
                url:"?r=transaction/format-ordernation", 
                data:{ csv: csv , pagination:pagination }
              })
              .done(function() {
              })
              .fail(function() {
              })
              .always(function() {
              }
            );
             
             
            jqxhr.always(function(data_csv) {

                if (Object.keys(data_csv).length > 0 && data_csv != "[]"){
                    showTransaction(data_csv);
                    sendCsv(pagination+10);
                    count ++;
                    return false;
                }
            });
        }

        var showTransaction = function(list_priority){
            array_key = Object.keys(list_priority);
            count_array_key = array_key
            array_key = array_key.reverse()
            $.each( array_key, function( key_priority, ordenation ) {
                    list_transaction = list_priority[ordenation];
                    $.each( list_transaction, function( key_transaction, transaction ) {
                        d = new Date();
                        id = 'transaction_'+count+"_"+ordenation+'_'+key_transaction+d.getTime();
                        html  = '<tr class="warning" id="'+id+'" >'
                        html += '<td>'+id+'</td>'
                        html += '<td>'+transaction[6]+' - '+transaction[1]+'</td>'
                        html += '<td class="process">process</td>'
                        html += '</tr>';
                        $("#table").append(html);
                        sendTransaction(transaction, id);
                    });
            });
            
        }

        var sendTransaction = function(transaction, id) {

            json = {"CreditCardTransactionCollection": [
                        {
                            "AmountInCents": parseInt(transaction[1]),
                            "CreditCard": {
                                "CreditCardBrand": String(transaction[2]).trim(),
                                "CreditCardNumber": String(transaction[3]).trim(),
                                "ExpMonth": String(transaction[4]).trim(),
                                "ExpYear": String(transaction[5]).trim(),
                                "HolderName": String(transaction[6]).trim(),
                                "SecurityCode": String(transaction[7]).trim()
                            },
                            "InstallmentCount": 1
                        }
                    ]
            }
            
            $.ajax({ 
                url: "https://sandbox.mundipaggone.com/Sale", 
                method:"post",
                data:JSON.stringify(json),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'MerchantKey':'c9b8afdc-c21e-4161-8a65-92202123029c'
            },
            success:function( data ) {
                if (data){
                    success(id, transaction);
                    saveLog(transaction, 'success', data);
                }
            },
            error:function(err){
                error(id, transaction)
                saveLog(transaction, 'error', err);
            }
          });

        }

        var success = function(id){
            $('#'+id).removeClass("warning");
            $('#'+id).addClass("success");
            $('#'+id+" .process").html("success");

        }

        var error = function(id){
            $('#'+id).removeClass("warning");
            $('#'+id).addClass("danger");
            $('#'+id+" .process").html("Error");
        }


        function saveLog(transaction, status, data){

            json = {
                "Priority": parseInt(transaction[0]),
                "AmountInCents": parseInt(transaction[1]),
                "CreditCardBrand": String(transaction[2]).trim(),
                "CreditCardNumber": String(transaction[3]).trim(),
                "ExpMonth": String(transaction[4]).trim(),
                "ExpYear": String(transaction[5]).trim(),
                "HolderName": String(transaction[6]).trim(),
                "SecurityCode": String(transaction[7]).trim(),
                "status": status,
                "response": JSON.stringify(data),

            }
            
            $.ajax({ 
                url: "?r=transaction/save-log", 
                method:"POST",
                data:json,
            success:function( data ) {
                
            },
            error:function(err){

            }
          })
        }


    })
</script>
</body>
</html>
<?php $this->endPage() ?>
