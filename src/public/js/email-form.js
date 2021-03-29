
(function() {

  window.addEventListener('load', function() {
    document.querySelector('#pgps-email-form')
    .addEventListener('submit', function(e) {
      e.preventDefault();
      let message = document.querySelector("#pgps-message").value;
      let name = document.querySelector("#pgps-contact-name").value;
      let email = document.querySelector("#pgps-contact-email").value;
      pgps.api.sendMessage(message, name, email).then( d => {
        e.target.reset();
        alert('message sent!');
      });
    });
  });


})();
