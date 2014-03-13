(function () {
  // Helpers
  function $(id) {
    return document.getElementById(id);
  };

  function addClass(element, clss) {
    element.className += element.className === '' ? clss : ' ' + clss;
    return element;
  }

  function removeClass(element, clss) {
    var re = new RegExp('\\s?' + clss);
    element.className = element.className.replace(re,'')
    return element;
  }

  // Initialization : setup all stuffs.
  function init() {
    var content = $('content');
    if(content) {
      new CastAway(content).resize();
      new AutoSaveForm(content.parentNode.parentNode);
    }
  }

  function CastAway(textarea) {
    function handleInput() {
      resize();
      window.scrollBy(0, document.height);
    }

    function resize() {
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    }

    if (!textarea.oninput) {
      addClass(textarea, 'js-cast-away');
      textarea.style.overflowY = 'hidden';
      textarea.oninput = handleInput;
    }

    return { 'textarea': textarea, 'resize' : resize }
  }

  function AutoSaveForm(form) {
    var controls = [];
    var interval = null;
    var AutoSaveForm = null;

    function clear() {
      localStorage.clear();
    }

    function isSaved() {
      var obj = JSON.parse(localStorage.getItem(form.action));
      return obj !== null && Object.keys(obj).length > 0;
    }

    function load() {
      var datas = isSaved() && JSON.parse(localStorage.getItem(form.action));

      for (var key in datas) {
        var el = document.getElementById(key);

        if (el.type && el.type === 'checkbox') {
          el.setAttribute('checked', datas[key]);
        } else {
          el.value = datas[key];
        }
      }
    }

    function save() {
      var inputs = form.querySelectorAll('input, select, textarea');
      var datas = {};

      for (var i = 0, len = inputs.length; i < len; i++) {
        var el = inputs[i];

        if (el.type && el.type === 'checkbox') {
          datas[el.id] = el.checked;
        } else {
          datas[el.id] = el.value;
        }
      }

      localStorage.setItem(form.action, JSON.stringify(datas));
      updateControls();
    }

    function setControls() {
      var submit = form.querySelector('[type=submit]');
      var loadBtn = document.createElement('button');
      loadBtn = addClass(loadBtn, 'button');
      loadBtn.onclick = load;
      loadBtn.textContent = 'Charger sauvegarde';
      loadBtn.type = 'button';
      controls['load'] = loadBtn;

      submit.parentNode.appendChild(loadBtn);

      updateControls();
    }

    function updateControls() {
      if (isSaved()) {
        controls['load'].removeAttribute('disabled');
      } else {
        controls['load'].setAttribute('disabled', 'disabled');
      }
    }

    if (document.querySelectorAll && localStorage) {
      interval = setInterval(save, 15000);
      setControls();
      AutoSaveForm = {'clear': clear, 'isSaved': isSaved,'load': load,'save': save};
      //form.onsubmit = clear;
    }

    return AutoSaveForm;
  }

  window.onload = init;
}());