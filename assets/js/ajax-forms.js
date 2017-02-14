(function($) {
  var $forms = $('.aos-ajax-form');
  $forms.submit(onFormSubmit);

  function onFormSubmit(e) {
    e.preventDefault();

    $form = $(e.currentTarget);
    var queryString = $form.serialize();

    $.ajax({
      type: "POST",
      url: ajaxurl,
      data: queryString,
      dataType: "json",
      success: function(response) {
        if(response.success === false) {
          alert('An error occurred: ' + response.data.message);
        } else {
          alert(response.message);
        }

      },
      error: function() {
        alert('An error occurred. Please try again.');
      }
    });
  }
})(jQuery);

