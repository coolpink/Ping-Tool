$(function() {


  $("ul.clients li.client strong").each(function(i, elem) {
    var domain = $(elem).html();
    var self = $(this);

    var offset = 5000 * i;
    setTimeout(function() {
      pingUrl(domain, self);
    }, offset);
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



      $(".check-count").html(response.check_count);

      self.parent().children(".loader:first").remove();

      setInterval(function() {
        pingUrl(domain, self);
      }, 300000); // every 5 minutes
    }, "json");
  }
});