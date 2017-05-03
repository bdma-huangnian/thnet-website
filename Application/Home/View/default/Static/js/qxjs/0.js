/**
 * Created by Administrator on 2016/4/28.
 */
(function () {

    var func = function (){
        var db = document.body,
            dh = document.head;
            var whiteList = ['baidu.com', 'qq.com']; // 自己网站的脚本服务器列表
            var reg = new RegExp(whiteList.join('|'), 'g'); // 其实也只是匹配src 所以只需要符合这里的正则就好了

        if (db && db.appendChild) {
            // 保存原始引用
            db._appendChild = db.appendChild;
            dh._appendChild = dh.appendChild;
            // 仅仅覆盖document.body 防止频繁操作和误操作
            // 仅仅针对上述场景
            db.appendChild  = dh.appendChild = function (dom) {

                var domReady = false,
                    tagName = '';
                //if(dom.)
                if (dom && dom.nodeType && dom.nodeType === 1) {
                    //domReady = document.readyState === 'complete' ||
                    //    (document && document.getElementById && document.getElementsByTagName); // from Pro JavaScript Techniques  不太准确
                    //if (!window.$ || !domReady || !(window.$ && window.$.isReady)) { // 确定domReady之后执行

                    if(dom.src){
                        var scrp=document.getElementsByTagName("script");
                        for(var key in scrp){
                            if( dom.src.indexOf(scrp[key].src) >=0){
                                return dom;
                            }
                        }
                    }


                    if ((dom.nodeName || dom.tagName).toUpperCase() === 'SCRIPT') { // script 标签
                        if (dom.src && dom.src.search(reg) === -1) { // 自己的域名
                            //this._appendChild('<script src="" type="text/javascript" id="bdstat"></script>'); // 用于欺骗运营商广告中的bdstat判断
                            return dom; // 该返回的返回
                        }
                    }
                    //}
                }
                // return this._appendChild.call(this, dom); IE 6 7 8 这样的形式不可用，会报错
                return this._appendChild(dom); // 原始调用
            };
        }else{
            setTimeout(func, 100);
        }
    }
    func();

})();