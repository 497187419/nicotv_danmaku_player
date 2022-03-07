// ==UserScript==
// @namespace   nicotv
// @name        Nicotv弹幕播放器
// @description 替换Nico普通播放器为弹幕播放器，关联B站弹幕池
// @icon        data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB0PSIxNjI3MjIzODMwMTE0IiBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHAtaWQ9Ijc2MCIgd2lkdGg9IjEyOCIgaGVpZ2h0PSIxMjgiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPkBmb250LWZhY2UgeyBmb250LWZhbWlseTogZmVlZGJhY2staWNvbmZvbnQ7IHNyYzogdXJsKCIvL2F0LmFsaWNkbi5jb20vdC9mb250XzEwMzExNThfMXVocjhyaTBwazUuZW90PyNpZWZpeCIpIGZvcm1hdCgiZW1iZWRkZWQtb3BlbnR5cGUiKSwgdXJsKCIvL2F0LmFsaWNkbi5jb20vdC9mb250XzEwMzExNThfMXVocjhyaTBwazUud29mZjIiKSBmb3JtYXQoIndvZmYyIiksIHVybCgiLy9hdC5hbGljZG4uY29tL3QvZm9udF8xMDMxMTU4XzF1aHI4cmkwcGs1LndvZmYiKSBmb3JtYXQoIndvZmYiKSwgdXJsKCIvL2F0LmFsaWNkbi5jb20vdC9mb250XzEwMzExNThfMXVocjhyaTBwazUudHRmIikgZm9ybWF0KCJ0cnVldHlwZSIpLCB1cmwoIi8vYXQuYWxpY2RuLmNvbS90L2ZvbnRfMTAzMTE1OF8xdWhyOHJpMHBrNS5zdmcjaWNvbmZvbnQiKSBmb3JtYXQoInN2ZyIpOyB9Cjwvc3R5bGU+PC9kZWZzPjxwYXRoIGQ9Ik05MjMuNSAzMzcuNmMtMjIuNi01My40LTU0LjktMTAxLjMtOTYtMTQyLjUtNDEuMi00MS4yLTg5LjEtNzMuNS0xNDIuNS05Ni01NS4zLTIzLjQtMTE0LTM1LjItMTc0LjUtMzUuMlMzOTEuMyA3NS44IDMzNiA5OS4xYy01My40IDIyLjYtMTAxLjMgNTQuOS0xNDIuNSA5Ni00MS4yIDQxLjItNzMuNSA4OS4xLTk2IDE0Mi41LTIzLjQgNTUuMy0zNS4yIDExNC0zNS4yIDE3NC41czExLjkgMTE5LjIgMzUuMiAxNzQuNWMyMi42IDUzLjQgNTQuOSAxMDEuMyA5NiAxNDIuNSA0MS4yIDQxLjIgODkuMSA3My41IDE0Mi41IDk2IDU1LjMgMjMuNCAxMTQgMzUuMiAxNzQuNSAzNS4yczExOS4yLTExLjkgMTc0LjUtMzUuMmM1My40LTIyLjYgMTAxLjMtNTQuOSAxNDIuNS05NiA0MS4yLTQxLjIgNzMuNS04OS4xIDk2LTE0Mi41IDIzLjQtNTUuMyAzNS4yLTExNCAzNS4yLTE3NC41cy0xMS44LTExOS4yLTM1LjItMTc0LjV6TTQ0Mi44IDkxMWMtMTU1LjktMjUuNS0zMDUuNi0xNzUuMi0zMzEuMS0zMzEuMS00NS4xLTI3NS44IDE5MS01MTEuOCA0NjYuNy00NjYuNyAxNTUuOSAyNS40IDMwNS42IDE3NS4xIDMzMS4xIDMzMUM5NTQuNiA3MjAgNzE4LjYgOTU2IDQ0Mi44IDkxMXoiIHAtaWQ9Ijc2MSI+PC9wYXRoPjxwYXRoIGQ9Ik00MjQgMzQ1LjVjMi42IDYgNi4xIDExLjQgMTAuMyAxNi4yIDEuNSAxLjcgMy44IDIuNyA2LjEgMi43aDExMS41YzEuNiAwIDMuMi0wLjUgNC41LTEuNCAyLjYtMS43IDQuNi0zLjMgNi00LjcgNi40LTUuNSAxMC40LTExLjMgMTEuOS0xNy4zIDAuMi0wLjYgMC4yLTEuMyAwLjItMS45di04Ny41YzAtMC40IDAtMC45LTAuMS0xLjMtMC41LTIuOC0xLjEtNS4xLTEuOS02LjgtNS4zLTExLjUtMTUuMi0xOC41LTI5LjctMjAuOC0wLjQtMC4xLTAuOS0wLjEtMS40LTAuMWgtOTEuM2MtMC44IDAtMS41IDAuMS0yLjMgMC4zLTIuMyAwLjYtNC4yIDEuMi01LjYgMS41LTAuNSAwLjEtMC45IDAuMy0xLjMgMC41LTExLjEgNC45LTE3LjggMTMuMy0yMC4xIDI1LjMtMC4xIDAuNS0wLjEgMS4xLTAuMSAxLjZ2ODIuOWMwIDAuOSAwLjEgMS44IDAuNCAyLjYgMS4xIDMuNCAyLjEgNi4yIDIuOSA4LjJ6IG0xNi45LTc2LjRjMC0xLjMgMC4zLTIuNiAwLjktMy43IDAuMy0wLjcgMC42LTEuMiAwLjktMS43IDAuMi0wLjQgMC40LTAuOCAwLjctMS4xIDMuOC01LjMgOS40LTkgMTYuNy0xMS4zIDAuOC0wLjIgMS41LTAuMyAyLjMtMC4zaDY5LjhjMS4zIDAgMi41IDAuMyAzLjcgMC45bDkuOSA1YzIuOCAxLjQgNC41IDQuMiA0LjUgNy4zVjMzMWMwIDMuMS0xLjggNS45LTQuNSA3LjNsLTkuOSA1Yy0xLjEgMC42LTIuNCAwLjktMy43IDAuOWgtNjkuNGMtMS4xIDAtMi4xLTAuMi0zLjEtMC42LTIuOC0xLjEtNS0yLjMtNi41LTMuNC01LjktNC4yLTkuOC05LjItMTEuOC0xNS0wLjMtMC44LTAuNC0xLjYtMC40LTIuNGwtMC4xLTUzLjd6TTM4NC4yIDU3NC45Yy0zLjUtNy4xLTguMS0xMy4xLTEzLjctMTguMi0xLjUtMS4zLTMuNS0yLjEtNS41LTIuMWgtNjguNWMtMSAwLTEuOS0wLjItMi44LTAuNS0zLjMtMS4yLTUuOC0yLjQtNy4zLTMuNi01LjUtMy44LTkuMi04LjgtMTEuMi0xNS4xLTAuMi0wLjctMC4zLTEuNS0wLjMtMi4zdi05OC43YzAtMSAwLjItMS45IDAuNS0yLjggMS4yLTMuMyAyLjQtNS44IDMuNi03LjMgMy44LTUuNSA4LjgtOS4yIDE1LjEtMTEuMSAwLjctMC4yIDEuNS0wLjMgMi4zLTAuM2g0Ni40YzAuNyAwIDEuMy0wLjEgMi0wLjIgMTAuNy0yLjYgMTgtNS4yIDIyLTcuOSA3LTQuMyAxMS40LTEwLjggMTMuMy0xOS40IDAuMS0wLjYgMC4yLTEuMiAwLjItMS43VjI1Mi42YzAtMS4xLTAuMi0yLjMtMC43LTMuMy0yLjMtNS4zLTQuNS05LjQtNi41LTEyLjItMC4xLTAuMi0wLjMtMC40LTAuNC0wLjYtNy42LTkuMy0xNy4yLTEzLjktMjguOC0xMy45aC04MmMtMi4yIDAtNC4zIDAuOS01LjggMi40LTAuNyAwLjctMS4xIDEuMi0xLjMgMS42LTIuMyAzLjYtMy40IDctMy40IDEwLjEgMCAzLjMgMS42IDYuMiA1IDguNiAxLjQgMSAzLjEgMS42IDQuOCAxLjZoODAuMmMxLjYgMCAzLjIgMC41IDQuNSAxLjRsNi40IDQuM2MyLjMgMS41IDMuNiA0LjEgMy42IDYuOHYxMTYuMWMwIDMuMS0xLjggNS45LTQuNSA3LjNsLTkuOSA1Yy0xLjEgMC42LTIuNCAwLjktMy43IDAuOWgtNTcuNmMtMC44IDAtMS43IDAuMS0yLjUgMC40LTUuMiAxLjctOS4xIDMuNC0xMS42IDUtOC40IDYuMi0xMy41IDE1LTE1LjQgMjYuMy0wLjEgMC41LTAuMSAwLjktMC4xIDEuNHYxMjMuOGMwIDAuNyAwLjEgMS40IDAuMyAyIDEuMyA0LjkgMi41IDguNiAzLjggMTEuMSAzLjUgNy4xIDguMSAxMy4xIDEzLjcgMTguMiAxLjUgMS4zIDMuNSAyLjEgNS41IDIuMWg2Ny42YzEuNiAwIDMuMSAwLjQgNC41IDEuMyA1LjIgMy40IDguOCA3LjYgMTEgMTIuNSAyLjUgNS42IDMuNyAxMiAzLjcgMTkuMiAwIDUuOS0wLjUgMTEuNy0xLjMgMTcuNS0yLjIgMTQuNy0zLjMgMjMuMy0zLjQgMjZ2MC42Yy0zLjYgMzcuMi02IDU5LjMtNy40IDY2LjUtNC44IDIzLjItMTQuMSA0My43LTI3LjkgNjEuMy0wLjggMS0xLjggMS45LTMgMi40LTIuNiAxLjItNS40IDEuNy04LjIgMS43LTUuOSAwLTEyLjgtMi41LTIwLjktNy40LTE3LjEtMTAuNC0yNy41LTE1LjUtMzEtMTUuNS02LjQgMC0xMS44IDMuOC0xNi4zIDExLjMtMS43IDIuOS0xLjQgNi42IDAuNyA5LjMgNi41IDcuOSAxNS4xIDE0LjMgMjUuNyAxOS4zIDEyLjYgNS44IDI1LjQgOC44IDM4LjUgOC44IDEzLjUgMCAyNC43LTMuMyAzMy43LTkuOCA4LjktNi40IDE0LjMtMTYuNSAxNi4xLTMwLjIgMC0wLjQgMC4xLTAuNyAwLjItMS4xIDUuOC0yMC41IDEwLjItMzcuNCAxMy4zLTUwLjggOS0zOS40IDE1LjItNzcuNiAxOC45LTExNC4zVjU4OGMwLTAuNy0wLjEtMS40LTAuMy0yLTEuNC00LjgtMi43LTguNS0zLjktMTEuMXpNNjE5IDM0OC44YzMuNiA2LjUgOC40IDExLjMgMTQuMiAxNC41IDEuMiAwLjcgMi42IDEgNCAxaDExMy4xYzEuNiAwIDMuMi0wLjUgNC41LTEuNCAyLjYtMS43IDQuNi0zLjMgNi00LjcgNi4xLTYuMSAxMC4xLTEzLjIgMTEuOS0yMS40IDAuMS0wLjYgMC4yLTEuMiAwLjItMS45di03OC40YzAtMS4yLTAuMi0yLjMtMC43LTMuNC0yLjQtNS41LTQuNy05LjctNi43LTEyLjUtNy4yLTkuNC0xNi45LTE0LjItMjktMTQuMmgtODVjLTEwLjggMC0xOS42IDIuOC0yNi4zIDguNC02LjggNS42LTEwLjEgMTMuNi0xMC4xIDI0djc5LjVjMCAxIDAuMiAyLjEgMC42IDMuMSAxIDMuMSAyLjIgNS41IDMuMyA3LjR6IG0xNi4yLTc5LjdjMC0xLjMgMC4zLTIuNiAwLjktMy43IDAuMy0wLjcgMC42LTEuMiAwLjktMS43IDAuMi0wLjQgMC40LTAuOCAwLjctMS4xIDMuOC01LjMgOS40LTkgMTYuNy0xMS4zIDAuNy0wLjIgMS41LTAuMyAyLjMtMC4zaDc3LjljMS4zIDAgMi41IDAuMyAzLjcgMC45bDkuOSA1YzIuOCAxLjQgNC41IDQuMiA0LjUgNy4zVjMzMWMwIDMuMS0xLjggNS45LTQuNSA3LjNsLTkuOSA1Yy0xLjEgMC42LTIuNCAwLjktMy43IDAuOUg2NTdjLTEuMSAwLTIuMS0wLjItMy4xLTAuNi0yLjgtMS4xLTUtMi4zLTYuNS0zLjQtNS45LTQuMi05LjgtOS4yLTExLjgtMTUtMC4zLTAuOC0wLjQtMS42LTAuNC0yLjR2LTUzLjd6TTc4Ni42IDY5Ni45Yy0wLjktMC4zLTEuOC0wLjQtMi43LTAuNEg2MTkuMWMtNC41IDAtOC4yLTMuNy04LjItOC4ydi00NC40YzAtNC41IDMuNy04LjIgOC4yLTguMmgxMDguN2MwLjQgMCAwLjcgMCAxLjEtMC4xIDYuOS0wLjkgMTIuMS0yIDE1LjctMy4zIDEwLjQtMy44IDE4LjItMTAgMjMuMi0xOC45IDAuNy0xLjMgMS4xLTIuNyAxLjEtNC4xVjQyOC41YzAtMi4yLTAuOS00LjMtMi40LTUuOGwtMTEuNC0xMS40Yy0xLjUtMS41LTMuNi0yLjQtNS44LTIuNGgtMzAxYy0yLjIgMC00LjMgMC45LTUuOCAyLjRsLTExLjQgMTEuNGMtMS41IDEuNS0yLjQgMy42LTIuNCA1Ljh2MTg4LjRjMCAxLjggMC42IDMuNiAxLjcgNXMyLjEgMi41IDMuMSAzLjFjNy42IDcuMiAxNS41IDEwLjggMjMuNiAxMC44aDEyMS40YzQuNSAwIDguMiAzLjcgOC4yIDguMnY0NC40YzAgNC41LTMuNyA4LjItOC4yIDguMkg0MTQuMmMtMS4xIDAtMi4xIDAuMi0zLjEgMC42LTEgMC40LTEuOCAwLjgtMi41IDEuMS0wLjUgMC4yLTAuOSAwLjUtMS4zIDAuOC0yLjMgMS44LTMuNCA0LjEtMy40IDcgMCAxLjggMC43IDQgMi4xIDYuNiAxLjQgMi42IDQuMiA0LjIgNy4xIDQuMmgxNjUuNWM0LjUgMCA4LjIgMy43IDguMiA4LjJ2OTcuNWMwIDIuOCAxLjMgNS40IDMuNyA2LjggMC4xIDAuMSAwLjIgMC4xIDAuNCAwLjIgMy4xIDEuOCA2LjEgMi43IDguOCAyLjcgNC41IDAgOC4xLTIuOCAxMC43LTguNCAwLjUtMS4xIDAuOC0yLjMgMC44LTMuNVY3MjVjMC00LjUgMy43LTguMiA4LjItOC4yaDE3NGMwLjktMC40IDEuMy0wLjkgMS4zLTEuMyAwLjQtMS40IDAuNy0zLjggMC43LTcuNC0wLjItNS42LTMuMS05LjMtOC44LTExLjJ6TTYxMC45IDQzNy4zYzAtNC41IDMuNy04LjIgOC4yLTguMmgxMDMuNmMxIDAgMi4xIDAuMiAzIDAuNmwxMy42IDUuNWMzLjEgMS4yIDUuMiA0LjMgNS4yIDcuNlY1MDJjMCA0LjUtMy43IDguMi04LjIgOC4ySDYxOS4xYy00LjUgMC04LjItMy43LTguMi04LjJ2LTY0Ljd6IG0wIDEwMS4zYzAtNC41IDMuNy04LjIgOC4yLTguMmgxMTcuM2M0LjUgMCA4LjIgMy43IDguMiA4LjJ2NTkuNmMwIDMuMS0xLjggNS45LTQuNSA3LjNsLTkuOSA1Yy0xLjEgMC42LTIuNCAwLjktMy43IDAuOUg2MTkuMWMtNC41IDAtOC4yLTMuNy04LjItOC4ydi02NC42eiBtLTI0LjMgNjQuNmMwIDQuNS0zLjcgOC4yLTguMiA4LjJINDY2LjFjLTAuNiAwLTEuMS0wLjEtMS43LTAuMi0xLjgtMC40LTQuMi0wLjgtNy4zLTEuMi0yLjktMC40LTUuMS0xLjgtNi45LTQuNS0wLjgtMS4zLTEuMi0yLjgtMS4yLTQuM3YtNjIuN2MwLTQuNSAzLjctOC4yIDguMi04LjJoMTIxLjNjNC41IDAgOC4yIDMuNyA4LjIgOC4ydjY0Ljd6IG0wLTEwMS4yYzAgNC41LTMuNyA4LjItOC4yIDguMkg0NTcuMWMtNC41IDAtOC4yLTMuNy04LjItOC4ydi01OS42YzAtMy4xIDEuOC01LjkgNC41LTcuM2w5LjktNWMxLjEtMC42IDIuNC0wLjkgMy43LTAuOWgxMTEuNGM0LjUgMCA4LjIgMy43IDguMiA4LjJWNTAyeiIgcC1pZD0iNzYyIj48L3BhdGg+PC9zdmc+
// @copyright   2021, linker (https://<服务器域名>/)
// @match       http://www.nicotv.me/video/play/*
// @match       http://www.nicotv.club/video/play/*
// @grant       none
// @license     MIT
// @version     1.202107261145
// @author      linker
// @run-at      document-end
// @require     https://<服务器域名>/js/DPlayer.min2.js
// @description 2021/7/20 下午9:16:07
// ==/UserScript==

