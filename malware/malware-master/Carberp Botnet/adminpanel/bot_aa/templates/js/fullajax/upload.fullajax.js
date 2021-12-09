/**
* Fullajax = AJAX & AHAH library
* http://www.fullajax.ru
* SiRusAjaX - SRAX v1.0.4 build 8 (upload)
* Copyright(c) 2007-2010, Ruslan Sinitskiy.
* http://fullajax.ru/#:license
**/

if (!window.SRAX || window.SRAX.TYPE != 'full'){

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
    TYPE : 'upload',       

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
    * Объект квази-AJAX аплоадер файлов<br><br>
    *
    * пример использования <br>
    * &lt;form action="/upload.jsp" method="post" enctype="multipart/form-data" onsubmit="new SRAX.Uploader(this, startCallback, finishCallback)"> <br>
    * &nbsp;&nbsp;&nbsp;&nbsp;  &lt;input type="file" name="form[file]" /> <br>
    * &lt;/form>
    *
    * @param {String/Element} form id формы или сама форма 
    * @param {Function} beforeStart выполняемая функция до начала загрузки
    * @return {Function} afterFinish выполняемая функция после окончания загрузки
    * @param {Boolean} manual флаг старта загрузки вручную (form.submit())
    * @param {Boolean} html флаг возврата в функцию afterFinish innerHTML (по умолчанию возвращается текст без тегов textContent)
    **/
    Uploader : function(form, beforeStart, afterFinish, manual, html){
        if (typeof form == 'object' && form.nodeName != 'FORM'){
            beforeStart = form.beforeStart;
            afterFinish = form.afterFinish;
            manual = form.manual;
            html = form.html;
            form = from.form;
        }
        var container,
            iframe = null,
            _this = this;
        this.init = function() {
            form = $.get(form);
            var id = $.genId();
            form.setAttribute('target', id);
            container = document.createElement('div');
            container.innerHTML = '<iframe style="display:none" src="javascript:true" onload="this._onload()" id="'+id+'" name="'+id+'"></iframe>';
            this.iframe = iframe = container.firstChild;

            this.setAfterFinish = setAfterFinish = function(afterFinish){
                iframe._onload = function(){
                    var content = this.contentWindow || this.contentDocument,
                        body = content.document.body,
                        text = body[html ? 'innerHTML' : ($.browser.msie ? 'innerText' : 'textContent')];
                    afterFinish(text, _this);
                }
            }

            if (afterFinish) {
                var set = function(){
                    setAfterFinish(afterFinish);
                    if (manual) form.submit()
                }
                if (manual) iframe._onload = set; else set(); 
            } else iframe._onload = function(){}
            form.appendChild(container);
            form.setAttribute('target', id);
            if (beforeStart) beforeStart(_this);
        }
        
        this.init();

        this.getIframe = function(){
            return iframe;
        }

        this.cancel = function(){
            form.reset();
            _this.destroy();
        }

        this.destroy = function(){
           iframe.src = 'javascript:true';
           SRAX.remove(container);
           container = null;
        }
        
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
