/**
 *
 * Created by Administrator on 2016/11/8.
 */
var host = 'http://madmin.qixiangnet.com/index.php';

var houtaicount = "0";
var onoff = false;
var toubu = "";
var type_id = "";  //类型id
var platform_id = "";  //平台id
var model_category_id = "";//分类id
var model_id = "";//模块id
var function_id = "";//功能id
var funcData;
var platform_list;   //类表id
var money_low_sum;  //起始价格
var money_hgih_sum;   //最高加
var dev_date_low_sum;   //最低开发时长
var dev_date_high_sum;  //最高时长
$(function () {
    //**步骤一
    $('input, textarea').placeholder({customClass: 'my-placeholder'});
    $(".nav li").removeClass('on');
    $(".nav li:eq(3)").addClass('on');
    jQuery.support.cors = true;
    $.ajax({
        url: host + "/AuotedPrice/getTypeList?ts=" + new Date().getTime(),
        type: "post",
        dataType: "text",
        contentType: "application/x-www-form-urlencoded",
        success: function (ret) {
            var ypeList = JSON.parse(ret);
            // console.log(JSON.stringify(ypeList));
            var htmlll = "";
            for (var i = 0; i < ypeList.length; i++) {
                var apnum = i + 1;
                if (i == 0) {
                    htmlll += " <li rel='" + ypeList[i].id + "' info='5' class='active'><i class='appraisal01-0" + apnum + "-icon'></i>";
                }
                if (i == 1) {
                    htmlll += " <li rel='" + ypeList[i].id + "' info='2'><i class='appraisal01-0" + apnum + "-icon'></i>";
                }
                if (i == 2) {
                    htmlll += " <li rel='" + ypeList[i].id + "' info='3'><i class='appraisal01-0" + apnum + "-icon'></i>";
                }
                if (i == 3) {
                    htmlll += " <li rel='" + ypeList[i].id + "' info='3'><i class='appraisal01-0" + apnum + "-icon'></i>";
                }
                if (i == 4) {
                    htmlll += " <li rel='" + ypeList[i].id + "' info='10'><i class='appraisal01-0" + apnum + "-icon'></i>";
                }
                if (i == 5) {
                    htmlll += " <li rel='" + ypeList[i].id + "' class='checked'><i class='appraisal01-0" + apnum + "-icon'></i>";
                }
                htmlll += " <p>" + ypeList[i].name + "</p>";
                htmlll += " <span class='appraisal01-item-check'></span><span class='appraisal01-item-bg'></span></li>";
            }
            $("#ulbox").html(htmlll);
            //选择项目类型
            $(".appraisal01-main li").on("click", function () {
                if (!$(this).hasClass("checked")) {
                    $(this).toggleClass("active");
                }
            });
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest)
            console.log(errorThrown);

        }
    });

    //步骤一  下一步
    $("#btnAppraisal01").on("click", function () {
        var quote_type = "";
        var html = "";
        var flag = 0;
        $(".quote_types li").each(function (index) {
            if ($(this).attr("class") == "active") {
                flag++;
                var num = $(this).attr('rel');
                quote_type += num + ",";         //组合id
                var name = $(this).find('p').text();
                var count = $(this).attr("info");
                // alert(flag);
                if (count == 0) {
                    if (flag == 1) {
                        html += " <li id='qh" + flag + "' rel=" + num + " class='tablist active' info=" + flag + "><i>" + name + "</i> <span>" + count + "</span></li>";
                    } else {
                        html += " <li id='qh" + flag + "' rel=" + num + " class='tablist' info=" + flag + "><i>" + name + "</i> <span>" + count + "</span></li>";
                    }

                } else {
                    if (flag == 1) {
                        html += " <li id='qh" + flag + "' rel=" + num + " class='tablist active' info=" + flag + "><i>" + name + "</i> <span>" + count + "</span></li>";
                    } else {
                        html += " <li id='qh" + flag + "' rel=" + num + " class='tablist' info=" + flag + "><i>" + name + "</i> <span>" + count + "</span></li>";
                    }
                }
            }
        });
        //alert(quote_type);
        if (quote_type == "") {
            layer.alert("请选择评估项目");
            return false;
        }

        // quote_type = quote_type + "6,";
        //quote_type = quote_type;
        //添加头部
        var bacnum = flag + 1;
        if (houtaicount == 0) {
            html += "<li id='qh" + bacnum + "' class='bacnum' rel=" + bacnum + " info='" + bacnum + "'><i>后台管理</i> <span></span></li>";
        } else {
            html += "<li id='qh" + bacnum + "' class='bacnum' rel=" + bacnum + " info='" + bacnum + "'><i>后台管理</i> <span>" + houtaicount + "</span></li>";
        }
        $("#appraisal02Nav").html(html);


        //显示第一个
        if (onoff == true) {
            var tbarr = toubu.split(",");
            tbarr.pop(tbarr);
            var qtarr = quote_type.split(",");
            qtarr.pop(qtarr);
            var ret = difference(tbarr, qtarr);
            //console.log(ret);
            if (ret == "") {

                $(".appraisal-main").eq(1).show().siblings(".appraisal-main").hide()

                $(".appraisal-process li").addClass("active").eq(2).removeClass("active");
                $("#appraisal02Nav li").removeClass("active").eq(0).addClass("active");
                var navNum = $("#appraisal02Nav li:first-child").attr("info");
                $("#appraisal02Wrap>.appraisal02-check").removeClass("active");
                $("#lists" + navNum).addClass("active");
                initFunNum();
                showajax(quote_type);  //引用最新选择
            } else {
                // showajax(ret);
                showajax(quote_type);
                //alert(ret+"ret");
                type_id = ret.substring(0, ret.length - 1);
            }

        } else {
            showajax(quote_type);
            type_id = quote_type.substring(0, quote_type.length - 1);
        }


        /*  $(".appraisal-main").eq(1).show().siblings(".appraisal-main").hide()
         $(".appraisal-process li").addClass("active").eq(2).removeClass("active")*/


    });

    //**步骤二
    //功能点鼠标移入描述
    $(document).on("mouseenter", ".appraisal02-table03 label", function () {
        var models = $(this).parents('tr').find(".appraisal02-table02").find("label").text();
        var actions = $(this).text();
        var ico = $(this).find('.ipts').val();
        var descss = $(this).find('.descabout').val();
        $(this).parents('.appraisal02-check').find('.titles_fr').text(models + " > " + actions);
        $(this).parents('.appraisal02-check').find('.descripts_action').text(descss);
        $(this).parents('.appraisal02-check').find('.descript_imgs').attr("src", ico);

        $(this).closest(".appraisal02-table").next(".appraisal02-check-describe").css({
            top: $(this).offset().top - $(this).closest(".appraisal02-table").offset().top - 20
        })
    });

    //步骤二平台切换
    /*$("#appraisal02Nav li").on("click", function () {
     alert(1)
     $(this).addClass("active").siblings("li").removeClass("active");
     $("#appraisal02Wrap>.appraisal02-check").removeClass("active").eq($(this).index()).addClass("active")
     })*/

    $(document).on("click", "#appraisal02Nav li", function () {
        $(this).addClass("active").siblings("li").removeClass("active");
        $("#appraisal02Wrap>.appraisal02-check").removeClass("active");
        $("#lists" + $(this).attr("info")).addClass("active");
    })


    //一键清空
    $(document).on("click", ".check-clear", function () {
        $(this).closest("table").find("tr").removeClass("checked").find("input").prop("checked", false).end().find(".default ").find("input[type='radio']").prop("checked", true);
        //计算功能数量
        funNum($(this));
    });

    //还原默认
    $(document).on("click", ".check-default", function () {
        $(this).closest("table").find("tr").removeClass("checked").find("input").prop("checked", false).end().find(".default ").find("input").prop("checked", true);
        //计算功能数量
        funNum($(this));
    });

    //选择模块
    $(document).on("change", ".appraisal02-table02 input", function () {
        if (!$(this).prop("checked")) {
            $(this).closest("tr").removeClass("checked").find(".appraisal02-table03 input").prop("checked", false);
        } else {
            $(this).closest("tr").addClass("checked").find(".appraisal02-table03 input").prop("checked", true);
        }
        //计算功能数量
        funNum($(this));
    });
    //选择功能点
    $(document).on("change", ".appraisal02-table03 input", function () {
        $(this).closest("tr").addClass("checked").find(".appraisal02-table02 input[type='checkbox']").prop("checked", true);
        $(this).parents("td").find("input[type='checkbox']").each(function () {
            if (!$(this).prop("checked")) {
                $(this).closest("tr").removeClass("checked").find(".appraisal02-table02 input[type='checkbox']").prop("checked", false);
                return false
            }
        });
        //计算功能数量
        funNum($(this));
    });


    //**步骤三
    //步骤3切换
    $(document).on("click", "#appraisal03Nav li", function () {
        $(this).addClass("active").siblings("li").removeClass("active");
        $("#appraisal03Wrap>.appraisal02-check").removeClass("active").eq($(this).index()).addClass("active")
    });


    //打开关闭功能清单
    $(document).on("click", "#functionListBtn", function () {
        $(this).toggleClass("active");
        $(".appraisal03-content").toggleClass("active")
    });


    //步骤跳转
    $("#btnAppraisal00").on("click", function () {
        toubu = "";
        $("#appraisal02Nav .tablist").each(function (index) {
            var num = $(this).attr("rel");
            toubu += num + ",";
        });
        //alert(toubu);
        onoff = true;
        $(".appraisal-main").eq(0).show().siblings(".appraisal-main").hide();
        $(".appraisal-process li").removeClass("active").eq(0).addClass("active");
        initFunNum();
    });

    function getParentId(funid) {
        for (var i = 0; i < funcData.length; i++) {
            var cate = funcData[i].category;
            if (cate) {
                for (var j = 0; j < cate.length; j++) {
                    var models = cate[j].model;
                    for (var s = 0; s < models.length; s++) {
                        var funct = models[s]['function'];
                        for (var z = 0; z < funct.length; z++) {
                            if (funct[z]['id'] == funid) {
                                return {pid: funcData[i].id, cid: cate[j].id, mid: models[s].id}
                            }
                        }
                    }
                }
            }
        }
        return {cid: 0, mid: 0, pid: 0}
    }


    $("#btnAppraisal02").on("click", function () {
        //alert(type_id);


        //分类id
        var m_arr = [];
        var c_arr = [];
        var f_arr = [];
        var p_arr = [];
        $('input[type=checkbox]:checked,input[type=radio]:checked').each(function () {
            var fid = $(this).val();
            if (!Number(fid)) {
                return true;
            }
            f_arr.push(fid);
            var pbj = getParentId(fid);
            if (pbj.cid) {
                if (c_arr.indexOf(pbj.cid) < 0) {
                    c_arr.push(pbj.cid);
                }

            }
            if (pbj.mid) {
                if (m_arr.indexOf(pbj.mid) < 0) {
                    m_arr.push(pbj.mid);
                }
            }
            if (pbj.pid) {
                if (p_arr.indexOf(pbj.pid) < 0) {
                    p_arr.push(pbj.pid);
                }
            }
        });

        model_category_id = c_arr.join(',');
        model_id = m_arr.join(',');
        function_id = f_arr.join(',');
        platform_id = p_arr.join(',');
        nextAjax({
            models: {
                type_id: type_id,
                platform_id: platform_id,
                model_category_id: model_category_id,
                model_id: model_id,
                function: function_id
            }
        });
//            nextAjax(ids, apply, shiji);

        /*  $(".appraisal-main").eq(2).show().siblings(".appraisal-main").hide()
         $(".appraisal-process li").addClass("active")*/
    });


    //弹框
    $(document).on("click", ".btn-appraisal-save", function () {
        $(".appraisal-alert").addClass("active")
    });
    $(document).on("click", ".appraisal-alert>i", function () {
        $(".appraisal-alert").removeClass("active")
    });
    //弹框输入框
    $(document).on("focus", "input,textarea", function () {
        $(this).next(".placeholder").hide()
    });
    $(document).on("blur", "input,textarea", function () {
        if ($(this).val() == "") {
            $(this).next(".placeholder").show()
        }

    })
});

