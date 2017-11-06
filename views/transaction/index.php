<style type="text/css">
.navbar-inverse{
	
}
.site-about{
	padding: 10%;	
}
.site-about .col-left{
	width: 50%;
	float: left;
}
.site-about .col-right{
	width: 50%;
	margin-left: 50%
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
			readFile(url);
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
	        type: "GET",
	        url: url,
	        dataType: "text",
	        success: function(data) {processData(data);}
	     });
	}

	function processData(allText) {
	    var record_num = 5;  // or however many elements there are in each row
	    var allTextLines = allText.split(/\r\n|\n/);
	    var entries = allTextLines[0].split(',');
	    var lines = [];

	    var headings = entries.splice(0,record_num);
	    while (entries.length>0) {
	        var tarr = [];
	        for (var j=0; j<record_num; j++) {
	            tarr.push(headings[j]+":"+entries.shift());
	        }
	        lines.push(tarr);
	    }

	    console.log(lines)
	    $("#csv").val(lines)
	}


	})
</script>

<div class="site-about">
	<form action="#" method="POST" id="form_transaction" >
		<div class="row">
			<div class="col-left">
				<p>Adicione as transaçoes no formato CSV.</p>
			</div>
			<div class="col-right">
				<textarea id="csv" name="csv">3 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ;  </textarea>
			</div>
		</div>
		<div class="row">
			<input type="file" value="Procurar file" class="btn submit-file" >
		</div>
		<div class="row">
			<input type="submit" value="Enviar" class="btn" >
		</div>
		<div class="row">
			<table class="table" id="table">
			  
			</table>
		</div>
	</form>
</div>

<!--
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 39.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 39.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 39.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 39.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 39.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 19.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
1 ; 19.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
3 ; 19.06 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
0 ; 19.01 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 19.02 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
2 ; 39.03 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.04 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; 
4 ; 39.05 ; Visa ; 511111111111 ; 10 ; 22 ; LUKE SKYWALKER ; 123 ; -->