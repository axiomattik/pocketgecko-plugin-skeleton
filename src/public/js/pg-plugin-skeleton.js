(function() {

  function _headers() {
    headers = {
      'Content-Type': 'application/json',
    };

    // if user is logged in, send a nonce. otherwise don't.
    if ( document.body.classList.contains('logged-in') ) {
      headers['X-WP-Nonce'] = pgps.api.nonce;
    }

    return headers;
  }

  pgps.api.get = async function(id=undefined) {
    let url = pgps.api.root + 'skeletons';
    if ( id ) url += `/${id}`;
    let response = await fetch(url);
    if ( ! response.ok ) {
      throw new Error(`HTTP Error: ${response.status}`);
    }
    return response.json();
  };


  pgps.api.post = async function(title, value) {
    let response = await fetch(pgps.api.root + 'skeletons', {
      method: 'POST',
      credentials: 'same-origin',
      headers: _headers(),
      body: JSON.stringify({
        title: title,
        value: value,
      })
    });
    if ( ! response.ok ) {
      throw new Error(`HTTP Error: ${response.status}`);
    }
    return response.json();
  };


  pgps.api.delete = async function(id) {
    let url = pgps.api.root + `skeletons/${id}`;
    let response = await fetch(url, {
      method: 'DELETE',
      headers: _headers(),
      credentials: 'same-origin',
    });
    if ( ! response.ok ) {
      throw new Error(`HTTP Error: ${response.status}`);
    }
    return response.json();
  };


  pgps.api.update = async function(id, title, value) {
    let url = pgps.api.root + `skeletons/${id}`;
    let response = await fetch(url, {
      method: 'PATCH',
      headers: _headers(),
      credentials: 'same-origin',
      body: JSON.stringify([{
        'op': 'replace',
        'path': '/post_title',
        'value': title
      }, {
        'op': 'replace',
        'path': '/post_meta/skeleton_meta',
        'value': value,
         
      }])
    });
    if ( ! response.ok ) {
      throw new Error(`HTTP Error: ${response.status}`);
    }
    return response.json();
  };


  pgps.api.sendMessage = async function(message, name, email) {
    let url = pgps.api.root + 'messages';
    let response = await fetch(url, {
      method: 'POST',
      headers: _headers(),
      credentials: 'same-origin',
      body: JSON.stringify({
        message: message,
        name: name,
        email: email,
      }),
    });
  };

})();

(function() {

  window.addEventListener('load', function() {
    document.getElementById('frm-create-skeleton')
    .addEventListener('submit', function(e) {
      e.preventDefault();
      let title = document.getElementById("inp-title").value;
      let value = document.getElementById("inp-value").value;
      pgps.api.post(title, value).then( d => {
        e.target.reset();
        window.location.reload(true);
      });
    });
  });


})();


(function() {

  window.addEventListener('load', function() {
    for (let button of document.querySelectorAll('.delete-skeleton-button')) {

      button.addEventListener('click', function(e) {
        let id = button.id.split('-').pop();
        pgps.api.delete(id).then( d => {
          window.location.reload(true);
        });
      });

    }
  });


})();


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

(function() {

  window.addEventListener('load', function() {
    for (let form of document.querySelectorAll('.update-skeleton-form')) {

      form.addEventListener('submit', function(e) {
        e.preventDefault();
        let id = form.id.split('-').pop();
        let title = document.querySelector("#inp-title-" + id).value;
        let value = document.querySelector("#inp-value-" + id).value;
        pgps.api.update(id, title, value).then( d => {
          alert('success!');
        });
      });

    }
  });


})();
