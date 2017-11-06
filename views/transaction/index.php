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