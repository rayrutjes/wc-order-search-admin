<?php

/*
 * This file is part of WooCommerce Order Search Admin plugin for WordPress.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the GPLv2 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WC_Order_Search_Admin\Admin;

use WC_Order_Search_Admin\AlgoliaSearch\AlgoliaException;
use WC_Order_Search_Admin\AlgoliaSearch\Client;
use WC_Order_Search_Admin\Options;

class Orders_List_Page {

	/**
	 * @var Options
	 */
	private $options;

	/**
	 * @var int
	 */
	private $nb_hits;

	/**
	 * OrdersListPage constructor.
	 *
	 * @param Options $options
	 */
	public function __construct( Options $options ) {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

		$this->options = $options;
	}

	public function is_current_screen() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}
		$screen = get_current_screen();

		return (!is_null($screen) && 'edit-shop_order' === $screen->id);
	}

	public function enqueue_scripts() {
		if ( ! $this->is_current_screen() ) {
			return;
		}

		wp_enqueue_style( 'wc_osa_orders_search', plugin_dir_url( WC_OSA_FILE ) . 'assets/css/styles.css', array(), WC_OSA_VERSION );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'wc_osa_algolia', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch' . $suffix . '.js', array(), false, true );
		wp_enqueue_script( 'wc_osa_autocomplete', 'https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete' . $suffix . '.js', array(), false, true );
		wp_enqueue_script( 'wc_osa_orders_search', plugin_dir_url( WC_OSA_FILE ) . 'assets/js/orders-autocomplete' . $suffix . '.js', array( 'wc_osa_algolia', 'wc_osa_autocomplete', 'jquery' ), WC_OSA_VERSION, true );

		$index_name            = $this->options->get_orders_index_name();
		$search_key            = $this->options->get_algolia_search_api_key();
		$restricted_search_key = Client::generateSecuredApiKey(
			$search_key, array(
				'restrictIndices' => $index_name,
				'validUntil'      => time() + 60 * 24, // A day from now.
			)
		);

		wp_localize_script(
			'wc_osa_orders_search', 'aosOptions', array(
				'appId'           => $this->options->get_algolia_app_id(),
				'searchApiKey'    => $restricted_search_key,
				'ordersIndexName' => $index_name,
				'debug'           => defined( 'WP_DEBUG' ) && WP_DEBUG === true,
			)
		);
	}

	/**
	 * We force the WP_Query to only return records according to Algolia's ranking.
	 *
	 * @param \WP_Query $query
	 */
	public function pre_get_posts( \WP_Query $query ) {
		if ( ! $this->should_filter_query( $query ) ) {
			return;
		}
		$current_page = 1;
		if ( get_query_var( 'paged' ) ) {
			$current_page = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$current_page = get_query_var( 'page' );
		}

		$posts_per_page = (int) get_option( 'posts_per_page' );

		if ( ! $this->options->has_algolia_account_settings() ) {
			return;
		}

		$client = new Client( $this->options->get_algolia_app_id(), $this->options->get_algolia_search_api_key() );
		$index  = $client->initIndex( $this->options->get_orders_index_name() );

		try {
			$results = $index->search(
				$query->query['s'], array(
					'attributesToRetrieve' => 'id',
					'hitsPerPage'          => $posts_per_page,
					'page'                 => $current_page - 1, // Algolia pages are zero indexed.
				)
			);
		} catch ( AlgoliaException $exception ) {
			add_action(
				'admin_notices', function() use ( $exception ) {
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php esc_html_e( 'Unable to fetch results from Algolia. Falling back to native WordPress search.', 'wc-order-search-admin' ); ?></p>
					<p><code><?php echo esc_html( $exception->getMessage() ); ?></code></p>
				</div>
				<?php
				}
			);
			return;
		}

		add_filter( 'found_posts', array( $this, 'found_posts' ), 10, 2 );
		add_filter( 'posts_search', array( $this, 'posts_search' ), 10, 2 );

		// Store the total number of hits, so that we can hook into the `found_posts`.
		// This is useful for pagination.
		$this->nb_hits = $results['nbHits'];
		$post_ids      = array();
		foreach ( $results['hits'] as $result ) {
			$post_ids[] = $result['id'];
		}

		// Make sure there are not results by tricking WordPress in trying to find
		// a non existing post ID.
		// Otherwise, the query returns all the results.
		if ( empty( $post_ids ) ) {
			$post_ids = array( 0 );
		}

		$query->set( 'posts_per_page', $posts_per_page );
		$query->set( 'offset', 0 );
		$query->set( 'post__in', $post_ids );
		$query->set( 'orderby', 'post__in' ); // Make sure we preserve Algolia's ranking.
	}

	/**
	 * Determines if we should filter the query passed as argument.
	 *
	 * @param \WP_Query $query
	 *
	 * @return bool
	 */
	private function should_filter_query( \WP_Query $query ) {
		return $this->is_current_screen()
			&& $query->is_admin
			&& $query->is_search()
			&& $query->is_main_query();
	}

	/**
	 * This hook returns the actual real number of results available in Algolia.
	 *
	 * @param int      $found_posts
	 * @param \WP_Query $query
	 *
	 * @return int
	 */
	public function found_posts( $found_posts, \WP_Query $query ) {
		return $this->should_filter_query( $query ) ? $this->nb_hits : $found_posts;
	}

	/**
	 * Filter the search SQL that is used in the WHERE clause of WP_Query.
	 * Removes the where Like part of the queries as we consider Algolia as being the source of truth.
	 * We don't want to filter by anything but the actual list of post_ids resulting
	 * from the Algolia search.
	 *
	 * @param string   $search
	 * @param \WP_Query $query
	 *
	 * @return string
	 */
	public function posts_search( $search, \WP_Query $query ) {
		return $this->should_filter_query( $query ) ? '' : $search;
	}
}
