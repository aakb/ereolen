jQuery(function($){
  $(document).ready(function() {

    // Make publisher description folderable.
    $('#ting-object .publisherDescription').expander({
      slicePoint:       350,
      expandPrefix:     ' ',
      expandText:       Drupal.t('More'),
      userCollapseText: Drupal.t('Less')
    });

    // Make publisher description folderable.
    $('.ting-references-object .abstract').expander({
      slicePoint: 350,
      expandPrefix: ' ',
      expandText: Drupal.t('More'),
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
