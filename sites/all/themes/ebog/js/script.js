jQuery(function($){
  $(document).ready(function() {

    // Add truncation function to publisher description on object view.
//    $('#ting-object .publisherDescription').condense({
//        moreSpeed: 'fast',
//        lessSpeed: 'fast',
//        moreText: Drupal.t('More'),
//        lessText: Drupal.t('Less'),
//        ellipsis: '',
//        condensedLength: 350
//    });


    $('#ting-object .publisherDescription').expander({
      slicePoint:       350,
      expandPrefix:     ' ',
      expandText:       Drupal.t('More'),
      userCollapseText: Drupal.t('Less')
    });

    // Make the help block clickable.
    $('#block-block-12').click(function() {
      document.location.href = "/help";
    });

    // Add class "last" to book lists.
    $('.publizon-loans-list li:last-child').addClass('last');
    $('.display-book').parent().find('.display-book:last').addClass('last');
  })
});
