/**
* Fullajax = AJAX & AHAH library
* http://www.fullajax.ru
* SiRusAjaX - SRAX v1.0.4 build 8 (dax)
* Copyright(c) 2007-2010, Ruslan Sinitskiy.
* http://fullajax.ru/#:license
**/

if (!window.SRAX || window.SRAX.TYPE != 'full'){

/**
* Функции логирования
* @param {Any} any значение
**/
function log(){
    SRAX.debug('log', arguments);
}

function info(){
    SRAX.debug('info', arguments);
}

function error(){
    SRAX.debug('error', arguments);
}

function warn(){
    SRAX.debug('warn', arguments);
}

/**
* Функция поиска элемента по его id
* @param {String} idElem id элемента
* @return {Element} найденный элемент
**/
function id(idElem){
    return SRAX.get(idElem);
}

/**
* Функция проверки заканчивается ли строка на указанное значение
* @param {String} value указанное значение
* @param {Boolean} caseSensitive если == true не чуствительна к регистру
* @return {Boolean} результат проверки
**/
String.prototype.endWith=function(value, caseSensitive){
    var s = caseSensitive ? this.toLowerCase() : this, v = caseSensitive ? value.toLowerCase() : value;
    return s.substring(s.length-v.length, s.length) == v;
}
      
/**
* Функция проверки начинается ли строка с указанного значения
* @param {String} value указанное значение
* @param {Boolean} caseSensitive если == true не чуствительна к регистру
* @return {Boolean} результат проверки
**/
String.prototype.startWith=function(value, caseSensitive){
    var s = caseSensitive ? this.toLowerCase() : this, v = caseSensitive ? value.toLowerCase() : value;
    return s.substring(0, v.length) == v;
}

/**
* Функция для запроса данных
* @param {String} url URL адрес запроса
* @param {Object} options объект конфигурации <br> пример: {callback:myfunction, id:'myid', method:'post', params:'name1=value1&name2=value2'} <br><br>
* 
* Возможные параметры options: <br>
* url/src - URL запроса <br>
* id - id потока <br>
* method - метода запроса данных post или get (по умолчанию) <br>
* form - id формы, сама форма, id элемента или сам элемент, с которого необходимо собрать параметры <br>
* params - строка параметров, которые необходимо включить в запрос (name=val1&name=val2) <br>
* callback/cb - функция обратного вызова <br>
* callbackOps/cbo - опции, которые передаются в функцию обратного вызова <br>
* destroy - флаг авто удаления процесса из памяти после окончания запроса true или false (по умолчанию) <br>
* anticache/nocache - флаг антикеширования true или false (по умолчанию) <br>
* async - флаг выполнения асинхронного запроса true (по умолчанию) или false <br>
* xml - XML, эмуляция ответа, при наличии данного параметра запрос данных c сервера не осуществляется <br>
* text - текст, эмуляция ответа, при наличии данного параметра запрос данных c сервера не осуществляется <br>
* user - username, для подключения требующего имя юзера
* pswd - password, для подключения требующего пароль
* storage - флаг использования локального хранилища true (по умолчанию) или false - действует только при подключении SRAX.Storage
* etag - флаг использования Etag для идентификации новизны данных в локальном хранилище true (по умолчанию) или false - действует только при подключении SRAX.Storage
* headers - массив header-ов из обьектов {ключ : значение}, которые необходимо передать на сервер. пример -> headers:[{Etag: '123'}, {'Accept-Encoding': 'gzip,deflate'}]
* loader - лоадер-индикатор, если не определен - используется лоадер по умолчанию 
* 
* @return {Object} DATAThread объект процесса запроса данных
**/
function dax(url, options){
    if (!options) options = {};
    if (typeof url == 'string') options.url = url; else options = url;
    if (!options.id) options.id = 'undefined';
    var thread = SRAX.Data.thread[options.id] ? SRAX.Data.thread[options.id] : SRAX.DATAThread(options.id);
    thread.setOptions(options, 1).request();
    return thread;
}

/**
* Функция для прерывания запроса данных
* @param {String} id id запроса
**/
function abortData(id){
    if (SRAX.Data.thread[id]) SRAX.Data.thread[id].abort();
}

/**
* Функция запроса данных методом GET
* @param {String} url URL адрес запроса
* @param {Function} cb callback функция обратного вызова
* @param {String} idThread id запроса
* @param {Object} cbo объект опции передаються в callback функцию
* @param {Boolean} destroy флаг авто удаления процесса после окончания запроса 
* @return {Object} DATAThread объект процесса запроса данных
**/
function getData(url, cb, idThread, cbo, anticache, destroy){
    return dax(url, {
        cb: cb,
        id: idThread,
        cbo: cbo,
        anticache: anticache,
        destroy: destroy
    });
}

/**
* Функция запроса данных методом POST
* @param {String} url URL адрес запроса
* @param {String} body параметры запроса (пример: 'name1=value1&name2=value2') 
* @param {Function} cb callback функция обратного вызова
* @param {String} idThread id запроса
* @param {Object} cbo объект опции передаються в callback фун??цию
* @param {Boolean} destroy флаг авто удаления процесса после окончания запроса 
* @return {Object} DATAThread объект процесса запроса данных
**/
function postData(url, params, cb, idThread, cbo, anticache, destroy){
    return dax(url, {
        method: 'post',
        params: params,
        cb: cb,
        id: idThread,
        cbo: cbo,
        anticache: anticache,
        destroy: destroy
    });
}

/**
* Главный объект-библиотека 
**/
if (!window.SRAX) FLAX = SRAX = {};

/**
* Функция для реализации наследования
**/
SRAX.extend = function(dest, src, skipexist){
    var overwrite = !skipexist; 
    for (var i in src)
        if (overwrite || !dest.hasOwnProperty(i)) dest[i] = src[i];
    return dest;
};

(function($){

$.extend($, {
    
    
    /**
    * Идентификатор версии библиотеки
    **/
    version : 'SRAX v1.0.4 build 8',       
    
    /**
    * Идентификатор данной библиотеки, для решения проблем совместного использования разных частей SRAX библиотеки
    **/
    TYPE : 'dax',       

    /**
    * Параметры по умолчанию 
    **/
    Default : {        
        /**
        * Префикс по умолчанию
        **/
        prefix: 'ax',

        /**
        * Разделитель префикса по умолчанию 
        **/
        sprt: ':',
        /**
        * Признак уровня ссылки
        **/
        lvl: '_lvl',

        /**
        * id элемента-лоадера по умолчанию - сигнализатора загрузки HTML 
        **/
        loader : 'loading',

        /**
        * id элемента-лоадер?? по умолчанию - сигнализатора загрузки данных 
        **/
        loader2 : 'loading2',

        /**
        * суфикс элемента-лоадера для каждого потока 
        **/
        loaderSufix : '_loading',

        /**
        * Флаг дебагинга AJAX запросов
        * @type Boolean 
        **/
        DEBUG_AJAX : 0,

        /**
        * Флаг авто удаления DATAThread процесса после окончания запроса
        * @type Boolean 
        **/
        DAX_AUTO_DESTROY : 0,

        /**
        * Флаг антикеш для DATAThread 
        * @type Boolean 
        **/
        DAX_ANTICACHE : 0,

        /**
        * Кодировка запросов (по умолчанию = 'UTF-8')
        * @type String 
        **/
        CHARSET : 'UTF-8'  

    },

    /**
    * Функция логирования
    * @param {String} type тип (log, warn, info, error)
    * @param {Array} аргументы
    **/
    debug : function (type, args){
        var c = window.console;
        if (c && c[type]) {
          try{
            c[type].apply(c, args); 
          } catch (ex){
            c[type](args.length == 1 ? args[0] : args);
          }
          //if (SRAX.browser.mozilla) c[type].apply(c, args); else c[type](args.length == 1 ? args[0] : args);
        } else if (window.runtime){
            var arr = [type + ': ' + args[0]];
            for (var i = 1, len = args.length; i < len; i++) arr.push(args[i]);
            runtime.trace(arr);
        } 
    },
    
    /**
    * Метод для получения текущего времени в миллисекундах
    **/
    getTime : function(){
      return new Date().getTime();
    },    

    /**
    * Метод инициализации основных контейнеров и прочего
    **/
    init : function(){
        var agent = navigator.userAgent.toLowerCase();
        $.browser = {
            //version: (agent.match( /.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/ ) || [])[1],
            webkit: /webkit/.test(agent),
            safari: /safari/.test(agent),
            opera: /opera/.test(agent),
            msie: /msie/.test(agent) && !/opera/.test(agent),
            mozilla: /mozilla/.test(agent) && !/(compatible|webkit)/.test(agent),
            air: /adobeair/.test(agent)
        }
        if ($.browser.msie)
          for (var i = 0, arr = [6, 7, 8], n = arr.length; i < n; i++ )
            if (new RegExp('msie ' + arr[i]).test(agent))
              $.browser.msieV = arr[i];

        $.addContainerListener($.Data);

    },

    /**
    * Инициализация события-триггера готовоности документа
    **/
    initOnReady : function(){
        if ($.isReadyInited) return;
        $.isReadyInited = 1;
        //событие запускается после полного построения DOM, но раньше чем событие window.onload 
	      if ($.browser.mozilla || $.browser.opera) {
            $.addEvent(document, 'DOMContentLoaded', $.ready);
        } else 
        if ($.browser.msie) {
            (function () {
                try {
                    document.documentElement.doScroll('left');
                } catch (e) {
                    setTimeout(arguments.callee, 50);
                    return;
                }
                $.ready();
            })();            
            /*            
            document.write('<s'+'cript id="ie-srax-loader" defer="defer" src="/'+'/:"></s'+'cript>');
            var defer = document.getElementById("ie-srax-loader");
            defer.onreadystatechange = function(){
                if(this.readyState == "complete") {
                    this.parentNode.removeChild(this);
                    $.ready();
                }
            };
            defer = null;
            **/
	} else 
        if ($.browser.safari){
		$.safariTimer = setInterval(function(){
			if (document.readyState == "loaded" || 
				document.readyState == "complete") {
				clearInterval($.safariTimer);
				$.safariTimer = null;
				$.ready();
			}
		}, 10); 
         }
         $.addEvent(window, 'load', $.ready);
    },
    /**
    * Регистрация Функций на событии onReady 
    * @param {Function} handler функция, которая должна выполниться
    **/
    onReady : function(handler){
        if ($.isReady) {
            handler();
        } else {
            $.readyHndlr.push(handler);        
            $.initOnReady();
        }
    },

    /**
    * Метод для выполнения зарегистрированных функций на событии onReady 
    **/
    ready : function(){
        if ($.isReady) return;
        $.isReady = 1;
        for (var i = 0, len = $.readyHndlr.length; i < len; i++){
            try{
                $.readyHndlr[i]();
            } catch(ex){
                error(ex);
            }
        }
        $.readyHndlr = null;
    },

    /**
    * Функция получения объекта
    * @param {String/Object} obj id объекта или сам объект
    * @return {Object} объект
    **/
    get : function(obj){
        return typeof obj == 'string' ? document.getElementById(obj) : obj;
    },

    /**
    * Список XMLHTTP ActiveXObject движков
    **/
    IE_XHR_ENGINE : ['Msxml2.XMLHTTP', 'Microsoft.XMLHTTP'],

    /**
    * Функция инициализации XMLHttpRequest объекта  
    * @return {Object} XMLHttpRequest объект
    **/
    getXHR : function() {
        if (window.XMLHttpRequest && !(window.ActiveXObject && location.protocol == 'file:')) {
            return new XMLHttpRequest();
        } else 
        if (window.ActiveXObject){
          for (var i = 0; i < $.IE_XHR_ENGINE.length; i++){
            try {
                return new ActiveXObject($.IE_XHR_ENGINE[i]);
            } catch (e){}
          }
        }
    },
    
    /**
    * Функция очистки URL от имени хотса  
    * @param {String} url URL
    * @return {String} результр??ющий URL
    **/
    delHost : function(url){
        if (url && url.startWith($.host)) url = url.replace($.host, '');  
        return url;
    },
    
    /**
    * Протокол + хост  
    **/
    host : location.protocol + '//' + location.host,

    /**
    * Функция препроцессорной обработки данных, 
    * если переопределена вызывается перед вызовом callback функции,
    * одна для всех запросов данных
    * @param {Object} ops входящие параметры (ops.xhr - объект XmlHttpRequest, thread - процесс владелец)
    * @return {Boolean} результат обработки
    **/
    DaxPreprocessor : function(ops){
    },

    /**
    * Объект процесса XHR запроса 
    * @param {String} id запроса
    **/
    XHRThread : function(id) {
        var _this = {
            options : {},
            inprocess : 0,
            id : id,
            setOptions : function(options, overwrite){
                if (!options.url && options.src) options.url = options.src;    
                if (!options.cb && options.callback) options.cb = options.callback;    
                if (options.cbo == null && options.callbackOps != null) options.cbo = options.callbackOps;    
                if (options.anticache == null && options.nocache != null) options.anticache = options.nocache;            
                if (overwrite) ops = {};
                $.extend(ops, options);
                if (ops.async == null) ops.async = true;
                ops.url = $.delHost(ops.url);
                this.options = ops;
                return _this;
            },
            getOptions : function(){
                return ops;
            },
            isProcess : function (){
                return _this.inprocess;
            },
            getXHR : function(){
                if (!xhr) xhr = $.getXHR();
                return xhr; 
            },
            onProgressXHR : function(){
                var xhr = _this.getXHR();
                try{    
                  xhr.onprogress = function(e){
                      _this.fireEvent('progress', {
                          id : id,
                          thread : _this,                                  
                          event : e,
                          position : e.position,
                          total : e.totalSize,
                          percent : Math.round(100 * e.position / e.totalSize) 
                      })                            
                  }
                } catch (ex){}
                return _this;
            },
            openXHR : function(){
                var method = _this.getMethod(),
                    xhr = _this.getXHR(),
                    url = ($.browser.msie && location.protocol == 'file:' && ops.url.startWith('/') ?  'file://' : '') + ops.url;                        
                if (ops.user) xhr.open(method.toUpperCase(), url, ops.async, ops.user, ops.pswd);
                else xhr.open(method.toUpperCase(), url, ops.async);        
                return _this;
            },
            sendXHR : function(useAnticache, processRequest, params){
                var method = _this.getMethod(),
                    xhr = _this.getXHR();
                xhr.onreadystatechange = ops.async ? processRequest : function(){};
                var rh = 'setRequestHeader';
                if (ops.cut) xhr[rh]('AJAX_CUT_BLOCK', ops.cut);
                if (useAnticache) xhr[rh]('If-Modified-Since', 'Sat, 1 Jan 2000 00:00:00 GMT');
                xhr[rh]('AJAX_ENGINE', 'Fullajax');
                xhr[rh]('X-Requested-With', 'XMLHttpRequest');
                if (ops.headers){
                    for (var i in ops.headers){
                        xhr[rh](i, ops.headers[i]);
                    }
                }
                if (method == 'post') xhr[rh]('Content-Type', 'application/x-www-form-urlencoded; Charset=' + D.CHARSET);                                        
                $.showLoading(_this.inprocess, _this.getLoader());
                xhr.send((method == 'post') ? params : null);
                if (!ops.async) processRequest();
            },
            init : function(){
                if (_this.inprocess) _this.abort();
                _this.inprocess = 1;
                return _this;   
            },
            getParams : function(){
                var params = $.createQuery(ops.form),
                    method = _this.getMethod();
                if (ops.params) {
                    if (params != '' && !ops.params.startWith('&')) params += '&';
                    params += ops.params; 
                }
                if (method != 'post' && params != '') {
                    if (ops.url.indexOf('?') == -1){
                        ops.url += '?' + params;
                    } else {
                        ops.url += ((ops.url.endWith('?') || ops.url.endWith('&')) ? '' : '&') + params;
                    }
                }
                return params;
            },
            abort : function(){
                _this.inprocess = 0;
                if (!xhr) return;
                try{
                    xhr.isAbort = 1;
                    xhr.abort();
                } catch (ex){}
                xhr = null;
                $.showLoading(0, _this.getLoader());
            },
            _getLoader : function(isdax){               
                if (!_this.loader) _this.loader = ops.loader == null ? $.getLoader(id, isdax) : $.get(ops.loader);   
                return _this.loader;
            },    
            getMethod : function(){
                var m = ops.method ? ops.method : (ops.form ? ops.form.method : 'get');
                return m && m.toLowerCase() == 'post' ? 'post':'get';
            }            
        }
        var xhr, ops = _this.options;
        $.addEventsListener(_this);
        return _this;
    },

    /**
    * Объект процесса запроса данных
    * @param {String} id запроса
    **/
    DATAThread : function(id) {
        var thread = $.XHRThread(id),        
            ops, 
            startTime;

        $.Data.thread[id] = thread;
        $.Data.register(thread);

        thread.getLoader = function(){
            return thread._getLoader(1);
        }

        thread.repeat = function(params){
            ops.params = params;
            thread.request();
        }

        thread.request = function(){
            ops = thread.getOptions();
            var method = thread.getMethod();
            try{
                var options = {
                    url:ops.url,
                    id:id,
                    options:ops,
                    thread:thread
                }

                if (thread.fireEvent('beforerequest', options) !== false){
                    startTime = $.getTime();
                    var params = thread.init().getParams(), 
                        useAnticache = ops.anticache != null ? ops.anticache : D.DAX_ANTICACHE;
                    
                    if (ops.text || ops.xml){
                        processRequest({readyState:4,status:ops.status == null ? 200:ops.status, responseText:ops.text, responseXML:ops.xml})
                        ops.text = ops.xml = null;
                    } else {
                        thread.onProgressXHR().openXHR().sendXHR(useAnticache, processRequest, params);
                    }
                    if (D.DEBUG_AJAX) log(method + ' ' + ops.url + ' params:' + params + ' id:' + id);
                    thread.fireEvent('afterrequest', options);                    
                }
            } catch (ex){
                thread.abort();
                error(ex);
                throw ex;
            }
        }


        function processRequest(xhr) {
          if (!xhr || !xhr.readyState) xhr = thread.getXHR();
          try{
            if (xhr.readyState == 4) {
              thread.inprocess = 0;
              $.showLoading(thread.inprocess, thread.getLoader());
              var status = xhr.isAbort ? -1 : xhr.status, 
                  success = (status >= 200 && status < 300) || status == 304 || (status == 0 && location.protocol == 'file:'),
                  text = xhr.responseText,
                  xml = xhr.responseXML,
                  o = {
                   xhr:xhr,
                   url:ops.url,
                   id:id,
                   status:status,
                   success:success, 
                   cbo:ops.cbo, callbackOps:ops.cbo,
                   options:ops,
                   text:text,
                   xml:xml,
                   thread:thread,
                   /**
                   * responseText и responseXML - deprecated, оставлены для совместимости с предыдущими версиями - вместо нижеследующих полей лучше использовать text и xml соответсвенно
                   **/
                   responseText:text,
                   responseXML:xml,
                   time: $.getTime() - startTime                   
               }
              thread.fireEvent('response', o);
              if (status > -1 && $.DaxPreprocessor(o) !== false && ops.cb) {
                   ops.cb(o, id, success, ops.cbo);
                   if (D.DEBUG_AJAX) log('callback id:' + id);                   
              }

              if ((ops.destroy != null) ? ops.destroy : D.DAX_AUTO_DESTROY){
                   thread.destroy();
              }
            }
          } catch (ex){
              error(ex);
              thread.fireEvent('exception',
                   {xhr:xhr,
                   url:ops.url,
                   id:id,
                   exception:ex,
                   options:ops}
              )
              thread.inprocess = 0;
              $.showLoading(thread.inprocess, thread.getLoader());
              if ((ops.destroy != null) ? ops.destroy : D.DAX_AUTO_DESTROY){
                   thread.destroy();
              }
          }
        }        

        thread.destroy = function(){
            $.Data.thread[id] = null;
            delete $.Data.thread[id];
        }
        
        return thread;
    },

    /**
    * Функция отображения/скрытия объекта лоадер-сигнализатор запроса HTML (картника с играющей загрузкой)
    * @param {Boolean} show показать/скрыть
    * @param {String} obj елемент loader 
    **/
    showLoading : function(show, obj){
        var s = obj ? obj.style : 0;
        if (s){
          if (show) {
              if (s.visibility) s.visibility = 'visible'; else s.display = 'block';
          } else {
            function getHide(th, isdax){
                for (var i in th) {
                    if (th[i].getLoader() != obj) continue;
                    if (th[i] && th[i].isProcess()) return 1;
                }
            }
            if (!getHide($.Data.thread, 1) && !getHide($.Html.thread)) {
                if (s.visibility) s.visibility = 'hidden'; else s.display = 'none';
            }
          }
        } 
    },

    /**
    * Функция доступа к объекту лоадер-сигнализатор запроса (картника с играющей загрузкой)
    * @param {String} obj id родительского элемента
    * @param {Boolean} isdax если = false или null запрос HTML, если = true - запрос данных    
    * @return {Object} объект лоадер-сигнализатор запроса HTML 
    **/
    getLoader : function(obj, isdax){        
        var g = $.get;
        if (obj) obj = g((typeof obj == 'string' ? obj : obj.id) + D.loaderSufix);
        return obj || g(isdax ? D.loader2 : D.loader) || g(isdax ? D.loader : D.loader2);
    },

    /**
    * Функция для кодирования симовлов
    * @param {String} text текст
    * @return {String} закодированный текст
    **/
    encode : encodeURIComponent,

    /**
    * Функция для декодирования симовлов
    * @param {String} text закодированный текст
    * @return {String} декодированный текст
    **/
    decode : decodeURIComponent,

    /**
    * Функция авто-сборки параметров
    * @param {String/Element} obj id формы или сама форма 
    * @return {String} строка завернутых параметров (пример: 'name1=value1&name2=value2')
    **/
    createQuery : function(obj, ops) {
        obj = $.get(obj);
        if (!obj) return '';
        if (!ops) ops = {};
        var names = [], 
            vals = [], 
            e = $.encode, 
            inputs = obj.getElementsByTagName("input");       
        for(var i = 0; i < inputs.length; i++ ) {
          var inp = inputs[i],
              type = inp.type.toLowerCase(),
              name = inp.name ? inp.name : inp.id,
              value = e(inp.value);
          if (!name) continue;
          name = e(name);
          switch(type){
              case "text":
              case "password":
              case "hidden":
              case "button":
                names.push(name);
                vals.push(value);
                break;
              case "checkbox":
              case "radio":
                if (inp.checked) {
                  names.push(name);
                  vals.push((value == null || value == '') ? inp.checked : value);
                }
                break;                
          }
        }

        var selects = obj.getElementsByTagName("select");       
        for(var i = 0; i < selects.length; i++ ) {
            var sel = selects[i],
                type = sel.type.toLowerCase(), 
                name = sel.name ? sel.name : sel.id;
            if (!name || sel.selectedIndex == -1) continue;
            if (type == 'select-multiple'){
                for (var j = 0, len = sel.options.length; j < len; j++){
                    if (sel.options[j].selected) {
                        names.push(name);
                        vals.push(e(sel.options[j].value));
                    }
                }
            } else {            
              names.push(e(name));
              vals.push(e(sel.options[sel.selectedIndex].value));
            }
        }   

        var textareas = obj.getElementsByTagName("textarea");       
        for(var i = 0; i < textareas.length; i++) {
            var ta = textareas[i],
                name = ta.name ? ta.name : ta.id;
            if (!name) continue;
            names.push(e(name));
            vals.push(e(ta.value));
        }
        var query = [];
        for (var i = 0, len = names.length; i < len; i++){ 
            if (ops.skipEmpty && vals[i] == '') continue;
            query.push(names[i] + '=' + vals[i]);
        }
        var params = query.join('&') + (obj.submitValue || '');
        obj.submitValue = null;
        return params;
    },

    /**
    * Метод для добавления объекту интерфейса модели событий
    *
    * @param {obj} обьект или конструктор обьекта
    *
    **/
    addEventsListener : function(obj){
        if (obj.prototype) obj = obj.prototype;
        obj.on = function(arr,func,skipun){
            if (!(arr instanceof Array)) arr = [arr];
            for (var i = 0, l = arr.length; i < l; i++){
                var event = arr[i];
                if (!skipun) this.un(event,func);
                if(!this.events) this.events = {};
                if (!this.events[event]) this.events[event] = [];
                this.events[event].push(func);
            }
        }
        obj.un = function(arr, func, equal){        
            if (!(arr instanceof Array)) arr = [arr];
            for (var i = 0, l = arr.length; i < l; i++){
                var event = arr[i];
                if (!func) return this.unall(event);
                var arrev = this.events ? this.events[event]:null;
                if (arrev) {
                    $.arrayRemoveOf(arrev, func, !equal);
                    this.events[event] = arrev;
                }
            }
        }
        obj.unall = function(event){
            if (this.events) {
                if (event) delete this.events[event]; else delete this.events;
            }
        }
        obj.fireEvent = function(event, options){
            var arr = this.events ? this.events[event] : null;
            if (arr) {
                //if (!options) options = {};
                var res = null,
                    args = [].slice.call(arguments);
                args.shift();
                args.push(event);
                for (var i = 0; i < arr.length; i++){
                    try{
                        var r = arr[i].apply(this, args);//arr[i](options)
                        if (res !== false && r != null) res = r;//if (r != null) res = res == null ? r : res * r;
                    } catch (ex){
                        error(ex);
                    }
                }
                return res;
            } 
        }
        return obj;
    },

    addContainerListener : function(obj){
        if (obj.prototype) obj = obj.prototype;
        var registered = {},
            toall = {};
        obj.register = function(thread){
            var events = registered[thread.id];
            if (events){
                for (var i in events){
                    for (var j = 0, len = events[i].length; j < len; j++)
                        thread.on(i,events[i][j]);
                }
            }
            for (var i in toall){
                var events = toall[i];
                for (var j = 0, len = events.length; j < len; j++)
                    thread.on(i,events[j]);
            }
        }

        obj.on = function(arr, event, func, skipun){
            if (!(arr instanceof Array)) arr = [arr];
            for (var i = 0, l = arr.length; i < l; i++){
                var id = arr[i];
                if (!registered[id]) registered[id] = {};
                if (!registered[id][event]) registered[id][event] = [];
                registered[id][event].push(func);
                if (this.thread[id]) this.thread[id].on(event, func, skipun);
            }
        }

        obj.onall = function(event, func, skipun){
            if (!toall[event]) toall[event] = [];
            toall[event].push(func);
            var th = this.thread;
            for (var i in th)
                if (th[i]) th[i].on(event, func, skipun);
        }

        obj.unall = function(event, func, equal){
            if (event){
                if (func) {
                    var arr = toall[event];
                    $.arrayRemoveOf(arr, func, !equal);
                    toall[event] = arr;
                } else 
                   toall[event] = [];
            } else
                toall = {};
            var th = this.thread;
            for (var i in th)
                if (th[i]) th[i].un(event, func, equal);
        }


        obj.un = function(arr, event, func, equal){
            if (!(arr instanceof Array)) arr = [arr];
            for (var i = 0, l = arr.length; i < l; i++){
                var id = arr[i];        
                if (!func) {
                    if (id){
                        if (registered[id]) {
                            if (event) delete registered[id][event]; else delete registered[id];
                        }
                    } else
                        registered = {};
    
                    var list = {};            
                    if (id) list[id] = this.thread[id]; else list = this.thread;
                    for (var j in list)
                        if (list[j]) list[j].unall(event);
                } else {
                    var arrev = registered[id] ? registered[id][event] : null;
                    if (arrev) {
                        $.arrayRemoveOf(arrev, func, !equal);
                        registered[id][event] = arrev;
                    }
                    if (this.thread[id]) this.thread[id].un(event, func, equal);
                }
            }
        }

        obj.fireEvent = function(id, event, options){
            if (this.thread[id]) return this.thread[id].fireEvent(event, options);
        }
        
        return obj;
    },


    /**
    * Контейнер объектов процесса запроса данных
    **/
    Data : {
        thread : {}
    },

    /**
    * Экспериментальный объект менеджер лоадеров-сигнализаторов
    **/
    Loader : {
        show: function(){
            $.showLoading(1, $.getLoader());
        },
        
        hide: function(){
            $.showLoading(0, $.getLoader());
        }
    },

    parseUri : function (source, ops) { 
        var options = { 
            strictMode: 0, 
            key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"], 
            q: { 
                name: "queryKey", 
                parser: /(?:^|&)([^&=]*)=?([^&]*)/g 
            }, 
            parser: { 
                strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/, 
                loose: /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ 
            } 
        }
        var o = ops ? ops : options, value = o.parser[o.strictMode ? "strict" : "loose"].exec(source); 
        for (var i = 0, uri = {}; i < 14; i++) { uri[o.key[i]] = value[i] || ""; } 
        uri[o.q.name] = {}; 
        uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) { if ($1) uri[o.q.name][$1] = $2; }); return uri;         
    },

    /**
    * Функция для отображения ошибок запроса HTML страниц
    * @param {String} url URL адрес запроса
    * @param {Integer} status код сообщения
    * @return {String} statusText текст сообщения
    **/
    showMessage : function(url, status, statusText){
        if (status == 0) return;
        alert('Error ' + status + ' : ' + url + '\n' + statusText);
    },

    /**
    * Функция генерации уникального Id
    **/
    genId : function(){
        return X('genid'+D.sprt) + ($.lastGenId ? ++$.lastGenId : $.lastGenId=1);
    }
})
var D = $.Default;
/**
* Функция для фо??мирования имени атрибута с префиксом 
**/ 
var X = function(str){
    return D.prefix+D.sprt+str;
}
/**
* Функция для формирования имени параметра/достуа к значению параметра/присвоению значения параметру 'ax:place:mark' - применяется для указания места вставки HTML 
**/ 
var PM = $.placeMark = function(el, bool){
    var pm = X('place'+D.sprt+'mark');
    if (el && bool != null) el[pm] = bool; 
    return el ? (bool == null ? el[pm] : el) : pm; 
}

/**
* Функция для формирования HTML кода элемента, обозначающего местоположение скрипта    
**/ 
var SP = function(place){
    return '<span id="'+place+'" style="display:none"><!--place of script # ' + place + '//--></span>'
}

})(SRAX)

SRAX.init();
} 
