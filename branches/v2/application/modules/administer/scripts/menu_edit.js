var last_menu_selected = false;

function update_menu_form(item)
{
    var f = document.forms['menu_form'];
    var w = dojo.byId('created_by_module_note');
    if (item.created_by_module)
    {
        w.innerHTML = 'This menu item was added by module ' + item.created_by_module;
    }
    else
    {
        w.innerHTML = '';
    }

    f.elements['id'].value = item.id;
    f.elements['label'].value = item.label;
    f.elements['name'].value = item.name;
    f.elements['parent'].value = item.parent;
    f.elements['module'].value = item.module;
    f.elements['controller'].value = item.controller;
    f.elements['action'].value = item.action;
    f.elements['href'].value = item.href;
    f.elements['parameters'].value = item.parameters;
    f.elements['callback_class'].value = item.callback_class;
    dojo.query('#if_module').forEach(
        function(i){
            i.checked = item.if_module[0] ? true : false;
        }
        ); //
    dojo.query('#if_controller').forEach(
        function(i){
            i.checked = item.if_controller[0] ? true : false;
        }
        ); //
    var found = 0;
    var res_select = f.elements['resource'];

    if (item.resource != "")
    {
        found = FM.findOption(res_select, item.resource);
    //   alert(item.resource + " found at " + found);
    }
    res_select.selectedIndex = found;
    found = 0;
    var mod_select = f.elements['module'];
    if (item.module != "")
    {
        found = FM.findOption(mod_select, item.module);
    //  alert("found " + item.module + " at " + found);
    }
    mod_select.selectedIndex = found;
    console.debug('getting res_parent');
    var res_parent = f.elements['parent'];
    if (item.parent != "")
        {
            var parent_key = item.parent + '_' + item.panel;
            console.debug("looking for item parent = " + item.parent);
            found = FM.findOption(res_parent, item.parent);
            for (var i = 0; i < res_parent.options.length; ++i)
                {
                    console.debug('item ' + i + ' = ' + res_parent.options[i].value);
                    if (res_parent.options[i].value == parent_key)
                        {
                            res_parent.selectedIndex = i;
                        }
                }

        }
}