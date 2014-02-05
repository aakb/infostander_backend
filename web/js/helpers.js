var helpers = {
  redirect: function(path) {
    document.location.href = path;
  },
  confirmRedirect: function(path, message) {
    if (confirm(message)) {
      helpers.redirect(path);
    }
  }
};