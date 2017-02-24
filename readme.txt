=== Algolia WooCommerce Order Search Admin ===
Contributors: rayrutjes, algolia
Tags: search, orders, woocommerce, algolia, admin, autocomplete, orders search, search as you type, instant search, ajax search, ajax
Requires at least: 4.4
Tested up to: 4.7
Stable tag: trunk
License: MIT
License URI: https://opensource.org/licenses/MIT

Search for WooCommerce orders in the admin at the speed of thought with Algolia.

== Description ==

This plugin will power the WooCommerce orders search input with an autocompleted search field providing results as you type in milliseconds regardless of how much orders you have in your database.

When you start having a lot of orders in WooCommerce, searching for a specific order can become very slow and time consuming.

Fun fact is also that the more you have orders, the more you will need to actually be able to search for a specific one.

We have seen users wait for over a minute for WooCommerce to return the search results in the admin.
And even after that long waiting time, given that the default search mechanism uses SQL queries, the relevancy isn't optimal and you might have to adjust your query and wait again.

By installing this plugin, you will be able to index all your orders into Algolia and be able to find orders at the speed of thought, right from your usual orders list in the admin screen of your WordPress website.

You can find orders by typing just a few characters. The search engine match the results by looking into the following fields:

* Order Number (Has been tested with plugins like [WooCommerce Sequential Order Numbers](https://wordpress.org/plugins/woocommerce-sequential-order-numbers/) )
* Customer First Name
* Customer Last Name
* Customer email address
* Product SKU contained in the order
* Status of the order

As you start typing in the search input, you will see instant results popping up inside of a dropdown menu and you will be able to find the relevant order in a few milliseconds.

Also note that by leveraging Algolia as a search engine, in addition to super fast results as you type, you will also benefit from all the other features like typo tolerance that will make sure that if you misspell the customer name, you will still get the relevant orders displayed as part of the results.

= Automatic synchronization =

After you properly provided the plugin with your Algolia credentials, the plugin will take care of making sure the search index stays up to date with your WooCommerce orders.

Every time an order is added, updated, trashed or deleted, it will synchronize with Algolia.

**Note however, that when you first initialize the plugin, you need to index your existing orders.**

= WP-CLI command =

The plugin also offers a [WP-CLI](http://wp-cli.org/) command to allow you to re-index your orders directly from the command line, speeding up the indexing even more and allowing you to use a CRON job.

Here is how to use it:

`wp orders reIndex`

Please note that at no point your are forced to use the command line tool, and that the admin settings screen of the plugin also allows you to re-index all your orders.

This is a nice technical alternative though if you have over 50 thousands of records and you want to speed up a bit the indexing.

Note that there is no limit as how many orders this plugin can handle, and indexing will work with bother the indexing method powered by the UI or from the command line tool.

The only limitation of the admin UI re-indexing is that you have to leave the page open while re-indexing.


= About Algolia =

This plugin relies on the Algolia service which requires you to [create an account](https://www.algolia.com/getstarted/pass?redirect=true).
Algolia offers its Search as a Service provider on a incremental payment program, including a free Hacker Plan which includes 10,000 records & 100,000 operations per month.
Beyond that, plans start at $49/month.

This plugin will create exactly one record per order to index. We index every order that is not flagged as trashed.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Hit the "Setup" button that should now appear at the top of every page of your admin, or directly access the plugin settings page under the `Settings` tab
1. Provide the plugin with your Algolia settings, and [create an Algolia account](https://www.algolia.com/getstarted/pass?redirect=true) if you haven't got one yet
1. Now click on the `re-index orders` button to start indexing your existing orders. WARNING: don't leave the page until the progress reached 100%
1. Once indexing has finished, head to `WooCommerce -> Orders` and enjoy the orders appearing as you type when using the search input



