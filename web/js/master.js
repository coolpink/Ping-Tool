$(function() {
  $("ul.clients li.client strong").each(function(i, elem) {
    var domain = $(elem).html();
    var self = $(this);
    
    pingUrl(domain, self);
  });

  function pingUrl(domain, self)
  {
    self.parent().children(".loader:first").remove();
    self.parent().append("<div class=\"loader\">Loading</div>");
    $.get("http://127.0.0.1:1337", { "domain": domain }, function(response) {
      var status = (response.status == "OK") ? "ok" : "remove";
      var color = (response.status == "OK") ? "success" : "danger";
      var status = "<span class=\"status btn-"+ color +"\"><span class=\"icon-white icon-"+ status +"\"></span></span>";
      self.parent().children("span.status:first").replaceWith(status);
      self.parent().children(".loader:first").remove();

      setTimeout(function() {
        pingUrl(domain, self);
      }, 30000); // every 30 seconds

      $.get("/check-count/", {}, function(response) {
        $(".check-count").html(response.checkCount);
      }, "json");
    }, "json");
  }
});