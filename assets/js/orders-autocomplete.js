var searchInput = document.getElementById('post-search-input');
console.log(searchInput);


var client = algoliasearch(aosOptions.appId, aosOptions.searchApiKey);
var index = client.initIndex(aosOptions.ordersIndexName);
autocomplete('#post-search-input', { hint: false, debug:true }, [
  {
    source: autocomplete.sources.hits(index, { hitsPerPage: 5 }),
    displayKey: 'number',
    templates: {
      suggestion: function(suggestion) {
        return getNumberLine(suggestion)
          + getCustomerLine(suggestion)
          + getTotalsLine(suggestion);
      }
    }
  }
]).on('autocomplete:selected', function(event, suggestion, dataset) {
    console.log(suggestion, dataset);
    window.location.href = "post.php?post=" + suggestion.objectID + "&action=edit";
});

function getNumberLine(suggestion) {
  return '<div class="aos-order__line">'
    + '<span class="aos-order__number">#' + suggestion._highlightResult.number.value + '</span> - '
    + '<span class="aos-order__date">' + suggestion.date_formatted + '</span>'
    + '</div>';
}

function getCustomerLine(suggestion) {
  if(typeof suggestion.customer === 'undefined') {
    return 'Anonymous user';
  }

  return suggestion._highlightResult.customer.display_name.value
    + ' (' + suggestion._highlightResult.customer.email.value + ')';
}

function getTotalsLine(suggestion) {
  return '<div class="aos-order__line">'
    + '<span class="aos-order__items">' + suggestion.items_count + ' items</span> - '
    + '<span class="aos-order__total">' + suggestion.formatted_order_total + '</span>'
    + '</div>';
}
