  //  djConfig.usePlainJson = true;
  dojo.require('dojo.rpc.JsonService');
  dojo.require('dijit.form.Button');
  dojo.require('dijit.form.TextBox');
  dojo.require('dojo.io.iframe');
  var proxy;
  function setup() {
    var smdURL = '/hgm/server/api.php';
    proxy = new dojo.rpc.JsonService({serviceUrl: smdURL ,
    methods: [
        {
            name: 'add_url',
            parameters: [{name: 'url'}],
            handleAs: 'json'
        },
        {
            name: 'scan_html',
            parameters: [{name: 'html'}],
            handleAs: 'json'
        }
    ],
    timeout: 100000}
    );
  }

  dojo.addOnLoad(setup);

  function scan_url_result(result) {
      imgs = result.images;

      rpc = dojo.byId('pics_counter');
      dojo.empty(rpc);
      rpc.innerHTML = imgs.length;

      rp = dojo.byId('result_pics');
      dojo.empty(rp);
      for (var i = 0; i < imgs.length; ++i)
          {
              var li = dojo.create('li', null, rp, 'last');
              var a = dojo.create('a', {href: imgs[i], innerHTML: new String(imgs[i]), target: '_blank'}, li, 'last');
          }

      links = result.links;
      lc = dojo.byId('links_counter');
      dojo.empty(lc);
      lc.innerHTML = links.length;
      
      rl = dojo.byId('result_links');
      dojo.empty(rl);
      for (i = 0; i < links.length; ++i)
          {
              var li = dojo.create('li', null, rl, 'last');
              var a = dojo.create('a', {href: new String(links[i]), innerHTML: new String(links[i]), target: '_blank'}, li, 'last');
          }
     // console.debug(result);

     cl = dojo.byId('input_checkbox_links');

     dojo.style('links_frame', 'display', cl.checked ? 'block' : 'none');

     cp = dojo.byId('input_checkbox_images');

     dojo.style('images_frame', 'display', cp.checked ? 'block' : 'none');
  }

  function scan_url() {
     if (!(url = dojo.byId('input_url').value))
     {
        return;
     }
     console.debug("scanning " + url);

     proxy.add_url(url).addCallback(scan_url_result);
  }

  function scan_html() {
     if (!(html = dojo.byId('input_html').value))
     {
         return;
     }
     console.debug("scanning " + html);

     proxy.scan_html(html).addCallback(scan_url_result);
  }

var url_value;

  function view_url()
  {
      source = dojo.byId('url_field');
      console.debug('changing source to ' + source.value);

      if (!source.value)
      {
          return;
      }

      dojo.byId('url_frame').src = source.value;
      dojo.style('url_frame', 'display', 'block');
      dojo.byId('url_echo').innerHTML = url_value = source.value;
      source.value = '';
      dojo.style(dijit.byId('scan_button').domNode, 'display', 'block');
  }


  function apply_url(source)
  {
      if (source.id == 'url_field')
          {
              view_url();
          }
      else if (source.id == 'url_frame')
          {
              dojo.byId('url_field').value = source.src;
          }
      else
          {
              console.debug('apply_url(' + source.id + ') passthrough');
          }
  }