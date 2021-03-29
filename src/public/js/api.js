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