//默认active
function itemChecked01() {
    var navNum = $("#appraisal02Nav li:first-child").attr("info");
    $("#appraisal02Wrap>.appraisal02-check").removeClass("active");
    $("#lists" + navNum).addClass("active");
}
//计算功能数量
function funNum(obj) {
    //var _this = obj;
    var i = 0;
    obj.closest("table").find(".appraisal02-table03 input[type='checkbox'],.appraisal02-table03 input[type='radio']").each(function () {
        if ($(this).prop("checked")) {
            i++;
        }
    });
    $("#qh" + obj.closest(".appraisal02-check").attr("info")).find("span").text(i);
}
//初始化功能数量
function initFunNum() {
    $("#appraisal02Wrap .appraisal02-check table").each(function () {
        //计算功能数量
        funNum($(this));
    })
}
initFunNum();

//初始化判断默认


//步骤二平台切换
/*  function actives(obj){
 $(obj).addClass("active").siblings("li").removeClass("active");
 $("#appraisal02Wrap>.appraisal02-check").removeClass("active").eq($(obj).index()).addClass("active")
 }*/

//两个函数去重
function difference(tbarr, qtarr) {
    var diff = [];
    var str = "";
    var bt = "";
    tbarr.forEach(function (val1, i) {
        if (qtarr.indexOf(val1) < 0) {
            diff.push(val1);
        } else {
            qtarr.splice(qtarr.indexOf(val1), 1);
        }
    });
    var dt = diff.concat(qtarr);
    for (var i = 0; i < dt.length; i++) {
        if (tbarr.indexOf(dt[i]) < 0) {
            str += dt[i] + ",";
        } else {
            bt += dt[i] + ",";
        }
    }

    if (str != "") {
        if (str == dt) {
            str = "";
        }
    }

    var arrbt = bt.split(",");
    arrbt.pop(arrbt);
    // console.log(arrbt);

    for (var s = 0; s < arrbt.length; s++) {
        $("#lists" + arrbt[s]).remove();
    }


    return str;

}

