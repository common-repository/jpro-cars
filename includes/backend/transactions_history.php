<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php global $wpdb; ?>

<!-- Wrap -->
<div class="wrap">
	<h3><?php _e( 'Transactions History', 'jprocars' ); ?></h3>
	
	<!-- Panel -->
	<div class="jpro-panel">
		<div class="jpro-panel-heading">
			<span class="jpro-panel-title"><?php _e( 'All Transactions', 'jprocars' ); ?></span>
		</div>
		<div class="jpro-panel-body">
			<form method="post" class="jpro-form-horizontal" role="form">
			<?php 
				
				$table_name = $wpdb->prefix . 'jp_cars_transactions';
				$query = $wpdb->get_results(
					"
						SELECT id, user_id, username, user_email, package_id, package_title, gateway, amount, currency, date, status 
						FROM $table_name
					"
				); ?>
				
				
				<table class="jpro-table">
					<thead>
						<tr class="jpro-primary">
							<th><?php _e( '#ID', 'jprocars' ); ?></th>
							<th><?php _e( 'User ID', 'jprocars' ); ?></th>
							<th><?php _e( 'Username', 'jprocars' ); // @since 0.2 ?></th>
							<th><?php _e( 'PayPal Email', 'jprocars' ); ?></th>
							<th><?php _e( 'Package ID', 'jprocars' ); ?></th>
							<th><?php _e( 'Package Title', 'jprocars' ); ?></th>
							<th><?php _e( 'Gateway', 'jprocars' ); ?></th>
							<th><?php _e( 'Amount', 'jprocars' ); ?></th>
							<th><?php _e( 'Currency', 'jprocars' ); ?></th>
							<th><?php _e( 'Purchase Date', 'jprocars' ); ?></th>
							<th><?php _e( 'Payment Status', 'jprocars' ); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php if( $query ): foreach( $query as $results ): ?>
					
						<tr>
							<td><?php echo $results->id; ?></td>
							<td><?php echo $results->user_id; ?></td>
							<td><?php echo $results->username; // @since 0.2 ?></td>
							<td><?php echo $results->user_email; ?></td>
							<td><?php echo $results->package_id; ?></td>
							<td><?php echo $results->package_title; ?></td>
							<td><?php echo $results->gateway; ?></td>
							<td><?php echo $results->amount; ?></td>
							<td><?php echo $results->currency; ?></td>
							<td><?php echo $results->date; ?></td>
							<td><?php echo $results->status; ?></td>
						</tr>
					
					<?php endforeach; else: ?>
						<tr>
							<td colspan="10"><?php _e( 'No transactions available yet.', 'jprocars' ); ?></td>
						</tr>
					<?php endif; ?>
					</tbody>
				</table>
			
			</form>
		</div>
	</div><!-- / Panel -->
	
</div><!-- / Wrap -->