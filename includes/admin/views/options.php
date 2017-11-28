<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<h2><?php esc_html_e( 'Setup instructions', 'wc-order-search-admin' ); ?></h2>
	<p><?php esc_html_e( 'To power your orders search with this plugin you need to:', 'wc-order-search-admin' ); ?></p>
	<ol>
		<li><?php esc_html_e( 'Create an Algolia account', 'wc-order-search-admin' ); ?></li>
		<li><?php esc_html_e( 'Paste the API keys in the Algolia Account settings section of this page', 'wc-order-search-admin' ); ?></li>
		<li>Hit this <button class="aos-reindex-button button button-primary" data-nonce="<?php echo esc_attr( wp_create_nonce( 're_index_nonce' ) ); ?>">Re-index orders</button> button</li>
	</ol>
	<p>Once you are all set, the search input on your <a href="edit.php?post_type=shop_order">orders list page</a> will be powered by the plugin.</p>
	<p><?php esc_html_e( 'Feel free to re-index every time you think something went wrong.', 'wc-order-search-admin' ); ?></p>


	<h2><?php esc_html_e( 'Algolia Account settings', 'wc-order-search-admin' ); ?></h2>
	<p>This plugin indexes your orders in <a href="https://www.algolia.com/" target="_blank">Algolia</a> to get extremely fast an relevant results.</p>
	<p>Algolia is a hosted search service that offers <a href="https://www.algolia.com/pricing" target="_blank">different pricing plans</a> according to your usage.</p>
	<p>In this plugin, every un-trashed order will be stored as one record in Algolia.</p>
	<p>If you <strong>don't have an Algolia account yet</strong>, you can <a href="https://www.algolia.com/users/sign_up" target="_blank">create one in a few minutes</a>.</p>

	<form method="post" class="aos-ajax-form">
		<input type="hidden" name="action" value="wc_osa_save_algolia_settings">
		<?php wp_nonce_field( 'save_algolia_account_settings_nonce' ); ?>
		<table class="form-table">
			<tbody>
				<tr>
					<th>
						<label><?php esc_html_e( 'Algolia Application ID:', 'wc-order-search-admin' ); ?> </label>
					</th>
					<td>
						<input type="text" class="regular-text" name="app_id" value="<?php echo esc_attr( $this->options->get_algolia_app_id() ); ?>">
						<p class="description">You can grab it from your <a href="https://www.algolia.com/api-keys" target="_blank">Algolia admin panel</a>.</p>
					</td>
				</tr>
				<tr>
					<th>
						<label><?php esc_html_e( 'Algolia Search API key:', 'wc-order-search-admin' ); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" name="search_api_key" value="<?php echo esc_attr( $this->options->get_algolia_search_api_key() ); ?>">
						<p class="description">
							You can grab it from your <a href="https://www.algolia.com/api-keys" target="_blank">Algolia admin panel</a>.
							<br>
							For maximum security, this key should only have "search" permission on the "<?php echo esc_attr( $this->options->get_orders_index_name() ); ?>" index.
							<br>
							Read more about permissions in the <a href="https://www.algolia.com/doc/guides/security/api-keys/" target="_blank">Algolia guide about API keys</a>.
						</p>
					</td>
				</tr>
				<tr>
					<th>
						<label><?php esc_html_e( 'Algolia Admin API key:', 'wc-order-search-admin' ); ?></label>
					</th>
					<td>
						<input type="password" class="regular-text" name="admin_api_key" value="<?php echo esc_attr( $this->options->get_algolia_admin_api_key() ); ?>">
						<p class="description">You can grab it from your <a href="https://www.algolia.com/api-keys" target="_blank">Algolia admin panel</a>.</p>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Algolia account settings', 'wc-order-search-admin' ); ?></button>
		</p>
	</form>




	<h2><?php esc_html_e( 'Orders indexing settings', 'wc-order-search-admin' ); ?></h2>

	<form method="post" class="aos-ajax-form">
		<input type="hidden" name="action" value="wc_osa_save_indexing_options">
		<?php wp_nonce_field( 'save_indexing_options_nonce' ); ?>
		<table class="form-table">
			<tbody>
			<tr>
				<th>
					<label><?php esc_html_e( 'Orders index name in Algolia:', 'wc-order-search-admin' ); ?></label>
				</th>
				<td>
					<input type="text" class="regular-text" name="orders_index_name" value="<?php echo esc_attr( $this->options->get_orders_index_name() ); ?>">
				</td>
			</tr>
			<tr>
				<th>
					<label><?php esc_html_e( 'Orders to index per batch:', 'wc-order-search-admin' ); ?></label>
				</th>
				<td>
					<input type="number" name="orders_per_batch"  value="<?php echo esc_attr( $this->options->get_orders_to_index_per_batch_count() ); ?>">
				</td>
			</tr>
			</tbody>
		</table>

		<p class="submit">
			<button type="submit" class="button button-primary"><?php esc_html_e( 'Save orders indexing settings', 'wc-order-search-admin' ); ?></button>
		</p>
	</form>

</div>
