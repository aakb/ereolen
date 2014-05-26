(function ($) {
  $('.ebog-dlink').live("click", function() {
    $('#ting-download-popup-info').dialog('close');
  });

  var href = '';
  var clicked = null;
  var button = null;
  var popup_buttons = null;
  var translatable = {
    ok_label: Drupal.t('Ok'),
    login_label: Drupal.t('Login'),
    cancel_label: Drupal.t('Cancel'),
    close_label: Drupal.t('Close'),
    download_label: Drupal.t('Proceed to download'),
    bookmark_label: Drupal.t('Remember'),
    prices_label: Drupal.t('Prices')
  };

  // Handle clicked loan link, those matching 'ting/object/%/download' pattern
  $(document).ready(function() {
    $('a[action="download"]').live("click", function(e) {
      e.preventDefault();

      // Get callback link.
      href = $(this).attr('href');

      // Button clicked.
      clicked = $(this);
      clicked.parent().find('.ajax-loader').remove();
      clicked.hide();
      clicked.parent().append('<div class="ajax-loader"></div>');

      process_loan();

      return false;
    });

    // Process loan/download
    var process_loan = function() {
      $.ajax({
        type : 'post',
        url : href + '/popup',
        dataType : 'json',
        success : function(response) {
          // Check if login dialog is open, if it is close it.
          var login_dialog = $('#ting-login-popup');
          if (login_dialog.length) {
            $('#ting-login-popup').dialog('close');
            $('#ting-login-popup').remove();
          }

          // Remove ajax loader
          $('#ting-download-popup').remove();
          clicked.parent().find('.ajax-loader').remove();
          clicked.show();

          if (response.status == false) {
            popup_buttons = {};
            popup_buttons[translatable.close_label] = function() {
              $('#ting-download-popup').dialog('close');
            }

            $('<div id="ting-download-popup" title="' + response.title + '">' + response.content + '</div>').dialog({
              modal : true,
              width: 'auto',
              height: 'auto',
              buttons: popup_buttons
            });

            return;
          }
          else if (response.status === 'login') {
            // Login is required, so display login form.
            popup_buttons = {};

            // Hide login button from the form.
            var content = $(response.content);
            $('#edit-submit', content).remove();

            // Add action to the login dialog button.
            popup_buttons[translatable.login_label] = function() {
              // Add ajax loader to replace buttons.
              button = $('#ting-login-popup').parents('.ui-dialog:first').find('button');
              button.css('visibility', 'hidden');
              button.parent().append('<div class="ajax-loader"></div>');

              // Collect form values.
              var data = $('#elib-popup-login-form').formSerialize();

              // Make login ajax callback.
              $.ajax({
                type : 'POST',
                url : $('#elib-popup-login-form').attr('action'),
                dataType : 'json',
                data: data,
                success : function(response) {
                  // If not logged in handle errors.
                  if (response.status !== 'loggedin') {

                    // Display error message.
                    if ($('#ting-login-popup .messages').length) {
                      $('#ting-login-popup .messages').fadeOut('fast', function () {
                        $(this).remove();
                        $('#elib-popup-login-form #edit-name-wrapper').prepend(response.content);
                      });
                    }
                    else {
                      $('#elib-popup-login-form #edit-name-wrapper').prepend(response.content);
                    }

                    // Enable login buttons and remove ajax loader.
                    button.css('visibility', 'visible');
                    button.parent().find('.ajax-loader').remove();
                    return;
                  }
                  else {
                    // Upload login menu link.
                    var link = $('.menu .login-link');
                    link.removeClass('login-link').addClass('account-link');
                    link = $('a', link);
                    link.val(Drupal.t('My page'));
                    link.attr("href", "/min_side");

                    // Add logout block to the site.
                    var block = $('<div id="block-publizon_user-logout" class="block block-publizon_user"><h2>'+Drupal.t('My profile')+'</h2><div class="content"><p>'+Drupal.t('You are now logged in')+': <a class="biglogoutbutton" href="/logout">'+Drupal.t('Logout')+'</a></p></div></div>');
                    $('#sidebar-first').prepend(block);

                    // Try to process the loan once more.
                    process_loan();
                  }
                }
              });
              return false;
            };

            popup_buttons[translatable.cancel_label] = function() {
              // Close the form an remove it from the dom or close will not work
              // if displayed once more.
              $('#ting-login-popup').dialog('close');
              $('#ting-login-popup').remove();
            };

            var options = {
              modal: true,
              width: 'auto',
              height: 'auto',
              buttons: popup_buttons
            };

            $('<div id="ting-login-popup" title="' + response.title + '">' + content[0].outerHTML + '</div>').dialog(options);

            return;
          }

          popup_buttons = {};
          popup_buttons[translatable.download_label] = function() {
            button = $('#ting-download-popup').parents('.ui-dialog:first').find('button');
            button.css('visibility', 'hidden');
            button.parent().append('<div class="ajax-loader"></div>');
            check_rules();
          };

          popup_buttons[translatable.cancel_label] = function() {
            // Close the form an remove it from the dom or close will not work
            // if displayed once more.
            $('#ting-download-popup').dialog('close');
            $('#ting-download-popup').remove();
          };


          $('<div id="ting-download-popup" title="' + response.title + '">' + response.content + '</div>').dialog({
            modal : true,
            width: 'auto',
            height: 'auto',
            buttons: popup_buttons
          });
        }
      });
    }

    // Check those checkboxes
    var check_rules = function() {
      var boxes = $('.ebog-download-confirm').find('input[type=checkbox]');
      if (boxes.length == boxes.filter(':checked').length) {
        $.ajax({
          type : 'post',
          url : href + '/request',
          dataType : 'json',
          success : function(response) {
            button.css('visibility', 'visible');
            button.parent().find('.ajax-loader').remove();
            $('#ting-download-popup').dialog('close');
            $('#ting-download-popup-info').remove();
            var options = {};
            if (response.status === false) {
              popup_buttons = {};

              var loan_disabled = (response.code === 120 || response.code === 125 || response.code === 126);

              // In case the buy functionality is enabled.
              if (response.buy_enabled === true && loan_disabled === true) {
                popup_buttons[translatable.bookmark_label] = function() {
                  window.location = $('.recall-list-add').attr('href');
                  button = $('#ting-download-popup-info').parents('.ui-dialog:first').find('button');
                  button.css('visibility', 'hidden');
                  button.parent().append('<div class="ajax-loader"></div>');
                }

                popup_buttons[translatable.prices_label] = function() {
                  button = $('#ting-download-popup-info').parents('.ui-dialog:first').find('button');
                  button.css('visibility', 'hidden');
                  button.parent().append('<div class="ajax-loader"></div>');

                  lookup_retailers(function(response) {
                    button.css('visibility', 'visible');
                    button.parent().find('.ajax-loader').remove();
                    $('#ting-download-popup').dialog('close');
                    $('#ting-download-popup-info').remove();

                    show_retailers(response);
                  });
                }
              }

              popup_buttons[translatable.close_label] = function() {
                $('#ting-download-popup-info').dialog('close');
              };

              options = {
                modal: true,
                width: 'auto',
                height: 'auto',
                buttons: popup_buttons
              };
            }
            else {
              options = {
                modal: true,
                width: 'auto',
                height: 'auto'
              };
            }

            $('<div id="ting-download-popup-info" title="' + response.title + '">' + response.content + '</div>').dialog(options);
          }
        });
      }
      else {
        button.css('visibility', 'visible');
        button.parent().find('.ajax-loader').remove();
        $('#ting-download-popup-error').remove();

        popup_buttons = {};
        popup_buttons[translatable.ok_label] = function() {
          $('#ting-download-popup-error').dialog('close');
        };

        $('<div id="ting-download-popup-error" title="' + Drupal.t('Error') + '"><p>' + Drupal.t('Check all checkboxes') + '</p></div>').dialog({
          modal : true,
          width: 'auto',
          height: 'auto',
          buttons: popup_buttons
        });

        return true;
      }

      return false;
    };

    // Retrieve the markup for resellers.
    var lookup_retailers = function(callback) {
      $.ajax({
        type : 'post',
        url : href + '/buy',
        dataType : 'json',
        success: function(response) {
          if ($.isFunction(callback)) {
            callback(response);
          }
        }
      });
    };

    // Populate the popup with reseller and item information.
    var show_retailers = function(data) {
      var buttons = {};
      var ting_object_markup = $('#ting-object #ting-object').clone();

      // List of elements classes to be kept.
      var classes = ['book-title', 'author', 'abstract'];
      var cls = '';
      var resellers_markup = (data.status === true) ? data.markup : '';
      var content = '';

      // Instead of hunge/munge services, response or any requests,
      // take the markup from the actual item page.
      //
      // This is template dependant.
      $(ting_object_markup).find('.meta .inner').children().each(function() {
        cls = $(this).attr('class');

        // -1 means the value is NOT present in the array of values.
        if ($.inArray(cls, classes) === -1) {
          ting_object_markup.find('.' + cls).remove();
        }
      });

      content = $('<div id="ting-object-popup-details" />');
      content.append(ting_object_markup);
      content.find('#ting-object').after(resellers_markup);
      buttons[translatable.close_label] = function() {
        $('#ting-download-popup-resellers').dialog('close');
      };

      $('<div id="ting-download-popup-resellers" title="' + Drupal.t('Prices') + '">' + content.html() + '</div>').dialog({
        modal: true,
        width: 'auto',
        height: 'auto',
        buttons: buttons
      });
    };

    $('.item-reseller .buy button').live('click', function() {
      $('#ting-download-popup-resellers').dialog('close');
    });
  });
})(jQuery);
