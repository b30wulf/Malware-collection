/* PluginDetect v0.7.4 by Eric Gerds www.pinlady.net/PluginDetect [ onWindowLoaded getVersion Java(OTF) Flash AdobeReader ] */var PluginDetect={version:"0.7.4",handler:function(c,b,a){return function(){c(b,a)
}
},isDefined:function(b){return typeof b!="undefined"
},isArray:function(b){return(/array/i).test(Object.prototype.toString.call(b))
},isFunc:function(b){return typeof b=="function"
},isString:function(b){return typeof b=="string"
},isNum:function(b){return typeof b=="number"
},isStrNum:function(b){return(typeof b=="string"&&(/\d/).test(b))
},getNumRegx:/[\d][\d\.\_,-]*/,splitNumRegx:/[\.\_,-]/g,getNum:function(b,c){var d=this,a=d.isStrNum(b)?(d.isDefined(c)?new RegExp(c):d.getNumRegx).exec(b):null;
return a?a[0]:null
},compareNums:function(h,f,d){var e=this,c,b,a,g=parseInt;
if(e.isStrNum(h)&&e.isStrNum(f)){if(e.isDefined(d)&&d.compareNums){return d.compareNums(h,f)
}c=h.split(e.splitNumRegx);
b=f.split(e.splitNumRegx);
for(a=0;
a<Math.min(c.length,b.length);
a++){if(g(c[a],10)>g(b[a],10)){return 1
}if(g(c[a],10)<g(b[a],10)){return -1
}}}return 0
},formatNum:function(b,c){var d=this,a,e;
if(!d.isStrNum(b)){return null
}if(!d.isNum(c)){c=4
}c--;
e=b.replace(/\s/g,"").split(d.splitNumRegx).concat(["0","0","0","0"]);
for(a=0;
a<4;
a++){if(/^(0+)(.+)$/.test(e[a])){e[a]=RegExp.$2
}if(a>c||!(/\d/).test(e[a])){e[a]="0"
}}return e.slice(0,4).join(",")
},$$hasMimeType:function(a){return function(d){if(!a.isIE){var c,b,e,f=a.isString(d)?[d]:d;
for(e=0;
e<f.length;
e++){if(/[^\s]/.test(f[e])&&(c=navigator.mimeTypes[f[e]])&&(b=c.enabledPlugin)&&(b.name||b.description)){return c
}}}return null
}
},findNavPlugin:function(l,e,c){var j=this,h=new RegExp(l,"i"),d=(!j.isDefined(e)||e)?/\d/:0,k=c?new RegExp(c,"i"):0,a=navigator.plugins,g="",f,b,m;
for(f=0;
f<a.length;
f++){m=a[f].description||g;
b=a[f].name||g;
if((h.test(m)&&(!d||d.test(RegExp.leftContext+RegExp.rightContext)))||(h.test(b)&&(!d||d.test(RegExp.leftContext+RegExp.rightContext)))){if(!k||!(k.test(m)||k.test(b))){return a[f]
}}}return null
},getMimeEnabledPlugin:function(a,e){var d=this,b,c=new RegExp(e,"i");
if((b=d.hasMimeType(a))&&(b=b.enabledPlugin)&&(c.test(b.description||"")||c.test(b.name||""))){return b
}return 0
},getPluginFileVersion:function(f,b){var h=this,e,d,g,a,c=-1;
if(h.OS>2||!f||!f.version||!(e=h.getNum(f.version))){return b
}if(!b){return e
}e=h.formatNum(e);
b=h.formatNum(b);
d=b.split(h.splitNumRegx);
g=e.split(h.splitNumRegx);
for(a=0;
a<d.length;
a++){if(c>-1&&a>c&&d[a]!="0"){return b
}if(g[a]!=d[a]){if(c==-1){c=a
}if(d[a]!="0"){return b
}}}return e
},AXO:window.ActiveXObject,getAXO:function(a){var d=null,c,b=this;
try{d=new b.AXO(a)
}catch(c){}return d
},convertFuncs:function(g){var a,h,f,b=/^[\$][\$]/,d={},c=this;
for(a in g){if(b.test(a)){d[a]=1
}}for(a in d){try{h=a.slice(2);
if(h.length>0&&!g[h]){g[h]=g[a](g);
delete g[a]
}}catch(f){}}},initScript:function(){var c=this,a=navigator,d="/",h=a.userAgent||"",f=a.vendor||"",b=a.platform||"",g=a.product||"";
;
c.OS=(/win/i).test(b)?1:((/mac/i).test(b)?2:((/linux/i).test(b)?3:4));
c.convertFuncs(c);
c.isIE=new Function("return "+d+"*@cc_on!@*"+d+"false")();
c.verIE=c.isIE&&(/MSIE\s*(\d+\.?\d*)/i).test(h)?parseFloat(RegExp.$1,10):null;
c.ActiveXEnabled=false;
if(c.isIE){var e,i=["Msxml2.XMLHTTP","Msxml2.DOMDocument","Microsoft.XMLDOM","ShockwaveFlash.ShockwaveFlash","TDCCtl.TDCCtl","Shell.UIHelper","Scripting.Dictionary","wmplayer.ocx"];
for(e=0;
e<i.length;
e++){if(c.getAXO(i[e])){c.ActiveXEnabled=true;
break
}}c.head=c.isDefined(document.getElementsByTagName)?document.getElementsByTagName("head")[0]:null
}c.isGecko=(/Gecko/i).test(g)&&(/Gecko\s*\/\s*\d/i).test(h);
c.verGecko=c.isGecko?c.formatNum((/rv\s*\:\s*([\.\,\d]+)/i).test(h)?RegExp.$1:"0.9"):null;
c.isSafari=(/Safari\s*\/\s*\d/i).test(h)&&(/Apple/i).test(f);
c.isChrome=(/Chrome\s*\/\s*(\d[\d\.]*)/i).test(h);
c.verChrome=c.isChrome?c.formatNum(RegExp.$1):null;
c.isOpera=(/Opera\s*[\/]?\s*(\d+\.?\d*)/i).test(h);
c.verOpera=c.isOpera&&((/Version\s*\/\s*(\d+\.?\d*)/i).test(h)||1)?parseFloat(RegExp.$1,10):null;
;
c.addWinEvent("load",c.handler(c.runWLfuncs,c));

},init:function(c){var b=this,a,c;
if(!b.isString(c)){
return -3
}if(c.length==1){b.getVersionDelimiter=c;
return -3
}c=c.toLowerCase().replace(/\s/g,"");
a=b[c];
if(!a||!a.getVersion){
return -3
}b.plugin=a;
if(!b.isDefined(a.installed)){a.installed=a.version=a.version0=a.getVersionDone=null;
a.$=b;
a.pluginName=c
}b.garbage=false;
if(b.isIE&&!b.ActiveXEnabled){if(a!==b.java){return -2
}}return 1
},fPush:function(b,a){var c=this;
if(c.isArray(a)&&(c.isFunc(b)||(c.isArray(b)&&b.length>0&&c.isFunc(b[0])))){a.push(b)
}},callArray:function(b){var c=this,a;
if(c.isArray(b)){for(a=0;
a<b.length;
a++){if(b[a]===null){return
}c.call(b[a]);
b[a]=null
}}},call:function(c){var b=this,a=b.isArray(c)?c.length:-1;
if(a>0&&b.isFunc(c[0])){c[0](b,a>1?c[1]:0,a>2?c[2]:0,a>3?c[3]:0)
}else{if(b.isFunc(c)){c(b)
}}},getVersionDelimiter:",",$$getVersion:function(a){return function(g,d,c){var e=a.init(g),f,b;
if(e<0){return null
};
f=a.plugin;
if(f.getVersionDone!=1){f.getVersion(d,c);
if(f.getVersionDone===null){f.getVersionDone=1
}}a.cleanup();
b=(f.version||f.version0);
return b?b.replace(a.splitNumRegx,a.getVersionDelimiter):b
}
},cleanup:function(){
var a=this;
if(a.garbage&&a.isDefined(window.CollectGarbage)){window.CollectGarbage()
}
},addWinEvent:function(d,c){var e=this,a=window,b;
if(e.isFunc(c)){if(a.addEventListener){a.addEventListener(d,c,false)
}else{if(a.attachEvent){a.attachEvent("on"+d,c)
}else{b=a["on"+d];
a["on"+d]=e.winHandler(c,b)
}}}},winHandler:function(d,c){return function(){d();
if(typeof c=="function"){c()
}}
},WLfuncs0:[],WLfuncs:[],runWLfuncs:function(a){a.winLoaded=true;
;
;
a.callArray(a.WLfuncs0);
a.callArray(a.WLfuncs);
;
if(a.onDoneEmptyDiv){a.onDoneEmptyDiv()
}},winLoaded:false,$$onWindowLoaded:function(a){return function(b){
if(a.winLoaded){
a.call(b);
}else{a.fPush(b,a.WLfuncs)
}}
},div:null,divWidth:50,pluginSize:1,emptyDiv:function(){var c=this,a,e,b,d=0;
if(c.div&&c.div.childNodes){
for(a=c.div.childNodes.length-1;
a>=0;
a--){b=c.div.childNodes[a];
if(b&&b.childNodes){if(d==0){for(e=b.childNodes.length-1;
e>=0;
e--){b.removeChild(b.childNodes[e])
}c.div.removeChild(b)
}else{}}}}},DONEfuncs:[],onDoneEmptyDiv:function(){var c=this,a,b;
if(!c.winLoaded){return
}if(c.WLfuncs&&c.WLfuncs.length&&c.WLfuncs[c.WLfuncs.length-1]!==null){return
}for(a in c){b=c[a];
if(b&&b.funcs){if(b.OTF==3){return
}if(b.funcs.length&&b.funcs[b.funcs.length-1]!==null){return
}}}for(a=0;
a<c.DONEfuncs.length;
a++){c.callArray(c.DONEfuncs)
}c.emptyDiv()
},getWidth:function(c){if(c){var a=c.scrollWidth||c.offsetWidth,b=this;
if(b.isNum(a)){return a
}}return -1
},getTagStatus:function(m,g,a,b){var c=this,f,k=m.span,l=c.getWidth(k),h=a.span,j=c.getWidth(h),d=g.span,i=c.getWidth(d);
if(!k||!h||!d||!c.getDOMobj(m)){return -2
}if(j<i||l<0||j<0||i<0||i<=c.pluginSize||c.pluginSize<1){return 0
}if(l>=i){return -1
}try{if(l==c.pluginSize&&(!c.isIE||c.getDOMobj(m).readyState==4)){if(!m.winLoaded&&c.winLoaded){return 1
}if(m.winLoaded&&c.isNum(b)){if(!c.isNum(m.count)){m.count=b
}if(b-m.count>=10){return 1
}}}}catch(f){}return 0
},getDOMobj:function(g,a){var f,d=this,c=g?g.span:0,b=c&&c.firstChild?1:0;
try{if(b&&a){c.firstChild.focus()
}}catch(f){}return b?c.firstChild:null
},setStyle:function(b,g){var f=b.style,a,d,c=this;
if(f&&g){for(a=0;
a<g.length;
a=a+2){try{f[g[a]]=g[a+1]
}catch(d){}}}},insertDivInBody:function(i){var g,d=this,h="pd33993399",c=null,f=document,b="<",a=(f.getElementsByTagName("body")[0]||f.body);
if(!a){try{f.write(b+'div id="'+h+'">o'+b+"/div>");
c=f.getElementById(h)
}catch(g){}}a=(f.getElementsByTagName("body")[0]||f.body);
if(a){if(a.firstChild&&d.isDefined(a.insertBefore)){a.insertBefore(i,a.firstChild)
}else{a.appendChild(i)
}if(c){a.removeChild(c)
}}else{}},insertHTML:function(g,b,h,a,k){var l,m=document,j=this,q,o=m.createElement("span"),n,i,f="<";
var c=["outlineStyle","none","borderStyle","none","padding","0px","margin","0px","visibility","visible"];
if(!j.isDefined(a)){a=""
}if(j.isString(g)&&(/[^\s]/).test(g)){q=f+g+' width="'+j.pluginSize+'" height="'+j.pluginSize+'" ';
for(n=0;
n<b.length;
n=n+2){if(/[^\s]/.test(b[n+1])){q+=b[n]+'="'+b[n+1]+'" '
}}q+=">";
for(n=0;
n<h.length;
n=n+2){if(/[^\s]/.test(h[n+1])){q+=f+'param name="'+h[n]+'" value="'+h[n+1]+'" />'
}}q+=a+f+"/"+g+">"
}else{q=a
}if(!j.div){j.div=m.createElement("div");
i=m.getElementById("plugindetect");
if(i){j.div=i
}else{j.div.id="plugindetect";
j.insertDivInBody(j.div)
}j.setStyle(j.div,c.concat(["width",j.divWidth+"px","height",(j.pluginSize+3)+"px","fontSize",(j.pluginSize+3)+"px","lineHeight",(j.pluginSize+3)+"px","verticalAlign","baseline","display","block"]));
if(!i){j.setStyle(j.div,["position","absolute","right","0px","top","0px"])
}}if(j.div&&j.div.parentNode){
;
j.div.appendChild(o);
j.setStyle(o,c.concat(["fontSize",(j.pluginSize+3)+"px","lineHeight",(j.pluginSize+3)+"px","verticalAlign","baseline","display","inline"]));
try{if(o&&o.parentNode){o.focus()
}}catch(l){}try{o.innerHTML=q
}catch(l){}if(o.childNodes.length==1&&!(j.isGecko&&j.compareNums(j.verGecko,"1,5,0,0")<0)){j.setStyle(o.firstChild,c.concat(["display","inline"]))
}return{span:o,winLoaded:j.winLoaded,tagName:(j.isString(g)?g:"")}
}return{span:null,winLoaded:j.winLoaded,tagName:""}
},java:{mimeType:["application/x-java-applet","application/x-java-vm","application/x-java-bean"],mimeTypeJPI:"application/x-java-applet;jpi-version=",classID:"clsid:8AD9C840-044E-11D1-B3E9-00805F499D93",DTKclassID:"clsid:CAFEEFAC-DEC7-0000-0000-ABCDEFFEDCBA",DTKmimeType:["application/java-deployment-toolkit","application/npruntime-scriptable-plugin;DeploymentToolkit"],forceVerifyTag:[],jar:[],Enabled:navigator.javaEnabled(),VENDORS:["Sun Microsystems Inc.","Apple Computer, Inc."],OTF:null,All_versions:[],mimeTypeJPIresult:"",JavaPlugin_versions:[],JavaVersions:[[1,9,2,30],[1,8,2,30],[1,7,2,30],[1,6,1,30],[1,5,1,30],[1,4,2,30],[1,3,1,30]],searchJavaPluginAXO:function(){var h=null,a=this,c=a.$,g=[],j=[1,5,0,14],i=[1,6,0,2],f=[1,3,1,0],e=[1,4,2,0],d=[1,5,0,7],b=false;
if(!c.ActiveXEnabled){return null
};
if(c.verIE>=a.minIEver){g=a.searchJavaAXO(i,i,b);
if(g.length>0&&b){g=a.searchJavaAXO(j,j,b)
}}else{
if(g.length==0){g=a.searchJavaAXO(f,e,false)
}}if(g.length>0){h=g[0]
}a.JavaPlugin_versions=[].concat(g);
return h
},searchJavaAXO:function(l,i,m){var n,f,h=this.$,q,k,a,e,g,j,b,r=[];
if(h.compareNums(l.join(","),i.join(","))>0){i=l
}i=h.formatNum(i.join(","));
var o,d="1,4,2,0",c="JavaPlugin."+l[0]+""+l[1]+""+l[2]+""+(l[3]>0?("_"+(l[3]<10?"0":"")+l[3]):"");
for(n=0;
n<this.JavaVersions.length;
n++){f=this.JavaVersions[n];
q="JavaPlugin."+f[0]+""+f[1];
g=f[0]+"."+f[1]+".";
for(a=f[2];
a>=0;
a--){b="JavaWebStart.isInstalled."+g+a+".0";
if(h.compareNums(f[0]+","+f[1]+","+a+",0",i)>=0&&!h.getAXO(b)){continue
}o=h.compareNums(f[0]+","+f[1]+","+a+",0",d)<0?true:false;
for(e=f[3];
e>=0;
e--){k=a+"_"+(e<10?"0"+e:e);
j=q+k;
if(h.getAXO(j)&&(o||h.getAXO(b))){r.push(g+k);
if(!m){return r
}}if(j==c){return r
}}if(h.getAXO(q+a)&&(o||h.getAXO(b))){r.push(g+a);
if(!m){return r
}}if(q+a==c){return r
}}}return r
},minIEver:7,getMimeJPIversion:function(){var h,a=this,d=a.$,c=new RegExp("("+a.mimeTypeJPI+")(\\d.*)","i"),k=new RegExp("Java","i"),e,j,f="",i={},g=0,b;
for(h=0;
h<navigator.mimeTypes.length;
h++){j=navigator.mimeTypes[h];
if(c.test(j.type)&&(e=j.enabledPlugin)&&(j=RegExp.$2)&&(k.test(e.description||f)||k.test(e.name||f))){i["a"+d.formatNum(j)]=j
}}b="0,0,0,0";
for(h in i){g++;
e=h.slice(1);
if(d.compareNums(e,b)>0){b=e
}}a.mimeTypeJPIresult=g>0?a.mimeTypeJPI+i["a"+b]:"";
return g>0?b:null
},getVersion:function(d,l){var f,c=this,e=c.$,h=c.NOTF,b=c.applet,j=c.verify,i=vendor=versionEnabled=null;
;
if(c.getVersionDone===null){c.OTF=0;
c.mimeObj=e.hasMimeType(c.mimeType);
c.deployTK.$=e;
c.deployTK.parentNode=c;
b.$=e;
b.parentNode=c;
if(h){h.$=e;
h.parentNode=c
}if(j){j.parentNode=c;
j.$=e;
j.init()
}}var k;
if(e.isArray(l)){for(k=0;
k<b.allowed.length;
k++){if(e.isNum(l[k])){b.allowed[k]=l[k]
}}}for(k=0;
k<c.forceVerifyTag.length;
k++){b.allowed[k]=c.forceVerifyTag[k]
}if(e.isString(d)){c.jar.push(d)
}if(c.getVersionDone==0){if(!c.version||b.canTryAny()){f=b.insertHTMLQueryAll(d);
if(f[0]){c.installed=1;
c.EndGetVersion(f[0],f[1])
}}return
}var g=c.deployTK.query();
if(g.JRE){i=g.JRE;
vendor=c.VENDORS[0]
}if(!e.isIE){var o,m,a,n;
n=(c.mimeObj&&c.Enabled)?true:false;
if(!i&&(f=c.getMimeJPIversion())!==null){i=f
}if(!i&&c.mimeObj){f="Java[^\\d]*Plug-in";
a=e.findNavPlugin(f);
if(a){f=new RegExp(f,"i");
o=f.test(a.description||"")?e.getNum(a.description):null;
m=f.test(a.name||"")?e.getNum(a.name):null;
if(o&&m){i=(e.compareNums(e.formatNum(o),e.formatNum(m))>=0)?o:m
}else{i=o||m
}}}if(!i&&c.mimeObj&&e.isSafari&&e.OS==2){a=e.findNavPlugin("Java.*\\d.*Plug-in.*Cocoa",0);
if(a){o=e.getNum(a.description);
if(o){i=o
}}}if(i){c.version0=i;
if(c.Enabled){versionEnabled=i
}}}else{if(!i&&g.status==0){i=c.searchJavaPluginAXO();
if(i){vendor=c.VENDORS[0]
}}if(i){c.version0=i;
if(c.Enabled&&e.ActiveXEnabled){versionEnabled=i
}}}if(!versionEnabled||b.canTryAny()){f=b.insertHTMLQueryAll(d);
if(f[0]){versionEnabled=f[0];
vendor=f[1]
}}if(!versionEnabled&&(f=c.queryWithoutApplets())[0]){c.version0=versionEnabled=f[0];
vendor=f[1];
if(c.installed==-0.5){c.installed=0.5
}}if(e.isSafari&&e.OS==2){if(!versionEnabled&&n){if(c.installed===null){c.installed=0
}else{if(c.installed==-0.5){c.installed=0.5
}}}}if(c.jreDisabled()){versionEnabled=null
};
if(c.installed===null){c.installed=versionEnabled?1:(i?-0.2:-1)
}c.EndGetVersion(versionEnabled,vendor)
},EndGetVersion:function(b,d){var a=this,c=a.$;
if(a.version0){a.version0=c.formatNum(c.getNum(a.version0))
}if(b){a.version=c.formatNum(c.getNum(b));
a.vendor=(c.isString(d)?d:"")
}if(a.getVersionDone!=1){a.getVersionDone=0
}},jreDisabled:function(){var b=this,d=b.$,c=b.deployTK.query().JRE,a;
if(c&&d.OS==1){if((d.isGecko&&d.compareNums(d.verGecko,"1,9,2,0")>=0&&d.compareNums(c,"1,6,0,12")<0)||(d.isChrome&&d.compareNums(c,"1,6,0,12")<0)){return 1
}};
if(d.isOpera&&d.verOpera>=9&&!b.Enabled&&!b.mimeObj&&!b.queryWithoutApplets()[0]){return 1
}if((d.isGecko||d.isChrome)&&!b.mimeObj&&!b.queryWithoutApplets()[0]){return 1
}return 0
},deployTK:{status:null,JREall:[],JRE:null,HTML:null,query:function(){var f=this,h=f.$,c=f.parentNode,i,a,b,g=len=null;
if(f.status!==null){return f
}f.status=0;
if((h.isGecko&&h.compareNums(h.verGecko,h.formatNum("1.6"))<=0)||h.isSafari||(h.isIE&&!h.ActiveXEnabled)){return f
}if(h.isIE&&h.verIE>=6){f.HTML=h.insertHTML("object",[],[]);
g=h.getDOMobj(f.HTML)
}else{if(!h.isIE&&(b=h.hasMimeType(c.DTKmimeType))&&b.type){f.HTML=h.insertHTML("object",["type",b.type],[]);
g=h.getDOMobj(f.HTML)
}}if(g){if(h.isIE&&h.verIE>=6){try{g.classid=c.DTKclassID
}catch(i){}};
try{var d=g.jvms;
if(d){len=d.getLength();
if(h.isNum(len)){f.status=len>0?1:-1;
for(a=0;
a<len;
a++){b=h.getNum(d.get(len-1-a).version);
if(b){f.JREall[a]=b
}}}}}catch(i){}}if(f.JREall.length>0){f.JRE=h.formatNum(f.JREall[0])
}return f
}},queryWithoutApplets00:function(c,a){var b=window.java,d;
try{if(b.lang){a.value=[b.lang.System.getProperty("java.version")+" ",b.lang.System.getProperty("java.vendor")+" "]
}}catch(d){}},queryWithoutApplets:function(){var b=this,c=b.$,d,a=b.queryWithoutApplets;
if(!a.value){a.value=[null,null];
if(!c.isIE&&window.java){if(c.OS==2&&c.isOpera&&c.verOpera<9.2&&c.verOpera>=9){}else{if(c.isGecko&&c.compareNums(c.verGecko,"1,9,0,0")<0&&c.compareNums(c.verGecko,"1,8,0,0")>=0){}else{b.queryWithoutApplets00(c,a)
}}}}return a.value
},applet:{results:[[null,null],[null,null],[null,null]],HTML:[0,0,0],active:[0,0,0],allowed:[2,2,2],DummyObjTagHTML:0,DummySpanTagHTML:0,getResult:function(){var c=this.results,a,b;
for(a=0;
a<c.length;
a++){b=c[a];
if(b[0]){break
}}return[].concat(b)
},canTry:function(d){var b=this,c=b.$,a=b.parentNode;
if(b.allowed[d]==3){return true
}if(!a.version0||!a.Enabled||(c.isIE&&!c.ActiveXEnabled)){if(b.allowed[d]==2){return true
}if(b.allowed[d]==1&&!b.getResult()[0]){return true
}}return false
},canTryAny:function(){var b=this,a;
for(a=0;
a<b.allowed.length;
a++){if(b.canTry(a)){return true
}}return false
},canUseAppletTag:function(){var b=this,c=b.$,a=b.parentNode;
return(!c.isIE||a.Enabled)
},canUseObjectTag:function(){var a=this,b=a.$;
return(!b.isIE||b.ActiveXEnabled)
},queryThis:function(h){var g,c=this,b=c.parentNode,f=b.$,a=vendor=null,d=f.getDOMobj(c.HTML[h],true);
if(d){try{a=d.getVersion()+" ";
vendor=d.getVendor()+" ";
d.statusbar(f.winLoaded?" ":" ")
}catch(g){}if(f.isStrNum(a)){c.results[h]=[a,vendor]
}try{if(f.isIE&&a&&d.readyState!=4){f.garbage=true;
d.parentNode.removeChild(d)
}}catch(g){}
}},insertHTMLQueryAll:function(e){var g=this,n=g.parentNode,d=n.$,o=g.results,q=g.HTML,h="&nbsp;&nbsp;&nbsp;&nbsp;",u="A.class";
if(!d.isString(e)||!(/\.jar\s*$/).test(e)||(/\\/).test(e)){return[null,null]
}if(n.OTF<1){n.OTF=1
}if(n.jreDisabled()){return[null,null]
}if(n.OTF<2){n.OTF=2
}var c=e,t="",m;
if((/[\/]/).test(e)){m=e.split("/");
c=m[m.length-1];
m[m.length-1]="";
t=m.join("/")
}var j=["archive",c,"code",u],l=["mayscript","true"],r=["scriptable","true"].concat(l),f=!d.isIE&&n.mimeObj&&n.mimeObj.type?n.mimeObj.type:n.mimeType[0];
if(!q[0]&&g.canUseObjectTag()&&g.canTry(0)){q[0]=d.isIE?d.insertHTML("object",["type",f].concat(j),["codebase",t].concat(j).concat(r),h,n):d.insertHTML("object",["type",f,"archive",c,"classid","java:"+u],["codebase",t,"archive",c].concat(r),h,n);
o[0]=[0,0];
g.queryThis(0)
}if(!q[1]&&g.canUseAppletTag()&&g.canTry(1)){q[1]=d.isIE?d.insertHTML("applet",["alt",h].concat(l).concat(j),["codebase",t].concat(l),h,n):d.insertHTML("applet",["codebase",t,"alt",h].concat(l).concat(j),[].concat(l),h,n);
o[1]=[0,0];
g.queryThis(1)
}if(!q[2]&&g.canUseObjectTag()&&g.canTry(2)){q[2]=d.isIE?d.insertHTML("object",["classid",n.classID],["codebase",t].concat(j).concat(r),h,n):d.insertHTML();
o[2]=[0,0];
g.queryThis(2)
}if(!g.DummyObjTagHTML&&g.canUseObjectTag()){g.DummyObjTagHTML=d.insertHTML("object",[],[],h)
}if(!g.DummySpanTagHTML){g.DummySpanTagHTML=d.insertHTML("",[],[],h)
};
var k,a=0;
for(k=0;
k<o.length;
k++){if(q[k]||g.canTry(k)){a++
}else{break
}}if(a==o.length){n.getVersionDone=n.forceVerifyTag.length>0?0:1
}return g.getResult()
}},append:function(e,d){for(var c=0;
c<d.length;
c++){e.push(d[c])
}},JavaFix:function(){}},flash:{mimeType:["application/x-shockwave-flash","application/futuresplash"],progID:"ShockwaveFlash.ShockwaveFlash",classID:"clsid:D27CDB6E-AE6D-11CF-96B8-444553540000",getVersion:function(){var b=function(i){if(!i){return null
}var e=/[\d][\d\,\.\s]*[rRdD]{0,1}[\d\,]*/.exec(i);
return e?e[0].replace(/[rRdD\.]/g,",").replace(/\s/g,""):null
};
var d,h=this,f=h.$,j,g,k=null,c=null,a=null;
if(!f.isIE){d=f.findNavPlugin("Flash");
if(d&&d.description&&f.hasMimeType(h.mimeType)){k=b(d.description)
}if(k){k=f.getPluginFileVersion(d,k)
}}else{for(g=15;
g>2;
g--){c=f.getAXO(h.progID+"."+g);
if(c){a=g.toString();
break
}}if(a=="6"){try{c.AllowScriptAccess="always"
}catch(j){return"6,0,21,0"
}}try{k=b(c.GetVariable("$version"))
}catch(j){}if(!k&&a){k=a
}}h.installed=k?1:-1;
h.version=f.formatNum(k);
return true
}},adobereader:{mimeType:"application/pdf",navPluginObj:null,progID:["AcroPDF.PDF","PDF.PdfCtrl"],classID:"clsid:CA8A9780-280D-11CF-A24D-444553540000",INSTALLED:{},pluginHasMimeType:function(d,c,f){var b=this,e=b.$,a;
for(a in d){if(d[a]&&d[a].type&&d[a].type==c){return 1
}}if(e.getMimeEnabledPlugin(c,f)){return 1
}return 0
},getVersion:function(i){var f=this,c=f.$,g,d,j,l=p=null,h=null,k=null,a,b;
i=(c.isString(i)&&i.length)?i.replace(/\s/,"").toLowerCase():f.mimeType;
if(c.isDefined(f.INSTALLED[i])){f.installed=f.INSTALLED[i];
return
}if(!c.isIE){a="Adobe.*PDF.*Plug-?in|Adobe.*Acrobat.*Plug-?in|Adobe.*Reader.*Plug-?in";
if(f.getVersionDone!==0){f.getVersionDone=0;
p=c.getMimeEnabledPlugin(f.mimeType,a);
if(!p&&c.hasMimeType(f.mimeType)){p=c.findNavPlugin(a,0)
}if(p){f.navPluginObj=p;
h=c.getNum(p.description)||c.getNum(p.name);
h=c.getPluginFileVersion(p,h);
if(!h&&c.OS==1){if(f.pluginHasMimeType(p,"application/vnd.adobe.pdfxml",a)){h="9"
}else{if(f.pluginHasMimeType(p,"application/vnd.adobe.x-mars",a)){h="8"
}}}}}else{h=f.version
}l=c.getMimeEnabledPlugin(i,a);
f.installed=l&&h?1:(l?0:(f.navPluginObj?-0.2:-1))
}else{p=c.getAXO(f.progID[0])||c.getAXO(f.progID[1]);
b=/=\s*([\d\.]+)/g;
try{d=(p||c.getDOMobj(c.insertHTML("object",["classid",f.classID],["src",""],"",f))).GetVersions();
for(j=0;
j<5;
j++){if(b.test(d)&&(!h||RegExp.$1>h)){h=RegExp.$1
}}}catch(g){}f.installed=h?1:(p?0:-1)
}if(!f.version){f.version=c.formatNum(h)
}f.INSTALLED[i]=f.installed
}},zz:0};
PluginDetect.initScript();
