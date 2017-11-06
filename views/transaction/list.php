<div class="site-about">

	<table class="table" id="table">
		<?php foreach ($list_transactions as $key => $transaction) {?>
		<tr class="<?php echo (($transaction->status == 'success'?'success':'danger') )?>" title='<?php echo ($transaction->response) ?>' >
				<td> <?php echo($transaction->priority) ?> </td>
				<td> <?php echo($transaction->card_name) ?> </td>
				<td> <?php echo($transaction->value) ?> </td>
				<td> <?php echo($transaction->status) ?> </td>
		</tr>
		<?php } ?>
	</table>
</div>