defaultFun();

//默认选中状态
function defaultFun() {
    $(".appraisal02-table03").each(function () {
        $(this).find("input[type='checkbox']").each(function () {
            $(this).closest("tr").addClass("checked").find(".appraisal02-table02 input").prop("checked", true);
            if (!$(this).prop("checked")) {
                $(this).closest("tr").removeClass("checked").find(".appraisal02-table02 input").prop("checked", false);
                return false
            }
        })

    })
}


//切换显示
function showajax(fid) {
    var index = layer.load();
    //fid="1,2,3";
    fid = fid.substring(0, fid.length - 1);
    var par = $.param({"type_id": fid});
    var obj = {"type_id": fid};
    $.ajax({
        url: host + "/AuotedPrice/getPlatformFunctionList?" + par,
        type: "post",
        data: obj,
        dataType: "json",
        success: function (ret) {
            layer.close(index);
            if (ret.status != 1) {
                layer.alert(ret.message);
                return false;
            }
            var data = ret.data;
            funcData = ret.data;

            var html = "";
            var arr = fid.split(",");
            for (var i = 0; i < data.length; i++) {
                var gfg = data.length - 1;
                $(".bacnum").attr('rel', data[gfg]['id']);
                var bjid = i + 1;
                if (i == 0) {
                    html += '<div class="appraisal02-check active" rel="' + data[i]['id'] + '" id="lists' + bjid + '" info="' + bjid + '"><div class="appraisal02-table"><table width="100%">' +
                        '<thead><tr><th>分类</th><th>模块</th><th>功能点</th></tr></thead><tbody>';
                } else {
                    html += '<div class="appraisal02-check" rel="' + data[i]['id'] + '" id="lists' + bjid + '" info="' + bjid + '"><div class="appraisal02-table"><table width="100%">' +
                        '<thead><tr><th>分类</th><th>模块</th><th>功能点</th></tr></thead><tbody>';
                }
                if (data[i].category != "") {
                    var cate = data[i].category;
                    for (var j = 0; j < cate.length; j++) {
                        if (cate[j].model != "") {
                            var models = cate[j].model;
                            for (var s = 0; s < models.length; s++) {
                                html += '<tr>';
                                if (s == 0) {
                                    if (models.length > 1) {
                                        html += '<td class="appraisal02-table01" rowspan="' + models.length + '" cid="' + cate[j]['id'] + '">' + cate[j]['name'] + '</td>';
                                    } else {
                                        html += '<td class="appraisal02-table01" cid="' + cate[j]['id'] + '">' + cate[j]['name'] + '</td>';
                                    }
                                }
                                if (models[s].is_checkbox == 1) {
                                    html += '<td class="appraisal02-table02" mid="' + models[s]['id'] + '"><label><i class="input-checkbox"><input type="checkbox" ><span></span></i>' + models[s]['name'] + '</label></td><td class="appraisal02-table03">';
                                } else {
                                    html += '<td class="appraisal02-table02" mid="' + models[s]['id'] + '"><label>' + models[s]['name'] + '</label></td><td class="appraisal02-table03">';
                                }

                                var funct = models[s]['function'];
                                for (var z = 0; z < funct.length; z++) {
                                    if (funct[z]['is_default'] == 1) {
                                        if (funct[z]['is_checkbox'] == 1) {
                                            html += '<label class="default"><i class="input-checkbox" info="1"><input type="checkbox" name="names[]" value="' + funct[z]['id'] + '" checked>';
                                        } else {
                                            if (funct[z]['is_sheji'] == 1) {
                                                html += '<label class="default"><i class="input-checkbox" info="2"><input type="radio" name="sheji' + data[i]['id'] + '" value="' + funct[z]['id'] + '" checked>';
                                            } else {
                                                html += '<label class="default" info="' + models[s]['id'] + '"><i class="input-checkbox" info="' + models[s]['id'] + '"><input type="radio" name="name' + models[s]['id'] + '" value="' + funct[z]['id'] + '" checked>';
                                            }

                                        }

                                    } else {
                                        if (funct[z]['is_checkbox'] == 1) {
                                            html += '<label><i class="input-checkbox" info="1"><input type="checkbox" name="names[]" value="' + funct[z]['id'] + '">';
                                        } else {
                                            if (funct[z]['is_sheji'] == 1) {
                                                html += '<label><i class="input-checkbox" info="2"><input type="radio" name="sheji' + data[i]['id'] + '" value="' + funct[z]['id'] + '">';
                                            } else {
                                                html += '<label><i class="input-checkbox" info="' + models[s]['id'] + '"><input type="radio" name="name' + models[s]['id'] + '" value="' + funct[z]['id'] + '">';
                                            }
                                        }

                                    }
                                    html += '<span></span></i><span>' + funct[z]['name'] + '</span><input type="hidden" class="ipts" value=' + 'images/icons/' + funct[z]['icon'] + '><input type="hidden" class="descabout" value=' + funct[z]['description'] + '></label>';
                                }
                                html += '</td></tr>';
                            }
                        } else {
                            html += '<tr><td class="appraisal02-table01" cid="' + cate[j]['id'] + '">' + cate[j]['name'] + '</td><td class="appraisal02-table02"></td><td class="appraisal02-table03"><label></label></td></tr>';
                        }
                    }


                }


                html += '</tbody><tfoot><tr><td colspan="3" align="right"> <a href="javascript:void(0)" class="btn-appraisal-bd-s check-clear">一键清空</a>' +
                    '<a href="javascript:void(0)" class="btn-appraisal-bd-s check-default">还原默认</a></td> </tr></tfoot></table></div>' +
                    '<div class="appraisal02-check-describe"><i></i><h5 class="titles_fr">功能点描述</h5><div class="appraisal02-check-describe-main">' +
                    '<p class="descripts_action">鼠标移动到对应的功能点上，此处会显示功能点说明</p><i><img class="descript_imgs" src="i/appraisa-right-img01.png"></i> </div></div></div>';


            }

            $('#appraisal02Wrap').html("");
            $('#appraisal02Wrap').append(html);
            itemChecked01();
            $(".appraisal-main").eq(1).show().siblings(".appraisal-main").hide();
            $(".appraisal-process li").addClass("active").eq(2).removeClass("active");
            defaultFun();
            initFunNum();
        }

    })
}


