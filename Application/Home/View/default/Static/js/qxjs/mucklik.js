$(function(){
	$float_speed=2000; //milliseconds
	$float_easing="easeOutQuint";
	$menu_fade_speed=500; //milliseconds
	$closed_menu_opacity=0.75;

	//cache vars
	$fl_menu=$("#kfmenu");
	$(function(){
		menuPosition=$('#kfmenu').position().top;
		FloatMenu();
	});

	$(window).scroll(function () {
		FloatMenu();
	});

	function FloatMenu(){
		var scrollAmount=$(document).scrollTop();
		var newPosition=menuPosition+scrollAmount;
		if($(window).height()<$fl_menu.height()){
			//$fl_menu.css("top",menuPosition);
			$fl_menu.css("top","50%");
		} else{
			$fl_menu.stop().animate({top: newPosition}, $float_speed, $float_easing);
		}
	}

	$(".touch").click(function(){
		if ($(this).hasClass("on")) {
			$(this).removeClass("on");
			$(this).animate({left:'150px'});
			$(".menuconten").animate({right:"-155px"});
			$(".touch span").html("展开");

		} else {
			$(this).addClass("on");
			$(".menuconten").animate({right:"0px"});
			$(this).animate({left:"0px"});
			$(".touch span").html("收起");
		}
	});

	//百度商桥
	/*$('body').on('click','.offerlink',function(){
		window.open("http://p0.qiao.baidu.com/im/index?siteid=8826560&ucid=11090049&lastsubid=1435248&from=%E6%B5%99%E6%B1%9F%E6%9D%AD%E5%B7%9E&bid=ee9e29c56d51911bf525bcf2&tok=1b664psjd&chattype=1&groupid=&groupname=&subid=&subname=&ref=http%3A%2F%2Fwww.qixiangnet.com%2Findex.html","","width=600,height=500,left=80, top=80,toolbar=no, status=no, menubar=no, resizable=yes, scrollbars=yes");
	});

	$('body').on('click','.consultlink',function(){
		window.open("http://p0.qiao.baidu.com/im/index?siteid=8826560&ucid=11090049&lastsubid=1435248&from=%E6%B5%99%E6%B1%9F%E6%9D%AD%E5%B7%9E&bid=ee9e29c56d51911bf525bcf2&tok=1b664psjd&chattype=1&groupid=&groupname=&subid=&subname=&ref=http%3A%2F%2Fwww.qixiangnet.com%2Findex.html","","width=600,height=500,left=80, top=80,toolbar=no, status=no, menubar=no, resizable=yes, scrollbars=yes");
	});*/
});
