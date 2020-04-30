# WooCommerce Order Search Admin #
**Contributors:** [rayrutjes](https://profiles.wordpress.org/rayrutjes)  
**Tags:** search, orders, woocommerce, algolia, admin, autocomplete, orders search, search as you type, instant search, ajax search, ajax  
**Requires at least:** 4.6  
**Tested up to:** 5.2  
**Requires PHP:** 5.3  
**Stable tag:** 1.13.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Search for WooCommerce orders in the admin at the speed of thought with Algolia.

## Description ##

This plugin will power the WooCommerce orders search input with an autocompleted search field providing results as you type in milliseconds regardless of how much orders you have in your database.

When you start having lots of orders in WooCommerce, searching for a specific order can become very slow and time-consuming.

Fun fact is also that the more you have orders, the more you will need to search for a specific one.

We have seen users wait for over a minute for WooCommerce to return the search results in the admin.
And even after that long waiting time, given that the default search mechanism uses SQL queries, the relevancy isn't optimal and you often need to adjust your search query and wait again.

By installing this plugin, you will be able to index all your orders into Algolia and be able to find orders at the speed of thought, right from your usual orders list in the admin screen of your WordPress website.

You can find orders by typing just a few characters.
The search engine will search on the following fields:

* Order Number (Has been tested with plugins like [WooCommerce Sequential Order Numbers](https://wordpress.org/plugins/woocommerce-sequential-order-numbers/) )
* Customer First Name
* Customer Last Name
* Customer email address
* Billing First Name
* Billing Last Name
* Billing email address
* Billing Phone number
* Billing Company
* Billing Address 1
* Billing Address 2
* Billing City
* Billing State
* Billing Postcode
* Billing Country
* Shipping First Name
* Shipping Last Name
* Shipping Company
* Shipping Address 1
* Shipping Address 2
* Shipping City
* Shipping State
* Shipping Postcode
* Shipping Country
* Product SKU contained in the order
* Status of the order

As you start typing in the search input, you will see instant results popping up inside of a dropdown menu and you will
be able to find the proper order in milliseconds.

Also note that by leveraging Algolia as a search engine, in addition to super fast results as you type, you will
also benefit from all the other features like typo tolerance that will make sure that if you misspell for example the customer name, you will still get the relevant orders displayed as part of the results.

### Automatic synchronization ###

After you correctly provided the plugin with your Algolia credentials, the plugin will take care of making sure
the search index stays up to date with your WooCommerce orders.

Every time an order is added, updated, trashed or deleted, it will synchronize with Algolia.

**Note, however, that when you first initialize the plugin, you need to index your existing orders.**

### WP-CLI command ###

The plugin also offers a [WP-CLI](http://wp-cli.org/) command to allow you to reindex your orders directly from the
terminal.

Here is how to use it:

`wp orders reindex`

Please note that at no point you are forced to use the command line tool and that the admin settings screen
of the plugin also allows you to reindex all your orders.

The command line approach is an excellent technical alternative though if you have over 50 thousands of records and you want to speed up the indexing.

Note that there is no limit to how many orders this plugin can handle, and indexing will work with both indexing methods;
powered by the UI or by using the WP-CLI command line tool.

The only limitation of the admin UI reindexing is that you have to leave the page open during the reindexing
process.

### Backend Order Search ###

By default, the plugin enhances the default backend search behavior by using Algolia.
This ensures a consistency between results you see in the list and the ones coming from the autocomplete dropdown.
If for whatever reason you want to restore the default backend search behavior, you can use the `wc_osa_enable_backend_search` filter hook.


	function should_enable_backend_search( $value, WP_Query $query ) {
	    return false;
	}
	
	add_filter( 'wc_osa_enable_backend_search', 'should_enable_backend_search', 10, 2 );


### Configuration constants ###

By default, you can configure the plugin on the included options page, but you can also configure the plugin by using one (or more) of the following constants in your `wp-config.php`.
When you use constants, the corresponding option fields will be disabled on the options page.


	define( 'WC_OSA_ALGOLIA_APPLICATION_ID', '<value>' );
	define( 'WC_OSA_ALGOLIA_SEARCH_API_KEY', '<value>' );
	define( 'WC_OSA_ALGOLIA_ADMIN_API_KEY', '<value>' );
	define( 'WC_OSA_ORDERS_INDEX_NAME', 'wc_orders' );
	define( 'WC_OSA_ORDERS_PER_BATCH', 200 );


### About Algolia ###

This plugin relies on the Algolia service which requires you to [create an account](https://www.algolia.com/getstarted/pass?redirect=true).
Algolia offers its Search as a Service provider on an incremental payment program, including a free plan which includes 10,000 records & 100,000 operations per month.
Beyond that, make sure you [check out the pricing](https://www.algolia.com/pricing).

This plugin will create precisely one record per order to index. We index every order that is not flagged as trashed.

Algolia does not support this plugin.

The preferred way of submitting issues or feature requests is through the [GitHub repository](https://github.com/rayrutjes/wc-order-search-admin/issues).

## Installation ##

The plugin works with WooCommerce 2.x & 3.x

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory,
or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Hit the "Setup" button that will  appear at the top of every page of your admin,
or directly access the plugin settings page under the `Settings` tab
1. Provide the plugin with your Algolia settings, and
[create an Algolia account](https://www.algolia.com/getstarted/pass?redirect=true) if you haven't got one yet
1. Now click on the `re-index orders` button to start indexing your existing orders.
WARNING: don't leave the page until the progress reaches 100%
1. Once indexing has finished, head to `WooCommerce -> Orders` and enjoy the orders appearing as you type when using the search input.

## Screenshots ##

### 1. The slick autocomplete search results dropdown. ###
![The slick autocomplete search results dropdown.](https://ps.w.org/wc-order-search-admin/assets/screenshot-1.png)

### 2. Setup instructions steps. ###
![Setup instructions steps.](https://ps.w.org/wc-order-search-admin/assets/screenshot-2.png)

### 3. Algolia account settings. ###
![Algolia account settings.](https://ps.w.org/wc-order-search-admin/assets/screenshot-3.png)

### 4. Indexing settings. ###
![Indexing settings.](https://ps.w.org/wc-order-search-admin/assets/screenshot-4.png)


## Upgrade Notice ##

### 1.12.1 ###
Make all billing and shipping fields searchable. In order to make the billing and shipping addresses searchable you need to reindex your data from `Settings -> WooCommerce Order Search Admin -> Re-index orders`.

## Changelog ##

### 1.12.1 ###
See: [CHANGELOG](https://github.com/rayrutjes/wc-order-search-admin/blob/master/CHANGELOG.md)
