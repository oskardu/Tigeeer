//js去除空格函数 或去除指定的字符串
//此处为string类添加三个成员
String.prototype.trim = function(){ return trim(this);}
String.prototype.ltrim = function(){return ltrim(this);}
String.prototype.rtrim = function(){return rtrim(this);}


function ltrim(str )
{
    var exp=arguments[1]?arguments[1]:" ";  //第二个参数 默认为空格 可以是 逗号 或其他的
    var i;
    for(i=0;i<str.length;i++)
    {
        if(str.charAt(i)!=exp && str.charAt(i)!=exp)break;
    }
    str=str.substring(i,str.length);
    return str;
}
function rtrim(str)
{
    var exp=arguments[1]?arguments[1]:" ";  //第二个参数 默认为空格 可以是 逗号 或其他的
    var i;
    for(i=str.length-1;i>=0;i--)
    {
        if(str.charAt(i)!=exp && str.charAt(i)!=exp)break;
    }
    str=str.substring(0,i+1);
    return str;
}
function trim(str)
{
    var exp=arguments[1]?arguments[1]:" ";  //第二个参数 默认为空格 可以是 逗号 或其他的
    return ltrim(rtrim(str,exp),exp);
}

/* 退出按钮
 */
$(function(){
    $('.calendarIcon').click(function(){
        $(this).siblings('.dateSelect').trigger('focus')
    })

    $('#exit_btn').click(function() {
        if (confirm('确认退出')) {
        	location.href ='/managers/default/logout';
        }
        return false;
    })

    //点击外围 显示层消失
    $(document).bind({
        click: function() {
            var $subMenu;

            $subMenu = $(this).find('.members');
            if ($subMenu.is(':visible')) {
                return $subMenu.slideUp('fast').siblings('.task-arrow').removeClass('task-arrowt');
            }
        }
    });

    //取消
    $('#pms-cancel').click(function(){
        $('#dia').hide();
        return false;
    });
    
    $('#pms-resetpwd-cancel').click(function(){
        $('#resetpwd').hide();
        return false;
    });
    
    $('#user-info-cancel').click(function(){
        $('#user-info-dia').hide();
        return false;
    });

    $('#new-pms-cancel').click(function(){
        $('#new-dia').hide();
        return false;
    });

    $('#pms-menu-role-cancel').click(function(){
        $('#menu_role').hide();
        return false;
    });	
    
    /*快捷导航
     */

    quickNav = function() {
        var $l, $range, $t, $w;

        $range = $(window).scrollTop();
        $t = $('#header').outerHeight(true);
        $l = $('.main-left').outerWidth(true);
        $w = $(document).width();
        /*修正快捷导航的位置
         */

        $('.main-quickBtn').css({
            top: $t + 'px',
            left: $l + 'px',
            width: $w - $l + 'px'
        });
        /*滚动超过头部时，改变快捷导航的位置及display
         */

        if ($range > $t) {
            $('.main-quickBtn').css({
                position: 'fixed',
                top: 0,
                left: 0,
                width: '100%'
            });
            return $('#main-quickNav').css({
                paddingLeft: '0px',
                width: $w + 'px'
            });
        } else {
            $('.main-quickBtn').css({
                position: 'absolute',
                top: $t + 'px',
                left: $l + 'px'
            });
            return $('#main-quickNav').css({
                paddingLeft: $l + 'px'
            });
        }
    };
   quickNavFn = function() {
        $(window).scroll(function() {
            return quickNav();
        });
        return $(window).resize(function() {
            return quickNav();
        });
    };
    quickNav();
    quickNavFn();
})

function isEmptyObject(obj){
    for(var n in obj){return false}
    return true;
}

window.imageResize = function(img, maxWidth, maxHeight, autoMargin) {
  var currHeight, currWidth, img2, rate, rateX, rateY, toHeight, toWidth;
  currWidth = Math.max(img.clientWidth, img.width);
  currHeight = Math.max(img.clientHeight, img.height);
  if (currWidth < 1 || currHeight < 1) {
    img2 = img.cloneNode();
    img2.style.visibility = 'hidden';
    img2.style.position = 'absolute';
    img2.style.display = '';
    document.body.appendChild(img2);
    currWidth = Math.max(img2.clientWidth, img2.width);
    currHeight = Math.max(img2.clientHeight, img2.height);
    document.body.removeChild(img2);
    img2 = null;
  }
  if (currWidth > maxWidth || currHeight > maxHeight) {
    rateX = maxWidth / currWidth;
    rateY = maxHeight / currHeight;
    rate = Math.max(rateX, rateY);
    toWidth = Math.floor(currWidth * rate);
    toHeight = Math.floor(currHeight * rate);
    if (toWidth > maxWidth || toHeight > maxHeight) {
      rate = Math.min(rateX, rateY);
      toWidth = Math.floor(currWidth * rate);
      toHeight = Math.floor(currHeight * rate);
    }
    img.width = toWidth;
    img.height = toHeight;
  }
  if (!rate) {
    toWidth = currWidth;
    toHeight = currHeight;
  }
  if (!autoMargin) {
    img.style.marginLeft = Math.round((maxWidth - toWidth) / 2) + 'px';
    img.style.marginTop = Math.round((maxHeight - toHeight) / 2) + 'px';
  } else if (autoMargin === 1) {
    img.style.marginLeft = Math.round((maxWidth - toWidth) / 2) + 'px';
  } else if (autoMargin === 2) {
    img.style.marginTop = Math.round((maxHeight - toHeight) / 2) + 'px';
  }
  return void 0;
};
