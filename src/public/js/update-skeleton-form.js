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
