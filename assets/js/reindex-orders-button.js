(function($) {
  var $ordersReindexButtons = $('.aos-reindex-button');
  var currentPage = 1;
  var totalOrdersIndexed = 0;

  $ordersReindexButtons.on('click', handleReindexButtonClick);


  function handleReindexButtonClick() {
    $ordersReindexButtons.attr('disabled', 'disabled');
    updateIndexingPourcentage(0);

    reIndex();
  }

  function updateIndexingPourcentage(amount) {
    $ordersReindexButtons.text('Processing, please be patient ... ' + amount + '%');
  }

  function reIndex() {
    var data = {
      'action': 'aos_reindex',
      'page': currentPage
    };

    $.post(ajaxurl, data, function(response) {

      if(typeof response.success !== 'undefined' && response.success === false) {
        alert('An error occurred: '+ response.data.message);
        resetButtons();
        return;
      }

      if(typeof response.recordsPushedCount === 'undefined') {
        alert('You should first configure your Algolia account settings.');
        resetButtons();
        return;
      }

      totalOrdersIndexed += response.recordsPushedCount;

      progress = Math.round((currentPage / response.totalPagesCount)*100);
      updateIndexingPourcentage(progress);

      if(response.finished !== true) {
        currentPage++;
        reIndex();
      } else {
        handleReIndexFinish();
      }
    }).fail(function(response) {
      alert('An error occurred. Please try again.');
    });
  }

  function handleReIndexFinish() {
    alert('Successfully indexed ' + totalOrdersIndexed + ' orders!');
    resetButtons();
  }

  function resetButtons() {
    totalOrdersIndexed = 0;
    currentPage = 1;
    $ordersReindexButtons.text('Re-index orders');
    $ordersReindexButtons.removeAttr('disabled');
  }

})(jQuery);


