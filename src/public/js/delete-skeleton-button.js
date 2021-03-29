
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
