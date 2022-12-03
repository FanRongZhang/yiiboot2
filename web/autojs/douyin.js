var mytool = require('tool.js')
var myfuction = require('functions.js')

/**
 * 抖音版本 https://www.wandoujia.com/apps/7461948/history_v190301
 * 
 */
// auto.waitFor()
console.show()


//设备环境设置
device.keepScreenOn()
console.log(app.autojs.versionName,"v221204")

var storage = storages.create("ABC");

let jiqiid = storage.get('jiqiid','')
if(!jiqiid){
  jiqiid = mytool.uuid()
}
storage.put('jiqiid',jiqiid)

console.log("machine id ",jiqiid)



function 头条粉丝界面自动点击关注(){
    while(1){
      var 滑动前的文字列表=mytool.返回当前页面的所有文字列表()
      一次关注的过程()
      mytool.从下往上滑动()
      var 滑动后的文字列表=mytool.返回当前页面的所有文字列表()
      if(两个数组是否一样(滑动前的文字列表,滑动后的文字列表)){
        log('滑动前后文字一样,说明到底部了')
        alert('全部关注完毕')
        break;
      }
    }
  }

  function 两个数组是否一样(滑动前的文字列表,滑动后的文字列表){
    if(滑动前的文字列表.toString()==滑动后的文字列表.toString()){
      // log(滑动前的文字列表.toString())
      // log(滑动后的文字列表.toString())
      return true
    }
    return false
  }

function 一次关注的过程(){
    var 关注fullId='cb9'
    // var 关注fullId='com.ss.android.article.news:id/cb9'
  
    var 关注collection=id(关注fullId).find()
    if(关注collection.empty()){
      log('没找到关注列表')
    }else{
      log(关注collection.length)
      var 有效的关注collection=[]
      关注collection.map(
        (关注)=>{
          var bounds = 关注.bounds()
          var left = bounds.left
          var top = bounds.top
          var right = bounds.right
          var bottom = bounds.bottom
          if (left < right && top < bottom && left >= device.width/2 && right <= device.width && top > 100 && bottom < device.height - 100) {
            有效的关注collection.push(关注)
          }
        }
      )
      log(有效的关注collection)
      log(有效的关注collection.length)
      有效的关注collection.map(
        (关注)=>{
          log(关注.bounds())
          if(!关注.selected()){
            mytool.click(关注)
            // 关注.select()
            sleep(3100)
          }
        }
      )
  
    }
  }

  
importPackage(Packages["okhttp3"]); //导入包

var client = new OkHttpClient.Builder().retryOnConnectionFailure(true).build();
var request = new Request.Builder().url("ws://192.168.1.178:8282").build();
client.dispatcher().cancelAll();//清理一次
myListener = {
    onOpen: function (webSocket, response) {
        print("建立连接中....");
        // //打开链接后，想服务器端发送一条消息
        // var json = {};
        // json.type="hello";
        // json.data= {device_name:"模拟设备",client_version:123,app_version:123,app_version_code:"233"};
        // var hello=JSON.stringify(json);
        // webSocket.send(hello);
    },
    onMessage: function (webSocket, msg) { //msg可能是字符串，也可能是byte数组，取决于服务器送的内容
      if(msg == "ping"){
        webSocket.send("pong")
      }else{
        if(msg == "服务器已连接"){
          print(msg)
        }else{
          //扫群
          if(msg == "saoqun"){

          }
        }
      }
    },
    onClosing: function (webSocket, code, response) {
        print("正在关闭ws");
    },
    onClosed: function (webSocket, code, response) {
        print("已关闭ws");
    },
    onFailure: function (webSocket, t, response) {
        print("ws错误");
        print( t);
    }
}

var webSocket= client.newWebSocket(request, new WebSocketListener(myListener)); //创建链接

setInterval(() => { // 防止主线程退出
    
}, 1000);


launchApp("抖音")