//第二步下一步 ajax 请求

function nextAjax(obj) {
    var index = layer.load();
    //console.log(JSON.stringify(obj));
    var par = $.param(obj);
//        var par = JSON.stringify(obj);
//        console.log(par);
    $.ajax({
        url: host + '/AuotedPrice/getModelPrice?' + par,
        type: "post",
        data: obj,
        dataType: "json",
        success: function (ret) {
            //console.log(JSON.stringify(ret));
            layer.close(index);
            if (ret.status != 1) {
                layer.alert(ret.message);
                return false;
            }

            var data = ret.data;
            //console.log(JSON.stringify(data));
            $("#btn-appraisal104").attr("info", data['number']);
            money_low_sum = data['money_low_sum'];
            money_hgih_sum = data['money_hgih_sum'];
            dev_date_low_sum = data['dev_date_low_sum'];
            dev_date_high_sum = data['dev_date_high_sum'];
            //头部
            //var htmla = '<div class="appraisal03-money"><p>同好极速报价<span>：</span></p><span>¥' + data['money_low_sum'] + '</span> <span>—</span> <span>¥' + data['money_hgih_sum'] + '</span> <i>元</i>' +
            //    '</div><p>共有' + data['platform_count'] + ' 个平台，' + data['model_count'] + ' 个功能模块<a href="javascript:void(0)" class="btn-appraisal-bd-s btn-function-list" id="functionListBtn">' +
            //    '<span>查看功能清单</span><i>关闭功能清单</i></a> <a href="javascript:void(0)" class="btn-appraisal-bd-s ml20" id="appraisalReturnMoney">调整需求重新计算</a>  </p> <p>预计开发周期：' + data['dev_date_low_sum'] + ' - ' + data['dev_date_high_sum'] + ' 个工作日/人</p>';
            var htmla = '<div class="appraisal03-money"><p>同好极速报价<span>：</span></p><span id="last_show_price"><font size="6">免费发送报价到手机。</font></span>' +
                '</div><p>共有' + data['platform_count'] + ' 个平台，' + data['model_count'] + ' 个功能模块<a href="javascript:void(0)" class="btn-appraisal-bd-s btn-function-list" id="functionListBtn">' +
                '<span>查看功能清单</span><i>关闭功能清单</i></a> <a href="javascript:void(0)" class="btn-appraisal-bd-s ml20" id="appraisalReturnMoney">调整需求重新计算</a>  </p> <p>预计开发周期：' + data['dev_date_low_sum'] + ' - ' + data['dev_date_high_sum'] + ' 个工作日/人</p>';
            $("#descripss").html(htmla);
            //功能清单部分
            var htmlb = "";
            var quotelist = data.platform_list;
            for (var i = 0; i < quotelist.length; i++) {
                if (i == 0) {
                    htmlb += '<li class="active" pid="' + quotelist[i]['id'] + '"> <i>' + quotelist[i]['name'] + '</i></li>';
                } else {
                    htmlb += '<li pid="' + quotelist[i]['id'] + '"> <i>' + quotelist[i]['name'] + '</i></li>';
                }
            }

            $("#lists").html(htmlb);


            //放入数据 分类功能
            var htmlc = "";
            var yylist = data.platform_list;
            platform_list = data.platform_list;
            for (var i = 0; i < yylist.length; i++) {
                if (i == 0) {
                    htmlc += '<div class="appraisal02-check active"><div class="appraisal02-table"><table width="100%">' +
                        '<thead><tr> <th>分类</th><th>模块</th> <th>功能点</th></tr></thead><tbody>';
                } else {
                    htmlc += '<div class="appraisal02-check"><div class="appraisal02-table"><table width="100%">' +
                        '<thead><tr> <th>分类</th><th>模块</th> <th>功能点</th></tr></thead><tbody>';
                }

                if (yylist[i] != "") {
                    var cate = yylist[i].category;
                    for (var j = 0; j < cate.length; j++) {
                        if (cate[j].model != "") {
                            var models = cate[j].model;
                            for (var s = 0; s < models.length; s++) {
                                htmlc += '<tr>';
                                if (s == 0) {
                                    if (models.length > 1) {
                                        htmlc += '<td class="appraisal02-table01" rowspan="' + models.length + '">' + cate[j]['name'] + '</td>';
                                    } else {
                                        htmlc += '<td class="appraisal02-table01">' + cate[j]['name'] + '</td>';
                                    }
                                }
                                htmlc += '<td class="appraisal02-table02">' + models[s]['name'] + '</td><td class="appraisal02-table03">';
                                var funct = models[s]['function'];
                                for (var z = 0; z < funct.length; z++) {
                                    htmlc += '<label>' + funct[z]['name'] + '</label>';
                                }
                                htmlc += '</td></tr>';
                            }
                        } else {
                            htmlc += '<tr><td class="appraisal02-table01">' + cate[j]['name'] + '</td><td class="appraisal02-table02"></td><td class="appraisal02-table03"><label></label></td></tr>';
                        }
                    }
                }
                htmlc += "</tbody></table></div></div>";
            }
            // console.log(htmlc);
            itemChecked01();
            $("#appraisal03Wrap").html(htmlc);

            $(".appraisal-main").eq(2).show().siblings(".appraisal-main").hide();
            $(".appraisal-process li").addClass("active");

        }
    });


}

