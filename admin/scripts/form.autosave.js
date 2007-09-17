/*  Autocompleter.editor, version 1.0: http://icebeat.bitacoras.com
 *  (c) 2005 Daniel Mota aka IceBeat <daniel.mota@gmail.com>
 *
/*--------------------------------------------------------------------------*/
Form.autoSave = Class.create();
Form.autoSave.prototype = {
	/*	
	*	Inicializamos la clase y preparamos parametros
	*/
  initialize: function(element, update, options) {
    this.element = $(element);
    this.update = $(update);
    this.options = $H({
			url: 'form.autosave.php',
			frequency: 30,
			method: 'get',
			msg: {
			  loading:  'Filling form',
			  loaded:   'Form filled',
			  sending:  'Sending form',
			  update:   'Form saved at: '
			}
		}).merge(options);
		this.msg = this.options.msg;
		this.message(this.msg.loading);
    new Ajax.Request(this.options.url, { method: this.options.method, parameters:"autosave=true&autosaveid="+this.element.id, onSuccess: this.autoFill.bind(this) });
  },
  message: function(msg) {
    Element.show(this.update);
    this.update.innerHTML = msg;
  },
  send: function(element,value) {
    this.message(this.msg.sending);
    new Ajax.Request(this.options.url, { method: this.options.method, parameters:"autosaveid="+element.id+'&'+value, onSuccess: this.updateForm.bind(this) });
  },
  updateForm: function(resp) {
    if(resp.responseText) {
      this.message(this.msg.update+resp.responseText);
    }
  },
  autoFill: function(resp) {
    if(resp.responseText) {
      this.message(this.msg.loaded);
      Form.Unserialize(this.element,resp.responseText);
    } else {
      Element.hide(this.update);
    }
    new Form.Observer(this.element,this.options.frequency,this.send.bind(this));
  }
};

Form.Unserialize = function(form,queryComponents) {
  var elements = Form.getElements($(form));
  var queryComponents = queryComponents.toQueryParams();
  for (var i = 0; i < elements.length; i++) {
    var element = elements[i];
    var name = element.name;
    if(queryComponents[name]) {
      var method = element.tagName.toLowerCase();
      var value = decodeURIComponent(queryComponents[name]);
      Form.Element.Unserializers[method](element,value);
    }
  }
};

Form.Element.Unserializers = {
  input: function(element,value) {
    switch (element.type.toLowerCase()) {
      case 'submit':
      case 'hidden':
      case 'password':
      case 'text':
        return Form.Element.Unserializers.textarea(element,value);
      case 'checkbox':
      case 'radio':
        return Form.Element.Unserializers.inputSelector(element,value);
    }
    return false;
  },

  inputSelector: function(element,value) {
    if (element.value == value)
        element.checked = 'checked';
  },

  textarea: function(element,value) {
    element.value = value;
  },

  select: function(element,value) {
    for (var i = 0; i < element.length; i++) {
      var opt = element.options[i];
      if(opt.value == value || opt.text == value) 
        opt.selected = 'selected';
    }
  }
}