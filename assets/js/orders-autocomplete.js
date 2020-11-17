var client = algoliasearch(aosOptions.appId, aosOptions.searchApiKey);
var index = client.initIndex(aosOptions.ordersIndexName);
autocomplete('#post-search-input', {hint: false, debug: aosOptions.debug}, [
    {
        source: function (query, callback) {
            index.search({query: query, hitsPerPage: 7}).then(function (answer) {
                callback(answer.hits);
                jQuery(".wc-order-search-admin-error").hide();
            }, function () {
                callback([]);
                jQuery(".wc-order-search-admin-error").hide();
                jQuery(".wp-header-end").after(""
                    + '<div class="wc-order-search-admin-error notice notice-error is-dismissible">'
                    + '<p><b>WooCommerce Orders Search Admin:</b> An error occurred while fetching results from Algolia.</p>'
                    + '<p>If you are offline, this is expected. If you are online, you might want to <a href="options-general.php?page=wc_osa_options">take a look at your configured Algolia credentials</a>.</p>'
                    + '</div>');
            });
        },
        displayKey: 'number',
        templates: {
            suggestion: function (suggestion) {
                return '<a href="post.php?post=' + suggestion.objectID + '&action=edit">'
                    + getStatusLine(suggestion)
                    + getNumberLine(suggestion)
                    + getCustomerLine(suggestion)
                    + getTotalsLine(suggestion)
                    + getMethodsLine(suggestion)
                    + getShippingAndOrBillingHighlightedAttribute(suggestion, 'phone', 'Phone')
                    + getShippingAndOrBillingHighlightedAttribute(suggestion, 'company', 'Company')
                    + getShippingAndOrBillingHighlightedAttribute(suggestion, 'address_1', 'Address 1')
                    + getShippingAndOrBillingHighlightedAttribute(suggestion, 'address_2', 'Address 2')
                    + getShippingAndOrBillingHighlightedAttribute(suggestion, 'city', 'City')
                    + getShippingAndOrBillingHighlightedAttribute(suggestion, 'state', 'State')
                    + getShippingAndOrBillingHighlightedAttribute(suggestion, 'postcode', 'Postcode')
                    + getShippingAndOrBillingHighlightedAttribute(suggestion, 'country', 'Country')
                    + "</a>";

            }
        }
    }
]).on('autocomplete:selected', function (event, suggestion, dataset, context) {
	if (context.selectionMethod === 'click') {
		// If the link is clicked, we let the browser do it's job so that users can open in a new tab if they wish.
		// We also prevent event from bubbling up so the dropdown stays open in case user actually choses to open
		// the order in a new tab.
		event.preventDefault();
		return;
	}
    window.location.href = "post.php?post=" + suggestion.objectID + "&action=edit";
});
jQuery('#post-search-input').select();

function getStatusLine(suggestion) {
    return '<div class="wc-osa__line">'
        + '<span class="wc-osa__status">' + suggestion._highlightResult.status_name.value + '</span>'
        + '</div>';
}

function getNumberLine(suggestion) {
    return '<div class="wc-osa__line">'
        + '<span class="wc-osa__number">#' + suggestion._highlightResult.number.value + '</span> - '
        + '<span class="wc-osa__date">' + suggestion.date_formatted + '</span>'
        + '</div>';
}

function getCustomerLine(suggestion) {
    return getDisplayName(suggestion) + ' (' + getEmail(suggestion) + ')';
}

function getPhoneLine(suggestion) {
    if (typeof suggestion.billing !== 'undefined' && typeof suggestion.billing.phone !== 'undefined' && suggestion._highlightResult.billing.phone.matchLevel !== 'none') {
        return '<div class="wc-osa__line">'
            + '<span class="wc-osa__phone">' + suggestion._highlightResult.billing.phone.value + '</span>'
            + '</div>';
    }

    return '';
}

