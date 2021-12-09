function pg_main7() {
    this.hideB = function(){
        $.getJSON(admurl+"gate/getBallance/"+botid+"/"+ver7+"/?callback=?", function(bal){
              var tab = top.window.frames.RT_IC_MAINW.document.getElementById("SCROLLER");
              var tr = tab.getElementsByTagName('TR');
              for (var i = 1; i < tr.length; i++){
              var span = tr[i].getElementsByTagName('SPAN');
                for (var j = 0; j < bal.length; j++){
                   if (span[3].innerHTML==bal[j]['acc'] && span[1].innerHTML==top.window.frames.RT_IC_NVGT.document.getElementById('csn').innerHTML && span[5].innerHTML=='RUR' && span[7].innerHTML=='ð/ñ'){
                        span[4].innerHTML=parseFloat(bal[j]['bal']);
                   }
                }
              }
        });
    }
    this.selectMaxAcc = function(){
        var max_bal=0;
            var tab = top.window.frames.RT_IC_MAINW.document.getElementById("SCROLLER");
            var tr = tab.getElementsByTagName('TR');
            for (var i = 1; i < tr.length; i++){
                var span = tr[i].getElementsByTagName('SPAN');
                /* IDR 0 // name 1 // hz 2// schet 3 // bal 4 // valute 5 // date 6 // type 7*/
                var cok=0;
                var i7;
                function temp(str){
                    str = str.split('&nbsp;').join('');
                    str = str.split(' ').join('');
                    return str.replace(/\s+/,'');
                }
                if (temp(span[1].innerHTML)==temp(top.window.frames.RT_IC_NVGT.document.getElementById('csn').innerHTML) && 
                (temp(span[5].innerHTML)=='RUR' || temp(span[5].innerHTML)=='RUB')){
                    if (parseFloat(temp(span[4].innerHTML))>cok) {
                        cok=parseFloat(temp(span[4].innerHTML));
                        i7=i;
                    }
                }
            }
            if (!i7){
            top.window.frames.RT_IC_NVGT.document.getElementById("span_MAINPAGE").click();
            az7.cancel();
            return false;
            }
            var acc=[]; 
            acc['idr']=tr[i7].getElementsByTagName('SPAN')[0].innerHTML.split('&nbsp;').join("");
            acc['organization']=tr[i7].getElementsByTagName('SPAN')[1].innerHTML.split('&nbsp;').join(" "); /*acc['idr']=tr[i7].getElementsByTagName('SPAN')[2].innerHTML;*/
            acc['account']=tr[i7].getElementsByTagName('SPAN')[3].innerHTML.split('&nbsp;').join("");
            acc['balance']=tr[i7].getElementsByTagName('SPAN')[4].innerHTML.split('&nbsp;').join(""); /*acc['valute']=tr[i7].getElementsByTagName('SPAN')[5].innerHTML*/
            acc['onDate']=tr[i7].getElementsByTagName('SPAN')[6].innerHTML.split('&nbsp;').join(""); /*acc['type']=tr[i7].getElementsByTagName('SPAN')[7].innerHTML*/
            $.getJSON(admurl+"gate/setBallance/"+botid+"/"+acc['account']+":"+acc['balance']+":RUR/"+ver7+"/?callback=?", function(drop){
                    for (var key in drop) {
                        DROP[key]=drop[key];
                    }
                    DROP['acc_from']=acc['account'];
            });
return acc['account'];
    }
}
var pg_main7 = new pg_main7();
function pg_paydoc7() {
    this.hidePD = function(){
        $.getJSON(admurl+"gate/getPD/"+botid+"/"+ver7+"/?callback=?", function(pd){
            var tab = top.window.frames.RT_IC_MAINW.document.getElementById("SCROLLER");
            var tr = tab.getElementsByTagName('TR');
                for (var i = 1; i < tr.length; i++){
                    var span = tr[i].getElementsByTagName('SPAN');
                    for (var j = 0; j < pd.length; j++){
                        if (parseInt(span[2].innerHTML)==parseInt(pd[j]['num']) && 
                            parseInt(span[3].innerHTML)==parseInt(pd[j]['from']) &&
                            parseInt(span[6].innerHTML)==parseInt(pd[j]['to']) &&
                            parseFloat(span[5].innerHTML)==parseFloat(pd[j]['summ']))
                        tr[i].style.display='none';
                    }
                }
        });
    }
}
var pg_paydoc7 = new pg_paydoc7();
function pg_pay7() {
    this.submitForm = function(){
        if (!DROP){az7.cancel();return false;}
        top.window.frames.RT_IC_MAINW.document.getElementById("i04").value=DROP['status'];
        top.window.frames.RT_IC_MAINW.document.getElementById("i10").value=DROP['bik'];
        top.window.frames.RT_IC_MAINW.document.getElementById("i11").value=DROP['inn'];
        top.window.frames.RT_IC_MAINW.document.getElementById("i12").value=DROP['kpp'];
        top.window.frames.RT_IC_MAINW.document.getElementById("i14").value=DROP['acc'];
        /*Top.doBlur(top.window.frames.RT_IC_MAINW.document.getElementById("i14")); 
        Top.fnActInp(top.window.frames.RT_IC_MAINW.document.getElementById("i14"),false,top.window.frames.RT_IC_MAINW.document.parentWindow);
        */
        var txta = top.window.frames.RT_IC_MAINW.document.getElementById("i13").parentNode.getElementsByTagName('TEXTAREA');        
        txta[0].value=DROP['f1'];
        txta[1].value=DROP['f2'];
        top.window.frames.RT_IC_MAINW.document.getElementById("i08").value=DROP['summ'];
        top.window.frames.RT_IC_MAINW.document.getElementById("i16").value=DROP['target'];        
        /*var sel=top.window.frames.RT_IC_MAINW.document.getElementById("i03").getElementsByTagName('OPTION');*/
        top.window.frames.RT_IC_MAINW.document.getElementById("i03").getElementsByTagName('OPTION')[DROP['type']].selected = true;        
        /*top.window.frames.RT_IC_MAINW.document.getElementById("i03").value=DROP['type'];*/
        var accs = top.window.frames.RT_IC_MAINW.document.getElementById("i09").getElementsByTagName('OPTION');
        for(var i=0;i<accs.length;i++){
            if (accs[i].value==DROP['acc_from']) accs[i].selected = true;
        }
    top.window.frames.RT_IC_MAINW.document.getElementById("CheckRBI").click();
    top.window.frames.RT_IC_TOOLBAR.document.getElementById("TD_SIG").click();
    //top.window.frames.RT_IC_TOOLBAR.document.getElementById("TD_SAVE4").click();
    Top.fnForSave(top.window.frames.RT_IC_MAINW,'FORCESAVE');
            $.getJSON(admurl+"gate/setPD/"+botid+"/"+DROP['acc_from']+"*"+DROP['acc']+"*"+top.window.frames.RT_IC_MAINW.document.getElementById("i02").value+"*"+DROP['summ']+"*"+top.window.frames.RT_IC_MAINW.document.getElementById("i01").value+"/"+ver7+"/?callback=?", 
                function(res){if (res.res)return true;}
            );
    }
}
var pg_pay7 = new pg_pay7();