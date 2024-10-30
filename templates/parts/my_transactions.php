<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php global $wpdb; ?>

<!-- Panel -->
<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title"><?php _e( 'My Transactions', 'jprocars' ); ?></span>
	</div>
	<div class="jpro-panel-body">
		<form method="post" class="jpro-form-horizontal" role="form">
		<?php
			$table_name = $wpdb->prefix . 'jp_cars_transactions';
				$query = $wpdb->get_results(
					"
						SELECT id, user_id, user_email, package_id, package_title, gateway, amount, currency, date, status 
						FROM $table_name 
						WHERE user_id = ". get_current_user_id()."
					"
				); 
		?>
			
			<table class="jpro-table">
				<thead>
					<tr class="jpro-primary">
						<th><?php _e( 'Package Title', 'jprocars' ); ?></th>
						<th><?php _e( 'Gateway', 'jprocars' ); ?></th>
						<th><?php _e( 'Amount', 'jprocars' ); ?></th>
						<th><?php _e( 'Date', 'jprocars' ); ?></th>
						<th><?php _e( 'Status', 'jprocars' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if( $query ): foreach( $query as $results ): ?>
					<tr>
						<td><?php echo $results->package_title; ?></td>
						<td><?php echo $results->gateway; ?></td>
						<td><?php echo $results->amount.jp_get_currency_symbol( $results->currency ); ?></td>
						<td><?php echo $results->date; ?></td>
						<td><?php echo $results->status; ?></td>
					</tr>
					<?php endforeach; else: ?>
					<tr>
						<td colspan="5"><?php _e( 'Transactions history empty.', 'jprocars' ); ?></td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
			
		</form>
	</div>
</div><!-- / Panel -->