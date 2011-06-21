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
    var html = '<ul>';
    $.each(obj, function(a) {
      html += '<li class="palette" style="width: 50px; height: 50px; background: '+obj[a]+';"><span>'+obj[a]+'</span></li>';
    });
    html += '</ul>';
    $('#palette div').append(html);
  }
});