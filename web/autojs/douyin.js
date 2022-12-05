var mytool = require('tool.js')
var myfuction = require('functions.js');

/**
 * 抖音版本 https://www.wandoujia.com/apps/7461948/history_v190301
 * 
 */
// auto.waitFor()
// console.show()


//设备环境设置
device.keepScreenOn()
console.log(app.autojs.versionName,"v221204")

var storage = storages.create("ABC");

let jiqiid = storage.get('jiqiid','')
if(!jiqiid){
  jiqiid = device.brand + '-' + device.getAndroidId()
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

var wsGlobalMsg = {}
var dw = device.width
var dh = device.height
var stopAllActions = false

function doBiz(wsBizMsg){
  wsGlobalMsg = JSON.parse(JSON.stringify(wsBizMsg))

  // //先停止执行的所有指令
  // stopAllActions = true
  // sleep(20000)
  // stopAllActions = false

  threads.shutDownAll() //停止所有子线程
  sleep(2000)

  //先回到首页
  while(currentActivity()!="com.ss.android.ugc.aweme.main.MainActivity"){
    back()
    sleep(5000)
  }

  threads.start(function(){
    if(wsGlobalMsg.action == 'zhaoqun'){
      zhaoqun()
    }else{
      zhaoqun()
    }
  })
  
}

function personPageInfo(){
  sleep(3000)
  var huozan = id('cpo').exists() ? id('cpo').findOne().text() : false
  if(huozan === false){
    huozan = idContains('cpo').exists() ? idContains('cpo').findOne().text() : ''
  }
  var guanzhu = id('d51').exists() ? id('d51').findOne().text() : false
  if(guanzhu === false){
    guanzhu = idContains('d51').exists() ? idContains('d51').findOne().text() : ''
  }
  var fensi = id('d5t').exists() ? id('d5t').findOne().text() : false
  if(fensi === false){
    fensi = idContains('d5t').exists() ? idContains('d5t').findOne().text() : ''
  }
  var nick = id('iaz').exists() ? id('iaz').findOne().text() : false
  if(nick === false){
    nick = idContains('iaz').exists() ? idContains('iaz').findOne().text() : ''
  }
  var dyh = textStartsWith('抖音号：').exists() ? textStartsWith('抖音号：').findOne().text().replace('抖音号：','') : ''

  var note = ''
  if(textEndsWith('更多  ').exists()) {
    var more = textEndsWith('更多  ').findOne()
    mytool.click( more.bounds().right , more.bounds().bottom - 5 )
    sleep(1000)
    note = more.text()
  }else if(id('n+s').exists()){
    note = id('n+s').findOne().text()
  }

  var zuopin = 0
  if(textStartsWith('作品').exists()){
    zuopin = textStartsWith('作品').findOne().text().replace('作品','')
  }

  var xihuan = 0
  if(textStartsWith('喜欢').exists()){
    xihuan = textStartsWith('喜欢').findOne().text().replace('喜欢','')
  }

  var fensiqun = 0
  var qunliao = []
  if(textEndsWith('个群聊').exists()){
    fensiqun = textEndsWith('个群聊').findOne().text().replace('个群聊','')

    //获取群聊信息

  }

  return {
    "huozan" : huozan,
    "guanzhu" : guanzhu,
    "fensi" : fensi,
    "nick" : nick,
    "dyh" : dyh,
    "zuopin" : zuopin,
    "fensiqun": fensiqun,
    "note": note,
    "xihuan": xihuan,
    "qunliao" : qunliao,
  }

}

function shanghua(){
  mytool.从下往上滑动(1.5)
}

function zhaoqun(){
  mytool.click(desc('搜索').findOne())

  //输入搜索关键字
  sleep(4000)
  id('et_search_kw').findOne().setText('风韵美女')
  mytool.click(desc('搜索').findOne())

  sleep(4000)

  //是不是搜索结果页
  // while(currentActivity() != "com.ss.android.ugc.aweme.search.activity.SearchResultActivity"){
  //   sleep(1000)
  // }

  var userMap = {}
  var userMapLength = 0

  //持续下拉找用户
  while(true){
    id('user_avatar').findOne(3500) // N/1000 秒钟内找到头像

    print("id('user_avatar').find().size() : ", id('user_avatar').find().size())

    if(id('user_avatar').find().size() == 0){
      shanghua()
      continue
    }

    id('user_avatar').find().forEach(function(one){
      var nick = one.desc()
      if(userMap[nick]){
        return
      }

      mytool.click(one) //点击头像位置进入

      var personInfo = personPageInfo()

      personInfo.head_nick = nick //记录刚刚刷到的用户
      userMap[personInfo.head_nick] = 1
      userMapLength += 1
      //到一定数量重置
      if(userMapLength > 1000){
        userMap = {}
        userMapLength = 0
      }

      print(personInfo)
      sleep(5000)
      back()
    
    })
    shanghua()
    sleep(random(3, 6 ))
  }


}


importPackage(Packages["okhttp3"]); //导入包

var client = new OkHttpClient.Builder().retryOnConnectionFailure(true).build();
var request = new Request.Builder().url("ws://192.168.1.178:8282").build();
client.dispatcher().cancelAll();//清理一次
myListener = {
    onOpen: function (webSocket, response) {
        print("已连接服务器");
        // //打开链接后，想服务器端发送一条消息
        var json = {};
        json.type="online";
        json.data= {
          jiqiid:jiqiid,
          w: device.width,
          h: device.height,
          brand: device.brand,
          product: device.product,
          release: device.release,
          imei: device.getIMEI(),
        };
        var url = "http://www.tuling123.com/openapi/api";
        r = http.postJson(url, json);
        print(r.body.string())
    },
    onMessage: function (webSocket, msg) { //msg可能是字符串，也可能是byte数组，取决于服务器送的内容
      msg = JSON.parse(msg)
      if(msg.action == "ping"){
        webSocket.send("pong")
      }else{
        doBiz(msg)
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

launchApp("抖音")
sleep(10000)
doBiz({})

// setInterval(() => { // 防止主线程退出
    
// }, 1000);

