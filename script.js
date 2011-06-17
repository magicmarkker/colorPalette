$(document).ready(function() {
  $('#w').click(function() {
    $('#paste').hide();
    $('#web_address').show();
  });
  $('#p').click(function() {
    $('#web_address').hide();
    $('#paste').show();
  });

  $('#paster').submit(function() {
    $.post('process.php', {
      action: 'pasted',
      styles: $('#pasted').val()
    }, function(json) {
      addPalette(json);
    });
    return false;
  });
  $('#web').submit(function() {
    $.post('process.php', {
      action: 'url',
      url: $('#weblink').val()
    }, function(json) {
      addPalette(json);
    });
    return false;
  });
  function addPalette(json) {
    var obj = $.parseJSON(json);
    $.each(obj, function(a) {
      $('#palette div').append('<ul><li class="palette" style="width: 50px; height: 50px; background:'+obj[a]+';"><span>'+obj[a]+'</span></li></ul>');
    });
  }
});