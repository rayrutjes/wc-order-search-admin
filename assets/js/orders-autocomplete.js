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
          + getCustomerLine(suggestion);
      }
    }
  }
]).on('autocomplete:selected', function(event, suggestion, dataset) {
  console.log(suggestion, dataset);
});

function getNumberLine(suggestion) {
  return '#' + suggestion._highlightResult.number.value + ' - '
    + suggestion.date_formatted;
}

function getCustomerLine(suggestion) {
  return suggestion._highlightResult.customer.display_name.value
    + ' (' + suggestion._highlightResult.customer.email.value + ')';
}
