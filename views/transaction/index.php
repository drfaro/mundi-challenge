<style type="text/css">
.navbar-inverse{
	
}
.site-about{
	padding: 10%;	
}
.site-about .row{
	padding-bottom: 2em
}
.site-about .submit-file{
	margin:0 auto;
}
.site-about .col-left{
	width: 70%;
	float: left;
}
.site-about .col-right{
	width: 30%;
	margin-left: 70%;
	text-align: right;
}
.site-about textarea{
	width: 90%;
	min-height: 300px
}
</style>

<script type="text/javascript">
	$(document).ready(function(){

		$("#form_transaction").submit(function(){

			sendCsv(0);
		  	return false;
		})
		

		$(".submit-file").change(function(){
			url = $(this).val();
			console.log(url)
			$("#form-file").submit();
			//readFile(url);
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
			    //alert( "success" );
			  })
			  .fail(function() {
			    //alert( "error" );
			  })
			  .always(function() {
			    //alert( "complete" );
			  }
			);
			 
			// Perform other work here ...
			 
			// Set another completion function for the request above
			jqxhr.always(function(data_csv) {
				console.log("complete")
				console.log(data_csv)
				console.log(data_csv.length)
				//data_csv = JSON.parse(data_csv)
				console.log(Object.keys(data_csv).length)

				if (Object.keys(data_csv).length > 0 && data_csv != "[]"){
					console.log("entrou")
					//data_csv = JSON.parse(data_csv)
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
			console.log(array_key);
			array_key = array_key.reverse()
			console.log(array_key);
			$.each( array_key, function( key_priority, ordenation ) {
					list_transaction = list_priority[ordenation];
					$.each( list_transaction, function( key_transaction, transaction ) {
						d = new Date();
						id = 'transaction_'+count+"_"+ordenation+'_'+key_transaction+d.getTime();
						html  = '<tr class="warning" id="'+id+'" >'
					  	html +=	'<td>'+id+'</td>'
					  	html +=	'<td>'+transaction[6]+' - '+transaction[1]+'</td>'
					  	html +=	'<td class="process">process</td>'
					  	html += '</tr>';
					  	console.log(html)
					 	$("#table").append(html);
					 	sendTransaction(transaction, id);
					});
				//key_priority = parseInt(key_priority) - 1;
			});
			//prepareSendTransaction(list_priority);
			
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
		    	//console.log(data);
		    	if (data){
		    		success(id);
		    	}
		    },
		  	error:function(err){
		  		error(id)
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



		function readFile(url){
		    $.ajax({
		        type: "POST",
		        url:"?r=transaction/read-file", 
		        data: {file:url},
		        enctype: 'multipart/form-data',
		        success: function(data) {processData(data);}
		     });
		}


	})
</script>

<div class="site-about">
	<div class="row center">
		<form  action="?r=transaction/read-file" method="POST" id="form-file" enctype="multipart/form-data" >
			<input type="file" value="Procurar file" name="file" class=" submit-file" >
		</form>
	</div>
	<form action="#" method="POST" id="form_transaction" enctype="multipart/form-data" >
		<div class="row">
			<div class="col-left">
				<textarea id="csv" name="csv"><?php echo ($file); ?></textarea>
			</div>
			<div class="col-right">
				<p>Adicione as transaçoes no formato CSV.</p>
				<div class="row right">
					<input type="submit" value="Enviar" class="btn" >
				</div>
			</div>
		</div>
		<div class="row">
			<table class="table" id="table">
			  
			</table>
		</div>
	</form>
</div>