/*——————————————————————————————— 处理视频地址 ———————————————————————————————*/
// 获取视频地址，解组urlencode
var video_url = decodeURIComponent($(".embed-responsive-item").attr("src"))
// 拆分
video_url = video_url.split("?")[2];
// 截取
video_url = video_url.split('url=')[1]
if(video_url.split("&time")[1])
{
    video_url = video_url.split("&time")[0]
}
/*——————————————————————————————— /处理视频地址 ———————————————————————————————*/
/*——————————————————————————————— 处理视频框 ———————————————————————————————*/
// 移除原有视频框
$(".embed-responsive-item").remove()
$("#cms_player").removeClass('embed-responsive')
$("#cms_player").removeClass('embed-responsive-4by3')
$("#cms_player").removeClass('dplayer-playing')
/*——————————————————————————————— /处理视频框 ———————————————————————————————*/
/*——————————————————————————————— 追加调整输入框 ———————————————————————————————*/
var input_div = `
<div class="input-group" style="margin-bottom:5px;">
        <input type="text" class="form-control" placeholder="(｡･∀･)ﾉﾞ Hi~!" id="adjust_data">
        <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="change_action">请选择操作 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="javascript:change_action_id=1;$('#change_action').html('修正视频名称');$('#adjust_data').attr('placeholder', '当前视频名称为: '+title+' ,若弹幕匹配失败,请提交正确的视频名称用于匹配');$('#adjust_data').attr('title', '当前视频名称为: '+title+' ,若弹幕匹配失败,请提交正确的视频名称用于匹配');console.log('番剧');">修正视频名称</a></li>
                        <li><a href="javascript:change_action_id=2;$('#change_action').html('修正集数名称');$('#adjust_data').attr('placeholder', '当前集数名称为:'+num+',若弹幕匹配失败,请提交正确的集数名称用于匹配');console.log('集数');$('#adjust_data').attr('title', '当前集数名称为:'+num+',若弹幕匹配失败,请提交正确的集数名称用于匹配');">修正集数名称</a></li>
                        <li><a href="javascript:change_action_id=3;$('#change_action').html('视频地址关联');$('#adjust_data').attr('placeholder', '请输入对应的视频地址');console.log('对应地址');">视频地址关联</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="javascript:change_action_id=99;$('#change_action').html('建议或意见');$('#adjust_data').attr('placeholder','(｡･∀･)ﾉﾞ Hi~! 请写下您的宝贵建议或意见。');console.log('提交建议或意见');">对插件的建议与意见</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="javascript:$('#change_action').html('请选择操作 <span class=\`caret\`></span>');$('#adjust_data').attr('placeholder','(｡･∀･)ﾉﾞ Hi~!');console.log('你好');">取消</a></li>
                </ul>
                <a class="btn btn-success" href="javascript:$.ajax({url:'https://<服务器域名>/?module=adjust',type:'post',data:{action_id:change_action_id,value:$('#adjust_data').val(),name:send_title_name,num:send_key_index},async:false,dataType:'json',success:function(a){console.log(a);change_action_id=0;$('#adjust_data').val('');$('#adjust_data').attr('placeholder', '感谢辅助！快刷新试下！');},error:function(){console.log('AJAX请求失败')}});">提交</a>
        </div>
</div>`;

document.querySelector("body > div:nth-child(5) > div.row.ff-row > div.col-md-8.ff-col > div.clearfix").insertAdjacentHTML('beforeend',input_div);
/*——————————————————————————————— /追加调整输入框 ———————————————————————————————*/
/*——————————————————————————————— 创建弹幕播放器 ———————————————————————————————*/
// 声明全局变量
window.change_action_id=0
window.title = $('title').html();
title = title.substring(1, title.length-16);
title = title.split('》');
// 集数
window.num = title[1].substring(title[1].indexOf("第") + 1,title[1].indexOf("集"))
// 标题
title = title[0]
console.log('title', title);
console.log('num', num);

new DPlayer({
    element: document.getElementById('cms_player'),
    autoplay: true,
    video: {
        // 视频地址
        url: video_url
    },
    danmaku: {
        // 弹幕池
        addition: ['https://<服务器域名>/?module=searchdanmu&title='+title+'&num='+num]
    }
});
/*——————————————————————————————— /创建弹幕播放器 ———————————————————————————————*/