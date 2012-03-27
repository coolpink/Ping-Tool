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
    $.post("/check-domain/", { "domain": domain }, function(response) {
      if (response.msg == "OK")
      {
        var status = (response.status == "OK") ? "ok" : "remove";
        var color = (response.status == "OK") ? "success" : "danger";
        var status = "<span class=\"status btn-"+ color +"\"><span class=\"icon-white icon-"+ status +"\"></span></span>";
        self.parent().children("span.status:first").replaceWith(status);

        self.parent().children(".loader:first").remove();

        $(".check-count").html(response.check_count);
      }

      setInterval(function() {
        pingUrl(domain, self);
      }, 300000); // every 5 minutes
    }, "json");
  }
});