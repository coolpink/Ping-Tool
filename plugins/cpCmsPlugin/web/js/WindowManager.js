/**
 * cpMediaBrowserWindowManager manages popup window opening and callbacks.
 * It also managed tinyMCE and should manage any other js based plugin extension.
 *
 * @WARNING : tinymceCallback url is hardcoded !!!!!!
 * @author: Vincent Agnano <vincent.agnano@particul.es>
 */
cpMediaBrowserWindowManager = {

  browser_url:  null,

  init: function(browser_url) {
    if(browser_url == null || browser_url == '')
      throw new Error('cpMediaBrowserWindowManager.init() requires one parameter that is the url for browser popup window');
    cpMediaBrowserWindowManager.browser_url = browser_url;
  },
  
  open: function(params) {
    var width = window.innerWidth ? window.innerWidth*.8 : 500;
    var height = window.innerHeight ? window.innerHeight*.6 : 300;
    params['popup'] = window.open(params['url'], 'cpMediaBrowser', 'width='+width+',height='+height+'addressbar=0,scrollbars=1');
    return new cpMediaBrowserWindowManagerObject(params);
  },
  
  addListener: function(params) {
    var event = params['event'] ? params['event'] : 'onclick';
    params['target'] = document.getElementById(params['target']);
    params['target'][event] = function() {
      var window_manager = cpMediaBrowserWindowManager.open(params);
      window.window_manager = window_manager;
    }
  },
  
  tinymceCallback: function(field_name, url, type, win) {
    if(cpMediaBrowserWindowManager.browser_url == '')
      throw new Error('You must initialise cpMediaBrowserWindowManager.init($browser_url) when calling browser for tinymce');
    var window_manager = cpMediaBrowserWindowManager.open({
      target: win.document.getElementById(field_name),
      url:    cpMediaBrowserWindowManager.browser_url
    });
    win.onunload = function() {
      window_manager.popup.close();
    }
    window_manager.popup.opener = win;
    window_manager.popup.opener.window_manager = window_manager;
  }
  
};

function cpMediaBrowserWindowManagerObject(params) {
  this.target = params['target'];
  this.popup = params['popup'];
}

cpMediaBrowserWindowManagerObject.prototype.callback = function(value) {
  this.getTarget().value = value;
  this.popup.close();
}
cpMediaBrowserWindowManagerObject.prototype.getTarget = function() {
  if(this.target.getAttribute)
    return this.target;
  else
    return this.popup.opener.document.getElementById(this.target);
}

