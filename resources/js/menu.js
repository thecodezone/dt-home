import '@spectrum-web-components/menu/sp-menu.js';
import '@spectrum-web-components/menu/sp-menu-group.js';
import '@spectrum-web-components/menu/sp-menu-item.js';
import '@spectrum-web-components/menu/sp-menu-divider.js';
import '@spectrum-web-components/overlay/sp-overlay.js';
import '@spectrum-web-components/icon/sp-icon.js';
import '@spectrum-web-components/icons-ui/icons/sp-icon-triple-gripper.js';

$('#trigger').click(function () {

  var $button = $(this);
  var $overlay = $button.next('sp-overlay');
  var $popover = $overlay.find('sp-popover');

  if ($popover.css('position') === 'relative') {
    $popover.css('position', '');
    $button.css({
      'background-color': '',
      'border-radius': '',
      'padding': '',
      'display': ''
    });
  } else {
    $popover.css('position', 'relative');
    $button.css({
      'background-color': '#007bff',
      'border-radius': '45%',
      'padding': '0px',
      'display': 'flex'
    });
  }
});

$(document).click(function (event) {

  var $overlay = $('sp-overlay');
  var $button = $('#trigger');
  var $popover = $overlay.find('sp-popover');

  if (!$overlay.is(event.target) && !$overlay.has(event.target).length && !$button.is(event.target) && !$button.has(event.target).length && $popover.css('position') === 'relative') {
    $popover.css('position', '');
    $button.css('background-color', '');
  }
});












