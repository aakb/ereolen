jQuery(function($){

//  $('#user-login #edit-name').blur(function(){
//    jQuery.getJSON( '/getlibrary/'+$(this).val(),function(data){
//      if(data.elib_library){
//        $('#user-login #edit-library option[value='+data.elib_library+']').attr('selected','selected');
//      }
//    });
//  });
//  var kill = false;
//  function lookupSelector(){
//    if(kill){
//      return false;
//    }
//    setTimeout(function(){
//      if($('.sort-by-selector').length > 0){
//        onChangeRedirect();
//        kill = true;
//      }
//      lookupSelector();
//    },100);
//  }
//  lookupSelector();
//
//  function onChangeRedirect(){
//
//    var url = location.href;
//    var parts = url.split('#');
//    var cleanurl = parts[0];
//    var fragment = parts[1];
//    var parts = url.split('=');
//    var sort = parts[1];
//
//    if(fragment){
//      $('.sort-by-selector').find('option[value='+sort+']').attr('selected','selected');
//    }
//
//    $('.sort-by-selector').change(function(){
//      var value = $(this).find('option:selected').val();
//      location.href = cleanurl+'#sort='+value;
//
//      setTimeout(function(){window.location.reload()},100);
//    });
//
//  }

  $(document).ready(function() {

    $('#ting-object .publisherDescription').condense({
        moreSpeed: 'fast',
        lessSpeed: 'fast',
        moreText: Drupal.t('More'),
        lessText: Drupal.t('Less'),
        ellipsis: '',
        condensedLength: 350
    });


    // Make the help block clickable.
    $('#block-block-12').click(function() {
      document.location.href = "/help";
    });

    $('.display-book').parent().find('.display-book:last').addClass('last');
  })
});
