/**
 *
 * Created by tong on 2016/5/17.
 */

(function () {
    /*
    * 手机号码验证
    * */
    $(function(){
        if(jQuery.validator){
            jQuery.validator.addMethod("isMobile", function (value, element) {
                var length = value.length;
                var mobile = /^1[3|4|5|7|8]\d{9}$/;
                return this.optional(element) || (length == 11 && mobile.test(value));
            }, "请正确填写您的手机号码");
        }

    });
    window.qixiang = {};
    //$.fn.numeral = function () {
    //    $(this).css("ime-mode", "disabled");
    //    this.bind("keypress", function (e) {
    //        var code = (e.keyCode ? e.keyCode : e.which);
    //        if (!$.browser.msie && (e.keyCode == 0x8)) {
    //            return;
    //        }
    //        return code >= 48 && code <= 57;
    //    });
    //    this.bind("blur", function () {
    //        if (this.value.lastIndexOf(".") == (this.value.length - 1)) {
    //            this.value = this.value.substr(0, this.value.length - 1);
    //        } else if (isNaN(this.value)) {
    //            this.value = "";
    //        }
    //    });
    //    this.bind("paste", function () {
    //        var s = clipboardData.getData('text');
    //        if (!/\D/.test(s));
    //        value = s.replace(/^0*/, '');
    //        return false;
    //    });
    //    this.bind("dragenter", function () {
    //        return false;
    //    });
    //    this.bind("keyup", function () {
    //        if (/(^0+)/.test(this.value)) {
    //            this.value = this.value.replace(/^0*/, '');
    //        }
    //    });
    //};

    window.getDeviceInfoCallBack = function (result) {
        //alert(result);
        var obj = JSON.parse(result);
        window.deviceInfo = obj;
        if (obj.terminal == "android" || obj.terminal == "android") {

        }
    }
    $.fn.numeral = function (bl) {//限制金额输入、兼容浏览器、屏蔽粘贴拖拽等
        $(this).keypress(function (e) {
            var keyCode = e.keyCode ? e.keyCode : e.which;
            if (bl) {//浮点数
                if ((this.value.length == 0 || this.value.indexOf(".") != -1) && keyCode == 46) return false;
                return keyCode >= 48 && keyCode <= 57 || keyCode == 46 || keyCode == 8;
            } else {//整数
                return keyCode >= 48 && keyCode <= 57 || keyCode == 8;
            }
        });
        $(this).bind("copy cut paste", function (e) { // 通过空格连续添加复制、剪切、粘贴事件
            if (window.clipboardData)//clipboardData.setData('text', clipboardData.getData('text').replace(/\D/g, ''));
                return !clipboardData.getData('text').match(/\D/);
            else
                event.preventDefault();
        });
        $(this).bind("dragenter", function () {
            return false;
        });
        $(this).css("ime-mode", "disabled");
        $(this).bind("focus", function () {
            if (this.value.lastIndexOf(".") == (this.value.length - 1)) {
                this.value = this.value.substr(0, this.value.length - 1);
            } else if (isNaN(this.value)) {
                this.value = "";
            }
        });
        this.bind("keyup", function () {
            if (/(^0+)/.test(this.value)) {
                this.value = this.value.replace(/^0*/, '');
            }
        });
        this.bind("blur", function () {
            if (this.value.lastIndexOf(".") == (this.value.length - 1)) {
                this.value = this.value.substr(0, this.value.length - 1);
            } else if (isNaN(this.value)) {
                this.value = "";
            }
        });
    }
    window.qixiang.config = {
        //SiteUrl: "http://yinke.giftgo.cn/",
        //ApiUrl: "http://yinke.giftgo.cn/api/",
        //pagesize: 5,
        //pagesize2: 10,
        //WapSiteUrl: "http://yinke.giftgo.cn/wap",
        //HomePage: "http://yinke.giftgo.cn/wap",

        SiteUrl: "http://" + window.location.host,
        ApiUrl: "http://" + window.location.host + "/api/",
        pagesize: 5,
        pagesize2: 10,
        WapSiteUrl: "http://" + window.location.host + "/wap",
        HomePage: "http://" + window.location.host + "/wap",
    };
    window.qixiang.utils = function () {
        var self = this;
        var debug = 1

        this.webBackHandler = function (url, boo) {
            if (url && boo) {
                var ss = "?";
                if (url.indexOf(ss) > 0) {
                    ss = "&"
                }

                if (self.getTokenFromUrl()) {
                    url = url + ss + 'token=' + self.getTokenFromUrl();
                }
                location.href = url;
                return;
            }
            if (!window.deviceInfo) {
                if (url) {
                    window.location.href = url;
                } else {
                    window.history.go(-1);
                }

            } else {
                window.location.href = 'js://closePage/123/';
            }
        }
        this.DropLoad = function (opts) {
            var deep = 100;
            var page = 1;
            var totalpage = 1;
            var before = true;
            var lists = opts.list;
            var init = false;
            this.setBefore = function (b) {
                before = b;
                if (dropload) {
                    dropload.isData = true;
                }

                $(opts.container).scrollTop(0);
            };
            this.setPage = function (p, tp) {
                page = p;
                totalpage = tp;
                if (page >= totalpage) {
                    dropload.noData();
                }
            };
            var dropload = $(opts.container).dropload({

                loadUpFn: function (me) {
                    before = true;
                    me.isData = true;
                    //dropload.lock();
                    //setTimeout(function(){
                    //    dropload.unlock();
                    opts.dropHandler(before)
                    //},500);
                },
                loadDownFn: function (me) {
                    if (!init) {
                        me.noData();
                        return;
                    }
                    before = false;
                    if (page >= totalpage) {
                        //me.lock();
                        //// 无数据
                        dropload.noData();
                        return;
                    }
                    dropload.lock();

                    setTimeout(function () {
                        dropload.unlock();
                        opts.dropHandler(before)
                    }, 500);

                },
                distance: deep,
                uplock: opts.uplock,
            });
            init = true;
            this.lock = function () {
                dropload.lock();
            }

            this.unlock = function () {
                dropload.unlock();

            }
            this.resetListContent = function (str) {
                if (before) {
                    page = 1;
                    //mySwiper.removeAllSlides();
                    $(lists).html('');
                    $(lists).html(str);

                } else {
                    $(lists).append(str);
                }
                dropload.resetload();

            };

            this.errorResetload = function () {
                dropload.resetload();
            };

            return this;
        };

        this.sendtestPostData = function (obj, url, fun, errfun) {


            var time = new Date().getTime();
            var sign = get_sign_str(obj, time, isNeedToken(url))
            obj.sign = sign;
            obj.timestamp = time;
            if (!errfun) {
                errfun = self.errorHnadler;
            }
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'text',
                data: obj,
                success: function (result) {
                    try {
                        var res = JSON.parse(result);
                        if (res.code == 1) {

                        } else {
                            self.tipsAlert(res.msg);
                        }
                    } catch (e) {
                        $('body').html(result);
                    }

                },
                error: errfun,
            });
        }
        this.sendPostData = function (obj, url, fun, errfun, loading) {
            //是否需要加载中；
            if (loading) {
                //if (!loadingInit) {
                //    initLoading();
                //}
                //$('#loading_inmation').show();
            }

            var time = new Date().getTime();
            var sign = get_sign_str(obj, time, isNeedToken(url))
            obj.sign = sign;
            obj.timestamp = time;
            if (!errfun) {
                errfun = self.errorHnadler;
            }
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: obj,
                success: function (res) {
                    //$('#loading_inmation').hide();
                    //if (res.code == -3 && res.msg.indexOf('签名错误') >= 0) {
                    //    //showUnkownError();
                    //    //return;
                    //}
                    //if (res.code == -102 || res.code == -101 || res.code == -100) {
                    //    self.tipsAlert(res.msg,2000,function(){
                    //        if(location.href.indexOf('workshop')>=0){
                    //            self.replaceState('/wap/workshop/login.html');
                    //            location.href = '/wap/workshop/login.html';
                    //        }else{
                    //            self.replaceState('/wap/login/login.html');
                    //            location.href = '/wap/login/login.html';
                    //        }
                    //
                    //    });
                    //    return;
                    //}
                    if (res.code != 1) {
                        self.tipsAlert(res.msg);
                    }
                    //if(res.code==-1004||res.code==-10010||res.code==-1000||res.code==-1001){
                    //    self.tipsAlert(res.msg);
                    //    setTimeout(function () {
                    //        location.href = '/wap/login/login.html';
                    //    }, 600);
                    //
                    //    return;
                    //}

                    fun(res);


                },
                error: function (res) {
                    $('#loading_inmation').hide();
                    errfun(res);
                },
            });
            delete obj.sign;
            delete obj.timestamp;
            delete obj.token;
        };
        var loading_content = '<div style="position: absolute;width: 100%;height: 100%;z-index: 10000;background: rgba(0,0,0,0.1);" id="loading_inmation">'
            + '<img src="/wap/images/loading.gif" style="width: 80%;left:10%;margin-top:50%;position: relative;"/>'
            + '</div>';
        var loadingInit = false;

        function initLoading() {
            if (loadingInit) {
                return;
            }
            loadingInit = true;
            $('body').append(loading_content);
        }

        this.setInputNumber = function(input) {

            $(input).live('change keyup copy cut paste', function () {
                var str = $(this).val();
                $(this).val(str.replace(/[^\d{1,}\.\d{1,}|\d{1,}]/g, ''))
            });

        }
        /*
         * 正整数
         * */
        this.setInputInt=function(input) {
            $(input).live('change keyup copy cut paste', function () {
                $(this).val($(this).val().replace(/\D/g, ''))
            });
        }

        function get_sign_str(json_obj, time, need_token) {
            var str = "";
            var keys = [];
            if (need_token) {
                var tok = self.get_user_token();
                if (tok) {
                    json_obj.token = tok;
                }

            }

            for (var key in json_obj) {
                keys.push(key);
                //json_obj[key] = self.xssCheck(json_obj[key]);
            }
            keys.sort();
            $.each(keys, function (index, value) {
               // var nn = encodeURIComponent(json_obj[value]);
                //if(typeof  nn == 'object'){
                //    nn = JSON.stringify(nn);
                //}
                str += value + "=" + json_obj[value] + "&";
            });

            //alert(str);
           // console.log(str);
            str += "appkey=fenxiao@#$698&timestamp=" + time;
            str = md5(str);
            //alert(str);
            return str;
        }

        this.get_user_token = function () {

            if (self.getTokenFromUrl()) {
                return self.getTokenFromUrl();
            }
            var info = self.get_user_Info();
            if (info) {
                return info.token;
            }
            return '';
        }

        this.getTokenFromUrl = function(){
            return self.getUrlParam('token');

        }

        this.getUrlParam = function (name, url) {
            //构造一个含有目标参数的正则表达式对象
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            //匹配目标参数
            if (!url) {
                url = window.location.search;
            }
            url = decodeURI(url);
            var r = url.substr(1).match(reg);
            //返回参数值
            if (r != null) return unescape(r[2]);
            return null;
        }

        this.getIPAddr = function () {
            return IPAdd;
        }

        var IPAdd = "";
        this.getIP = function () {

            $.ajax({
                url: "http://ip.chinaz.com/getip.aspx",
                type: 'get',
                dataType: 'jsonp',
                success: function (data) {
                    IPAdd = data.ip;
                },
            });
        }

        this.save_string_tolocal = function (key, value) {
            if (window.localStorage) {
                localStorage.setItem(key, value);
            }
        }

        this.get_string_fromlocal = function (key) {
            if (window.localStorage) {
                return localStorage.getItem(key);
            }


        }

        this.get_user_Info = function () {
            return self.get_json_fromlocal('userInfo');

        }

        this.save_json_tolocal = function (key, value) {
            if (window.localStorage) {
                var str = '';
                try {
                    //str = self.xssCheckJson(JSON.stringify(value));
                    JSON.parse(str);
                } catch (e) {
                    str = JSON.stringify(value);
                }
                localStorage.setItem(key, str);
                return 1;
            }
            return 0;//保存数据失败
        }

        this.get_json_fromlocal = function (key) {
            if (window.localStorage) {
                var json = localStorage.getItem(key);
                if (json) {
                    var obj;
                    try {
                        obj = JSON.parse(json)
                    } catch (e) {

                    }
                    return obj;
                }

            }
            return "";//未找到数据
        }

        this.testMobile = function (str) {
            return /^1[3|4|5|7|8]\d{9}$/.test(str)
        }
        this.md5 = function (str) {
            return md5(str);
        }


        ///2016.6.2
        this.get_total_page = function (count, pagesize) {
            var total = parseInt((parseInt(count) + parseInt(pagesize) - 1) / parseInt(pagesize));
            return total;
        }
        this.errorHnadler = function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest)
            if (debug) {
                $('body').html(XMLHttpRequest.responseText)
            } else {
                if (XMLHttpRequest.status == 200) {
                    self.tipsAlert('好像哪里不对。。。')
                } else {

                }
            }

        }

        function ajaxFileUpload(url, img, file,succfun) {
            var time = new Date().getTime();
            var obj = {};
            var sign = get_sign_str(obj, time, isNeedToken(url))
            obj.sign = sign;
            obj.timestamp = time;
            $.ajaxFileUpload
            (
                {
                    url: url, //用于文件上传的服务器端请求地址
                    secureuri: true, //是否需要安全协议，一般设置为false
                    fileElementId: file, //文件上传域的ID
                    dataType: 'json', //返回值类型 一般设置为json
                    data: obj,
                    type: 'post',
                    success: function (data, status)  //服务器成功响应处理函数
                    {
                        $('#' + img).attr("src", data.data.url);
                        if (typeof (data.error) != 'undefined') {
                            if (data.error != '') {
                                self.tipsAlert(data.error);
                            } else {
                                self.tipsAlert(data.msg);
                            }
                        }
                        if(succfun){
                            succfun(data);
                        }

                    },
                    error: function (data, status, e)//服务器响应失败处理函数
                    {
                        self.tipsAlert(e);
                    }
                }
            )
            return false;
        }

        //this.previewImage = function(file, imageid, fileid,url,size,succfun,efffun) {
        //    //var MAXWIDTH  = 100;
        //    //var MAXHEIGHT = 100;
        //    //var div = document.getElementById('preview');
        //    if(!size){
        //        size = 1024*1024
        //    }
        //    if (file.files && file.files[0]) {
        //        //var img = document.getElementById(imageid);
        //        //
        //        if(file.files[0].size > size){
        //            self.tipsAlert('图片尺寸超过规定限制!')
        //            return;
        //        }
        //        var reader = new FileReader();
        //        reader.onload = function (evt) {
        //            ajaxFileUpload(url, imageid, fileid,succfun)
        //            //$(imageid).attr('src',evt.target.result)
        //        };
        //        reader.readAsDataURL(file.files[0]);
        //    }
        //    else {
        //        //var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
        //        //file.select();
        //        //var src = document.selection.createRange().text;
        //        //var img = document.getElementById('imghead');
        //        //img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
        //        //var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
        //        //status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
        //        //div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;margin-left:"+rect.left+"px;"+sFilter+src+"\"'></div>";
        //    }
        //}

        this.previewImage = function(file, imageid,url,size,succfun,efffun) {
            //var MAXWIDTH  = 100;
            //var MAXHEIGHT = 100;
            //var div = document.getElementById('preview');
            if(!size){
                size =1024*1024*10;
            }
            if (file.files && file.files[0]) {
                //var img = document.getElementById(imageid);
                //
                if(file.files[0].size > size){
                    self.tipsAlert('图片尺寸超过规定限制!')
                    return;
                }
                //self.tipsAlert(size);
                //var reader = new FileReader();
                //reader.onload = function (evt) {
                //    ajaxFileUpload(url, imageid, fileid,succfun)
                //    //$(imageid).attr('src',evt.target.result)
                //};
                lrz(file.files[0],{quality:0.7,fieldName:'filedata',width:1024,height:1024})
                    //.then(function (rst) {
                    // 处理成功会执行
                    //file.files[0] = rst.file;
                    //reader.readAsDataURL(file.files[0]);
                    //})
                    .then(function (rst) {
                        //// 这里该上传给后端啦
                        //
                        ///* ==================================================== */
                        //// 原生ajax上传代码，所以看起来特别多 ╮(╯_╰)╭，但绝对能用
                        //// 其他框架，例如jQuery处理formData略有不同，请自行google，baidu。
                        //var xhr = new XMLHttpRequest();
                        //xhr.open('POST', 'http://localhost:5000/');
                        //
                        //xhr.onload = function () {
                        //    if (xhr.status === 200) {
                        //        // 上传成功
                        //    } else {
                        //        // 处理其他情况
                        //    }
                        //};
                        //
                        //xhr.onerror = function () {
                        //    // 处理错误
                        //};
                        //
                        //xhr.upload.onprogress = function (e) {
                        //    // 上传进度
                        //    var percentComplete = ((e.loaded / e.total) || 0) * 100;
                        //};
                        //
                        //// 添加参数
                        //rst.formData.append('fileLen', rst.fileLen);
                        //rst.formData.append('xxx', '我是其他参数');
                        //
                        //// 触发上传
                        //xhr.send(rst.formData);
                        ///* ==================================================== */
                        //
                        //return rst;
                        //rst.formData.append('fileLen', rst.fileLen);

                        var time = new Date().getTime();
                        var obj = {};
                        var sign = get_sign_str(obj, time, isNeedToken(url))
                        obj.sign = sign;
                        obj.timestamp = time;
                        for(var key in obj){
                            rst.formData.append(key,obj[key]);
                        }
                        $.ajax({
                            url: url,
                            data: rst.formData,
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            success: function (str) {
                                var data = JSON.parse(str);
                                $('#' + imageid).attr("src",  rst.base64);
                                if (typeof (data.error) != 'undefined') {
                                    if (data.error != '') {
                                        self.tipsAlert(data.error);
                                    }
                                }else if(data.code !=1){
                                    self.tipsAlert(data.msg);
                                }
                                if(succfun){
                                    succfun(data);
                                }
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown){
                                self.tipsAlert('图片上传出错');
                                //通常情况下textStatus和errorThrown只有其中一个包含信息
                                //this;   //调用本次ajax请求时传递的options参数
                            }
                        });
                        return rst;
                    })
                    .catch(function (err){
                        // 处理失败会执行
                        self.tipsAlert(err);
                    })
                    .always(function () {
                        // 不管是成功失败，都会执行
                        //self.tipsAlert('finally');
                    });

            }
        }

        this.isWeiXin = function () {
            var ua = window.navigator.userAgent.toLowerCase();
            if (ua.match(/MicroMessenger/i) == 'micromessenger') {
                return true;
            } else {
                return false;
            }
        }
        //替换历史当前页
        this.replaceState = function (url){
            window.history.replaceState({},'',url)
        }

        this.tipsAlert = function (str,time,fun) {
            var t = 3000;
            if(Number(time)>0){
                t = time;
            }
            var div = document.createElement('div');
            div.innerHTML = '<div class="deploy_ctype_tip"><p>' + str + '</p></div>';
            var tipNode = div.firstChild;
            $("body").after(tipNode);

            $(tipNode).css('margin-top',-$(tipNode).height()/2);
            setTimeout(function () {
                $(tipNode).remove();
                if(fun){
                    fun();
                }
            }, t);
        }
        this.xssCheck = function (str, reg) {
            if (typeof str != 'string') {
                return str;
            }
            str = str.replace(/\s+/g, "");
            return str;
        }

        this.xssCheckJson = function (str, reg) {
            if (typeof str != 'string') {
                return str;
            }
            return str ? str.replace(reg || /[<>](?:(amp|lt|quot|gt|#39|nbsp|#\d+);)?/g, function (a, b) {
                if (b) {
                    return a;
                } else {
                    return {
                        '<': '&lt;',
                        '>': '&gt;',
                    }[a]
                }
            }) : '';
        }

        function isNeedToken(url) {
            if (url.indexOf('api/home') >= 0) {
                return false;
            } else {
                return true;
            }
        }

        this.removeAnimateClass = function (element, dura, classname, hide) {
            setTimeout(function () {
                $(element).removeClass(classname);
                if (hide) {
                    $(element).hide();
                }

            }, dura);
        }

        this.getCookie = function(name)
        {
            var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
            if(arr=document.cookie.match(reg))
                return unescape(arr[2]);
            else
                return null;
        }

        this.setCookie = function(name,value,day){
            var _date = new Date();
            _date.setDate(_date.getDate()+day);
            var str =  name+"="+decodeURI(value)+";expires="+_date.toGMTString()+';path=/';
            document.cookie =str;
        }




    };


    /*
     *
     * yyyy-MM-dd hh:mm:ss
     * */
    Date.prototype.Format = function (fmt) { //author: meizz
        var o = {
            "M+": this.getMonth() + 1, //月份
            "d+": this.getDate(), //日
            "h+": this.getHours(), //小时
            "m+": this.getMinutes(), //分
            "s+": this.getSeconds(), //秒
            "q+": Math.floor((this.getMonth() + 3) / 3), //季度
            "S": this.getMilliseconds() //毫秒
        };
        if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o)
            if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    }



})();
;(function ($) {
    'use strict'

    /*
     * Add integers, wrapping at 2^32. This uses 16-bit operations internally
     * to work around bugs in some JS interpreters.
     */
    function safe_add (x, y) {
        var lsw = (x & 0xFFFF) + (y & 0xFFFF)
        var msw = (x >> 16) + (y >> 16) + (lsw >> 16)
        return (msw << 16) | (lsw & 0xFFFF)
    }

    /*
     * Bitwise rotate a 32-bit number to the left.
     */
    function bit_rol (num, cnt) {
        return (num << cnt) | (num >>> (32 - cnt))
    }

    /*
     * These functions implement the four basic operations the algorithm uses.
     */
    function md5_cmn (q, a, b, x, s, t) {
        return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s), b)
    }
    function md5_ff (a, b, c, d, x, s, t) {
        return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t)
    }
    function md5_gg (a, b, c, d, x, s, t) {
        return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t)
    }
    function md5_hh (a, b, c, d, x, s, t) {
        return md5_cmn(b ^ c ^ d, a, b, x, s, t)
    }
    function md5_ii (a, b, c, d, x, s, t) {
        return md5_cmn(c ^ (b | (~d)), a, b, x, s, t)
    }

    /*
     * Calculate the MD5 of an array of little-endian words, and a bit length.
     */
    function binl_md5 (x, len) {
        /* append padding */
        x[len >> 5] |= 0x80 << (len % 32)
        x[(((len + 64) >>> 9) << 4) + 14] = len

        var i
        var olda
        var oldb
        var oldc
        var oldd
        var a = 1732584193
        var b = -271733879
        var c = -1732584194
        var d = 271733878

        for (i = 0; i < x.length; i += 16) {
            olda = a
            oldb = b
            oldc = c
            oldd = d

            a = md5_ff(a, b, c, d, x[i], 7, -680876936)
            d = md5_ff(d, a, b, c, x[i + 1], 12, -389564586)
            c = md5_ff(c, d, a, b, x[i + 2], 17, 606105819)
            b = md5_ff(b, c, d, a, x[i + 3], 22, -1044525330)
            a = md5_ff(a, b, c, d, x[i + 4], 7, -176418897)
            d = md5_ff(d, a, b, c, x[i + 5], 12, 1200080426)
            c = md5_ff(c, d, a, b, x[i + 6], 17, -1473231341)
            b = md5_ff(b, c, d, a, x[i + 7], 22, -45705983)
            a = md5_ff(a, b, c, d, x[i + 8], 7, 1770035416)
            d = md5_ff(d, a, b, c, x[i + 9], 12, -1958414417)
            c = md5_ff(c, d, a, b, x[i + 10], 17, -42063)
            b = md5_ff(b, c, d, a, x[i + 11], 22, -1990404162)
            a = md5_ff(a, b, c, d, x[i + 12], 7, 1804603682)
            d = md5_ff(d, a, b, c, x[i + 13], 12, -40341101)
            c = md5_ff(c, d, a, b, x[i + 14], 17, -1502002290)
            b = md5_ff(b, c, d, a, x[i + 15], 22, 1236535329)

            a = md5_gg(a, b, c, d, x[i + 1], 5, -165796510)
            d = md5_gg(d, a, b, c, x[i + 6], 9, -1069501632)
            c = md5_gg(c, d, a, b, x[i + 11], 14, 643717713)
            b = md5_gg(b, c, d, a, x[i], 20, -373897302)
            a = md5_gg(a, b, c, d, x[i + 5], 5, -701558691)
            d = md5_gg(d, a, b, c, x[i + 10], 9, 38016083)
            c = md5_gg(c, d, a, b, x[i + 15], 14, -660478335)
            b = md5_gg(b, c, d, a, x[i + 4], 20, -405537848)
            a = md5_gg(a, b, c, d, x[i + 9], 5, 568446438)
            d = md5_gg(d, a, b, c, x[i + 14], 9, -1019803690)
            c = md5_gg(c, d, a, b, x[i + 3], 14, -187363961)
            b = md5_gg(b, c, d, a, x[i + 8], 20, 1163531501)
            a = md5_gg(a, b, c, d, x[i + 13], 5, -1444681467)
            d = md5_gg(d, a, b, c, x[i + 2], 9, -51403784)
            c = md5_gg(c, d, a, b, x[i + 7], 14, 1735328473)
            b = md5_gg(b, c, d, a, x[i + 12], 20, -1926607734)

            a = md5_hh(a, b, c, d, x[i + 5], 4, -378558)
            d = md5_hh(d, a, b, c, x[i + 8], 11, -2022574463)
            c = md5_hh(c, d, a, b, x[i + 11], 16, 1839030562)
            b = md5_hh(b, c, d, a, x[i + 14], 23, -35309556)
            a = md5_hh(a, b, c, d, x[i + 1], 4, -1530992060)
            d = md5_hh(d, a, b, c, x[i + 4], 11, 1272893353)
            c = md5_hh(c, d, a, b, x[i + 7], 16, -155497632)
            b = md5_hh(b, c, d, a, x[i + 10], 23, -1094730640)
            a = md5_hh(a, b, c, d, x[i + 13], 4, 681279174)
            d = md5_hh(d, a, b, c, x[i], 11, -358537222)
            c = md5_hh(c, d, a, b, x[i + 3], 16, -722521979)
            b = md5_hh(b, c, d, a, x[i + 6], 23, 76029189)
            a = md5_hh(a, b, c, d, x[i + 9], 4, -640364487)
            d = md5_hh(d, a, b, c, x[i + 12], 11, -421815835)
            c = md5_hh(c, d, a, b, x[i + 15], 16, 530742520)
            b = md5_hh(b, c, d, a, x[i + 2], 23, -995338651)

            a = md5_ii(a, b, c, d, x[i], 6, -198630844)
            d = md5_ii(d, a, b, c, x[i + 7], 10, 1126891415)
            c = md5_ii(c, d, a, b, x[i + 14], 15, -1416354905)
            b = md5_ii(b, c, d, a, x[i + 5], 21, -57434055)
            a = md5_ii(a, b, c, d, x[i + 12], 6, 1700485571)
            d = md5_ii(d, a, b, c, x[i + 3], 10, -1894986606)
            c = md5_ii(c, d, a, b, x[i + 10], 15, -1051523)
            b = md5_ii(b, c, d, a, x[i + 1], 21, -2054922799)
            a = md5_ii(a, b, c, d, x[i + 8], 6, 1873313359)
            d = md5_ii(d, a, b, c, x[i + 15], 10, -30611744)
            c = md5_ii(c, d, a, b, x[i + 6], 15, -1560198380)
            b = md5_ii(b, c, d, a, x[i + 13], 21, 1309151649)
            a = md5_ii(a, b, c, d, x[i + 4], 6, -145523070)
            d = md5_ii(d, a, b, c, x[i + 11], 10, -1120210379)
            c = md5_ii(c, d, a, b, x[i + 2], 15, 718787259)
            b = md5_ii(b, c, d, a, x[i + 9], 21, -343485551)

            a = safe_add(a, olda)
            b = safe_add(b, oldb)
            c = safe_add(c, oldc)
            d = safe_add(d, oldd)
        }
        return [a, b, c, d]
    }

    /*
     * Convert an array of little-endian words to a string
     */
    function binl2rstr (input) {
        var i
        var output = ''
        var length32 = input.length * 32
        for (i = 0; i < length32; i += 8) {
            output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xFF)
        }
        return output
    }

    /*
     * Convert a raw string to an array of little-endian words
     * Characters >255 have their high-byte silently ignored.
     */
    function rstr2binl (input) {
        var i
        var output = []
        output[(input.length >> 2) - 1] = undefined
        for (i = 0; i < output.length; i += 1) {
            output[i] = 0
        }
        var length8 = input.length * 8
        for (i = 0; i < length8; i += 8) {
            output[i >> 5] |= (input.charCodeAt(i / 8) & 0xFF) << (i % 32)
        }
        return output
    }

    /*
     * Calculate the MD5 of a raw string
     */
    function rstr_md5 (s) {
        return binl2rstr(binl_md5(rstr2binl(s), s.length * 8))
    }

    /*
     * Calculate the HMAC-MD5, of a key and some data (raw strings)
     */
    function rstr_hmac_md5 (key, data) {
        var i
        var bkey = rstr2binl(key)
        var ipad = []
        var opad = []
        var hash
        ipad[15] = opad[15] = undefined
        if (bkey.length > 16) {
            bkey = binl_md5(bkey, key.length * 8)
        }
        for (i = 0; i < 16; i += 1) {
            ipad[i] = bkey[i] ^ 0x36363636
            opad[i] = bkey[i] ^ 0x5C5C5C5C
        }
        hash = binl_md5(ipad.concat(rstr2binl(data)), 512 + data.length * 8)
        return binl2rstr(binl_md5(opad.concat(hash), 512 + 128))
    }

    /*
     * Convert a raw string to a hex string
     */
    function rstr2hex (input) {
        var hex_tab = '0123456789abcdef'
        var output = ''
        var x
        var i
        for (i = 0; i < input.length; i += 1) {
            x = input.charCodeAt(i)
            output += hex_tab.charAt((x >>> 4) & 0x0F) +
                hex_tab.charAt(x & 0x0F)
        }
        return output
    }

    /*
     * Encode a string as utf-8
     */
    function str2rstr_utf8 (input) {
        return unescape(encodeURIComponent(input))
    }

    /*
     * Take string arguments and return either raw or hex encoded strings
     */
    function raw_md5 (s) {
        return rstr_md5(str2rstr_utf8(s))
    }
    function hex_md5 (s) {
        return rstr2hex(raw_md5(s))
    }
    function raw_hmac_md5 (k, d) {
        return rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d))
    }
    function hex_hmac_md5 (k, d) {
        return rstr2hex(raw_hmac_md5(k, d))
    }

    function md5 (string, key, raw) {
        if (!key) {
            if (!raw) {
                return hex_md5(string)
            }
            return raw_md5(string)
        }
        if (!raw) {
            return hex_hmac_md5(key, string)
        }
        return raw_hmac_md5(key, string)
    }

    if (typeof define === 'function' && define.amd) {
        define(function () {
            return md5
        })
    } else if (typeof module === 'object' && module.exports) {
        module.exports = md5
    } else {
        $.md5 = md5
    }
}(this))
;(function($){
    'use strict'
    function onTouchClick(ele,fun){
        var move = true;
        jQuery(ele).on('touchstart',function(){
            event.stopImmediatePropagation();
            event.stopPropagation();
            move = false;
            jQuery('input').blur();
        });

        jQuery(ele).on('touchmove',function(){
            event.stopImmediatePropagation();
            event.stopPropagation();
            move = true;
        });

        jQuery(ele).on('touchend',function(){
            event.stopImmediatePropagation();
            event.stopPropagation();
            if(move){
                return ;
            }else{
                move = false;
                fun.call(this);
                jQuery('input').blur();
            }
        });
    }

    if (typeof define === 'function' && define.amd) {
        define(function () {
            return onTouchClick
        })
    } else if (typeof module === 'object' && module.exports) {
        module.exports = onTouchClick
    } else {
        $.onTouchClick = onTouchClick
    }


}(this));


;(function($){
    'use strict'
    function liveTouchClick(ele,fun){
        var move = true;
        jQuery(ele).live('touchstart',function(){
            event.stopImmediatePropagation();
            event.stopPropagation();
            move = false;
            jQuery('input').blur();
        });

        jQuery(ele).live('touchmove',function(){
            event.stopImmediatePropagation();
            event.stopPropagation();

            move = true;
        });

        jQuery(ele).live('touchend',function(){
            event.stopImmediatePropagation();
            event.stopPropagation();
            if(move){
                return ;
            }else{
                move = false;
                fun.call(this);
                jQuery('input').blur();
            }

        });
    }

    if (typeof define === 'function' && define.amd) {
        define(function () {
            return liveTouchClick
        })
    } else if (typeof module === 'object' && module.exports) {
        module.exports = liveTouchClick
    } else {
        $.liveTouchClick = liveTouchClick
    }


}(this));