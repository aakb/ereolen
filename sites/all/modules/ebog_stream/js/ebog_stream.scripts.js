(function ($) {
  var popupSelector = '#ebog-stream-popup';
  var popupOpen = false;
  var clicked = null;
  var streamUrl = null;
  var itemId = null;
  var ok_button = Drupal.t('Ok');
  var login_button = Drupal.t('Login');
  var cancel_button = Drupal.t('Cancel');

  var showThrobber = function(ele, dialog) {
    if (dialog) {
      ele.css('visibility', 'hidden').after('<div class="ajax-loader" />');
    }
    else {
      ele.hide().after('<div class="ajax-loader" />');
    }
  }

  var hideThrobber = function(ele, dialog) {
    if (dialog) {
      ele.css('visibility', 'visible').next('.ajax-loader').remove();
    }
    else {
      ele.show().next('.ajax-loader').remove();
    }
  }

  var showPopup = function(title, content, buttons) {
    popupOpen = true;

    var options = {
      height: 'auto',
      width: 'auto',
      modal: true,
      buttons: buttons,
      close: function(event, ui) {
        $(this).remove();
        popupOpen = false;
      }
    };

    $('<div id="ebog-stream-popup" title="' + title + '">' + content + '</div>').dialog(options);
  }

  var closePopup = function() {
    $(popupSelector).dialog('close').remove();
  }

  var showLoanConfirm = function() {
    var buttons = {};
    buttons[ok_button] = function() {
      var button = $('#ebog-stream-popup').parents('.ui-dialog:first').find('button');
      showThrobber(button, true);

      tryLoan(itemId, function(response) {
        hideThrobber(button, true);
        closePopup();

        if (response.status === 'loaned') {
          displayReader(response);
        }
        else if (response.status === 'loan_exceeded') {
          showPopup(response.title, response.message, {'Ok': function() {$(popupSelector).dialog('close').remove();}});
        }
      });
    }
    buttons[cancel_button] = function() {
      $(popupSelector).dialog('close').remove();
      popupOpen = false;
    }

    showPopup(
      Drupal.t('Confirm loan'),
      Drupal.t('Continue with loaning this item?'),
      buttons
    );
  }

  var doLogin = function(action, data, callback) {
    $.ajax({
      url: action,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {
        if (callback && typeof(callback) === 'function') {
          callback(response);
        }
      }
    });
  }

  var displayLoginError = function(response) {
    if ($('#ebog-stream-popup .messages').length) {
      $('#ebog-stream-popup .messages').fadeOut('fast', function () {
        $(this).remove();
        $('#elib-popup-login-form #edit-name-wrapper').prepend(response.content);
      });
    }
    else {
      $('#elib-popup-login-form #edit-name-wrapper').prepend(response.content);
    }
  }

  var checkLoan = function(itemId, callback) {
    $.ajax({
      url: '/publizon/' + itemId + '/checkloan',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (callback && typeof(callback) === 'function') {
          callback(response);
        }
      }
    });
  }

  var tryLoan = function(itemId, callback) {
    $.ajax({
      url: '/publizon/' + itemId + '/tryloan',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (callback && typeof(callback) === 'function') {
          callback(response);
        }
      }
    });
  }

  var tryStream = function() {
    $.ajax({
      url: streamUrl,
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (response.status === 'login') {
          var buttons = {};
          buttons[login_button] = function() {
            var form = $('#elib-popup-login-form');
            var form_values = form.formSerialize();
            var button = $('#ebog-stream-popup').parents('.ui-dialog:first').find('button');

            showThrobber(button, true);

            doLogin(form.attr('action'), form_values, function(response) {
              hideThrobber(button, true);

              if (response.status === 'loggedin') {
                closePopup();
                checkLoan(itemId, function(response) {
                  if (response.status === 'loaned') {
                    displayReader(response);
                  }
                  else if (response.status === 'confirm_loan') {
                    showLoanConfirm();
                  }
                });
              }
              else {
                displayLoginError(response);
              }
            });
          }
          buttons[cancel_button] = function() {
            $(popupSelector).dialog('close').remove();
            popupOpen = false;
          }

          var content = $(response.content);
          $('#edit-submit', content).remove();

          showPopup(response.title, content[0].outerHTML, buttons);
        }
        else if (response.status === 'confirm_loan') {
          showLoanConfirm();
        }
        else if (response.status === 'loaned') {
          displayReader(response);
        }

        hideThrobber(clicked, false);
      }
    });
  }

  var displayReader = function(response) {
    showPopup(Drupal.t('Stream'), '<a href="/publizon/stream/'+response.book_id+'" target="_blank">' + Drupal.t('Start reading') + '</a>', []);
  }

  $(document).ready(function() {
    $('a.ebook-stream').click(function(e) {
      e.preventDefault();

      if (popupOpen) {
        return false;
      }

      clicked = $(this);
      streamUrl = $(this).attr('href');
      itemId = streamUrl.split('/');
      itemId = itemId[2];

      showThrobber(clicked, false);
      tryStream();

      return false;
    });
  });

})(jQuery);

