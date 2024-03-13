document.addEventListener('DOMContentLoaded', function () {
  var loginForm = document.querySelector('.container.login form');
  loginForm.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      loginForm.submit();
    }
  });
});