$(function () {
    $(document).on("click", "#appraisalReturnMoney", function () {
        $(".appraisal-main").hide().eq(1).show();
        $("#appraisal02Nav li").removeClass("active").eq(0).addClass("active");
        $(".appraisal-process li").eq(2).removeClass("active");
        //$("#appraisal02Wrap>.appraisal02-check").removeClass("active").eq(0).addClass("active")
    })
});

//验证提交
var code_bnt;
function yzm() {
    var arr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];  //候选数
    var str = '';
    for (var i = 0; i < 4; i++)
        str += '' + arr[Math.floor(Math.random() * arr.length)];
    code_bnt = str;
    $("#code_bnt").html(str)
    return str;

}
yzm();
$(".offer_inpbox").on("click", "#code_bnt", function () {
    yzm();
});
//            var money_low_sum;  //起始价格
//            var money_hgih_sum;   //最高加
//            var dev_date_low_sum;   //最低开发时长
//            var dev_date_high_sum;  //最高时长
$(".btnbox .btn").click(function () {
    var ip = returnCitySN.cip;
    var money = "￥" + money_low_sum + "-" + "￥" + money_hgih_sum;
    var dev_date = dev_date_low_sum + "-" + dev_date_high_sum;
    var names = $("#names").val();
    var phones_call = $("#phones_call").val();
    var codes = $("#codes").val();
    var save_data = {};
    save_data.mobile = phones_call;
    save_data.user_name = names;
    //save_data.platform_list=platform_list;
    save_data.money = money;
    save_data.dev_date = dev_date;
    var patrn = /^((\+?86)|(\(\+86\)))?\d{3,4}-\d{7,8}(-\d{3,4})?$|^((\+?86)|(\(\+86\)))?1\d{10}$/;
    if (!patrn.exec($.trim(phones_call))) {
        layer.alert('手机或电话格式错误');
        return false;
    }
    if (!names) {
        layer.alert("请输入您的姓名");
    }
    else if (!phones_call) {
        layer.alert("请输入手机号");
        createCode(); //刷新验证码
    }
    else if (!codes) {
        layer.alert("请输入验证码");
    }
    else if (codes != code_bnt) {
        layer.alert("验证码错误");
        $("#codes").val("");
        $("#codes").focus();
        yzm(); //刷新验证码
    }
    else {
        yzm(); //刷新验证码
        var obj = {
            "mobile": phones_call,
            "user_name": names,
            //"platform_list": JSON.stringify(platform_list),
            "models":JSON.stringify({
                type_id: type_id,
                platform_id: platform_id,
                model_category_id: model_category_id,
                model_id: model_id,
                function: function_id
            }),
            "money": money,
            "dev_date": dev_date,
            "ip":ip,
        };
                    var par = $.param(obj);
        $.ajax({
            url: host + '/AuotedPrice/save_functionData?'+par,
            type: "post",
            // data: save_data,
            data: obj,
            contentType: 'application/x-www-form-urlencoded',
            dataType: "json",
            success: function (ret) {
                if (ret.status == 1) {
                    $("#last_show_price").html('¥' + money_low_sum + '</span> <span>—</span> <span>¥' + money_hgih_sum + '</span> <i>元</i>')
                    layer.alert("短信发送成功！");
                } else {
                    layer.alert(ret.message);
                }
                //layer.alert(ret.message, function () {
                //location.href = '/member/user/quote/quote-list';
                // });

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest)
                console.log(errorThrown);

            }


        });


    }
})