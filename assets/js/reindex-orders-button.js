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
      totalOrdersIndexed += response.recordsPushedCount;

      progress = Math.round((currentPage / response.totalPagesCount)*100);
      updateIndexingPourcentage(progress);

      if(response.finished !== true) {

        currentPage++;
        reIndex();
      } else {
        handleReIndexFinish();
      }
    });
  }

  function handleReIndexFinish() {
    alert('Successfully indexed ' + totalOrdersIndexed + ' orders!');
    totalOrdersIndexed = 0;
    currentPage = 1;
    $ordersReindexButtons.text('Re-index orders');
    $ordersReindexButtons.removeAttr('disabled');
  }

})(jQuery);


