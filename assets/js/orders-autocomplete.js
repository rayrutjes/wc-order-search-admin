var client = algoliasearch(aosOptions.appId, aosOptions.searchApiKey);
var index = client.initIndex(aosOptions.ordersIndexName);
autocomplete('#post-search-input', {hint: false, openOnFocus: true, debug: aosOptions.debug}, [
  {
    source: autocomplete.sources.hits(index, {hitsPerPage: 7}),
    displayKey: 'number',
    templates: {
      suggestion: function (suggestion) {
        return ""
          + getStatusLine(suggestion)
          + getNumberLine(suggestion)
          + getCustomerLine(suggestion)
          + getTotalsLine(suggestion)
          + getMethodsLine(suggestion);
      }
    }
  }
]).on('autocomplete:selected', function (event, suggestion) {
  window.location.href = "post.php?post=" + suggestion.objectID + "&action=edit";
});
jQuery('#post-search-input').focus();

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
  if (typeof suggestion.customer === 'undefined') {
    return 'Anonymous user';
  }

  return getDisplayName(suggestion) + ' (' + getEmail(suggestion) + ')';
}

function getDisplayName(suggestion) {
  if (typeof suggestion.customer !== 'undefined' && suggestion._highlightResult.customer.display_name.matchLevel !== 'none') {
    return suggestion._highlightResult.customer.display_name.value;
  }

  if (typeof suggestion.billing !== 'undefined' && suggestion._highlightResult.billing.display_name.matchLevel !== 'none') {
    return suggestion._highlightResult.billing.display_name.value;
  }

  if (typeof suggestion.shipping !== 'undefined' && suggestion._highlightResult.shipping.display_name.matchLevel !== 'none') {
    return suggestion._highlightResult.shipping.display_name.value;
  }

  if (typeof suggestion.customer !== 'undefined') {
    return suggestion._highlightResult.customer.display_name.value;
  }

  return 'Anonymous user';
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

  return 'Unknown email';
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