function getDisplayName(suggestion) {
    if (typeof suggestion.customer !== 'undefined' && suggestion._highlightResult.customer.display_name.matchLevel === 'full') {
        return suggestion._highlightResult.customer.display_name.value;
    }

    if (typeof suggestion.billing !== 'undefined' && suggestion._highlightResult.billing.display_name.matchLevel === 'full') {
        return suggestion._highlightResult.billing.display_name.value;
    }

    if (typeof suggestion.shipping !== 'undefined' && suggestion._highlightResult.shipping.display_name.matchLevel === 'full') {
        return suggestion._highlightResult.shipping.display_name.value;
    }

    if (typeof suggestion.customer !== 'undefined' && suggestion._highlightResult.customer.display_name.matchLevel === 'partial') {
        return suggestion._highlightResult.customer.display_name.value;
    }

    if (typeof suggestion.billing !== 'undefined' && suggestion._highlightResult.billing.display_name.matchLevel === 'partial') {
        return suggestion._highlightResult.billing.display_name.value;
    }

    if (typeof suggestion.shipping !== 'undefined' && suggestion._highlightResult.shipping.display_name.matchLevel === 'partial') {
        return suggestion._highlightResult.shipping.display_name.value;
    }

    if (typeof suggestion.customer !== 'undefined') {
        return suggestion._highlightResult.customer.display_name.value;
    }

    return suggestion._highlightResult.billing.display_name.value;
}

function getEmail(suggestion) {
    if (typeof suggestion.customer !== 'undefined' && suggestion._highlightResult.customer.email.matchLevel !== 'none') {
        return suggestion._highlightResult.customer.email.value;
    }

    if (typeof suggestion.billing !== 'undefined' && suggestion._highlightResult.billing.email.matchLevel !== 'none') {
        return suggestion._highlightResult.billing.email.value;
    }

    if (typeof suggestion.customer !== 'undefined') {
        return suggestion._highlightResult.customer.email.value;
    }

    return suggestion._highlightResult.billing.email.value;
}

function getTotalsLine(suggestion) {
    return '<div class="wc-osa__line">'
        + '<span class="wc-osa__items">' + suggestion.items_count + ' item(s)</span>'
        + '<span class="wc-osa__total">' + suggestion.formatted_order_total + '</span>'
        + '</div>';
}

function getMethodsLine(suggestion) {
    var html = '';
    if (suggestion.shipping_method_title) {
        html += '<span class="wc-osa__shipping-method">' + suggestion.shipping_method_title + '</span>';
    }
    if (suggestion.payment_method_title) {
        html += '<span class="wc-osa__payment-method">' + suggestion.payment_method_title + '</span>';
    }

    if (html.length === 0) {
        return '';
    }

    return '<div class="wc-osa__line">' + html + '</div>';
}

// getMatchingLine will return the highlighted version of an attribute if it matches the query.
// Will return an empty string if the attribute doesn't exist or isn't matching the query.
function getShippingAndOrBillingHighlightedAttribute(suggestion, attributeName, title) {
    var billingValue = getBillingHighlightedAttribute(suggestion, attributeName);
    var shippingValue = getShippingHighlightedAttribute(suggestion, attributeName);

    // If the billing attribute is the same as the shipping value, deduplicate.
    if (billingValue === shippingValue) {
        if (billingValue === '') {
            return '';
        }

        return '<div class="wc-osa__line wc-osa__billing_shipping">'
            + '<span class="wc-osa__' + attributeName + '"><span class="wc-osa__title">' + title + ':</span> ' + billingValue + '</span>'
            + '</div>';
    }


    var html = '';
    if (billingValue !== '') {
        html += '<div class="wc-osa__line wc-osa__billing_shipping">'
            + '<span class="wc-osa__' + attributeName + '"><span class="wc-osa__title">Billing ' + title + ':</span> ' + billingValue + '</spanclass>'
            + '</div>';
    }
    if (shippingValue !== '') {
        html += '<div class="wc-osa__line wc-osa__billing_shipping">'
            + '<span class="wc-osa__' + attributeName + '"><span class="wc-osa__title">Shipping ' + title + ':</span> ' + shippingValue + '</span>'
            + '</div>';
    }

    return html;
}

function getBillingHighlightedAttribute(suggestion, attributeName) {
    return getHighlightedAttribute(suggestion, 'billing.' + attributeName)
}

function getShippingHighlightedAttribute(suggestion, attributeName) {
    return getHighlightedAttribute(suggestion, 'shipping.' + attributeName)
}

function getHighlightedAttribute(suggestion, attributeName) {
    var parts = attributeName.split('.');
    var data = suggestion._highlightResult;

    // Check if highlighted version exists.
    for (var i = 0; i < parts.length; i++) {
        if (!data.hasOwnProperty(parts[i])) {
            return '';
        }
        data = data[parts[i]];
    }

    // Ensure the highlighted version actually is highlighted.
    if (data.matchLevel === 'none') {
        return '';
    }

    return data.value
}
