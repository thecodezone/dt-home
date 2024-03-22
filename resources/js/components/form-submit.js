/**
 * Attaches an event listener to submit the login form when the 'Enter' key is pressed.
 * The event listener is attached to the 'keypress' event on the login form element.
 *
 * @listens DOMContentLoaded
 * @param {Event} e - The event object.
 * @returns {void}
 */
document.addEventListener('DOMContentLoaded', function () {
  var loginForm = document.querySelector('.container.login form');
  loginForm.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      loginForm.submit();
    }
  });
});
