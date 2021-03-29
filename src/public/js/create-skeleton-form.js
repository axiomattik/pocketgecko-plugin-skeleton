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
