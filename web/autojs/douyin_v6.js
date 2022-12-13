auto()

console.hide()
var mytool = require('tool.js')
//toolcode占位
let jihuoma = ''
//激活码code占位
let codetime=''
//codetime占位
let user_id = '0'
//usercode占位

let shutdown = false

/**
 * 抖音版本 https://www.wandoujia.com/apps/7461948/history_v190301
 * 
 */
// auto.waitFor()

device.keepScreenOn()
threads.shutDownAll()
launchApp("抖音")


function doBiz(taskInfo){
  threads.shutDownAll()

  let action = taskInfo.action
  print("action is ",action)

  threads.start(function(){
    try{

      if( !action ){

        var r1 = http.get(mytool.api + "/v1/autojscode/jihuoma-info?jihuoma="+jihuoma+"&user_id="+user_id)
        r1 = r1.body.json().data

        print("激活码", jihuoma)
        print("过期时间", r1 && r1.expire)
        print("代码同步时间",codetime)

        print("提示：激活码过期自动停止工作")
        print("已准备好接受操作指令，在网页 用户中心 进行操作")
        return
      }


      //先回到首页
      var i = 7
      while( --i > 0){
        back()
        sleep(5000)
      }

      switch(action){
        case 'search':
          zhaoqun(taskInfo.keyword)
          break
        case 'jinqun':
          jinqun()
          break
        case 'shuashouyetuijian':
          tuijianzhaoqun()
          break
        default:
          toast('不支持的操作，升级代码后再来')
      }

    }catch(e){
      print(e)
    }
  });
}


function getAndPostPersonPageInfo(){
  try{
      idContains('cpo').findOne(5000)
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
      if(id('n+s').exists()){
        var more = id('n+s').findOne()
        click( more.bounds().right - 10 , more.bounds().bottom - 10 ) //原生点击，展开更多
        sleep(30)
        
        note = more.text()
      }else if(textContains('更多').exists()) {
        var more = textContains('更多').findOne()
        if(more.text().indexOf('...') > 0)
        {
          click( more.bounds().right - 10 , more.bounds().bottom - 10 ) //原生点击，展开更多
          sleep(30)
        }
        note = more.text()
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

      if(dyh && textEndsWith('个群聊').exists() ){ // 有抖音号才进去
        fensiqun = textEndsWith('个群聊').findOne().text().replace('个群聊','')

        //获取群聊信息
        let clicked = mytool.click(textEndsWith('个群聊').findOne()) //点进去

        sleep(5000)

        textEndsWith("人)").find().each(function(v){
          try{
            var _mc = ''
            var _menkan =  ''
            try{
              _mc = v.parent().child(0).text()
              _menkan = v.parent().parent().findOne(textStartsWith("进群门槛")).text()
            }catch(e2){}
            qunliao.push({
              "renshu": v.text(),
              "mingcheng" : _mc,
              "menkan" : _menkan,
              // "anniu" : _anniu,
            })
          }catch(e){
            print(e)
          }
        })

        clicked && back() //回退

      }

      var personInfo = {
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

      print(personInfo)

      if(dyh){
        var url = mytool.api + "/v1/autojs/found-user?user_id="+user_id
        r = http.postJson(url, personInfo)
        print(r.body.string())
      }else{
        print('没抖音号')
      }

      return personInfo
  }catch(e1){}

}

function shanghua(){
  mytool.从下往上滑动(2.3)
}

//首页推荐页找群
function tuijianzhaoqun(){
  mytool.click(descContains('推荐').findOne())
  sleep(2000)
  
  var w = device.width
  var h = device.height
  var x = 660
  var y = 580
  if( w == 720 && h == 1600){
    x = 660
    y = 580
  }else{
    x = w * x / w
    y = y * y / h
  }

  while(true){
    if(text('点击进入直播间').exists() == false){
      click(x, y)
      getAndPostPersonPageInfo()
      back()    
    }
    shanghua()
    // sleep(random(1200, 2000 ))
  }
}

//搜索找群
function zhaoqun(keyword){
  if(mytool.click(desc('搜索').findOne())){//进入了搜索页
    sleep(2000)
    print("id('et_search_kw').exists() ",id('et_search_kw').exists())
    id('et_search_kw').findOne().setText(keyword)//输入搜索关键字
    if(mytool.click(desc('搜索').findOne()))//点击搜索
    {
      commonUserBiz()
    }
  }
}

//进群
function jinqun(){
  let c = 10
  if(mytool.click(desc('搜索').findOne())){//进入了搜索页
    sleep(2000)
    print("id('et_search_kw').exists() ",id('et_search_kw').exists())
    //while begin
    while(--c > 0){
      try{
          var res = http.get(mytool.api + '/v1/autojs/qun-get?user_id='+user_id)
          var qunModel = res.body.json().data
          if(!qunModel)break

          print(qunModel)
          
          id('et_search_kw').findOne().setText(qunModel.douyinhao)//输入搜索关键字
          if(mytool.click(desc('搜索').findOne()))//点击搜索
          {
                sleep(2000)
                if( mytool.click( textContains('抖音号:').findOne() ) ){ //进入到用户信息页
                  sleep(3000)
                  //获取群聊信息
                  let clicked = mytool.click(textEndsWith('个群聊').findOne()) //点进去
                  if(clicked){
                    sleep(5000)
                    let ci = 0
                    text("立即加入").find().each(function(v){
                      mytool.click(v) && ci++
                    })
                    text("申请加入").find().each(function(v){
                      mytool.click(v) && ci++
                    })
                    if(ci > 0)
                    {
                      http.get(mytool.api + '/v1/autojs/qun-join?douyinhao='+qunModel.douyinhao+'&user_id='+user_id)
                    }
                    back() //回退到用户信息页
                    sleep(2000)
                  }
                  back() //返回到搜索结果页面
                }
          }
          
      }catch(e){print(e)}
      // sleep(60*1000*30)
    }
    // while end
  }
}


function commonUserBiz(){
  var userMap = {}
  var userMapLength = 0

  //持续滑动界面
  while(true){
    id('user_avatar').findOne(4000) // N/1000 秒钟内找到头像

    print("id('user_avatar').find().size() : ", id('user_avatar').find().size())

    if(id('user_avatar').find().size() == 0){
      shanghua()
      continue
    }

    id('user_avatar').find().each(function(one){
      var nick = one.desc()
      if(userMap[nick]){
        print("跳过 ",nick)
        return
      }

      let clicked = mytool.click(one) //点击头像位置进入
      if(clicked){
        print("点击头像位置进入")

        var personInfo = getAndPostPersonPageInfo()
  
        personInfo.head_nick = nick //记录刚刚刷到的用户
        userMap[personInfo.head_nick] = 1
        userMapLength += 1
        //到一定数量重置
        if(userMapLength > 1000){
          userMap = {}
          userMapLength = 0
        }
  
        back()
      }
      
    })
    shanghua()
    sleep(random(1200, 2600 ))
  }

}


doBiz({})

importPackage(Packages["okhttp3"]); //导入包

var client_id=''
var client = new OkHttpClient.Builder().retryOnConnectionFailure(true).build();
var request = new Request.Builder().url(mytool.ws + '?jihuoma=' + jihuoma).build();
client.dispatcher().cancelAll();//清理一次
myListener = {
    onOpen: function (webSocket, response) {
        print("已连接服务器");
    },
    onMessage: function (webSocket, _msg) { //msg可能是字符串，也可能是byte数组，取决于服务器送的内容
      var msgJson = JSON.parse(_msg)
      if(msgJson.action == "ping"){
        webSocket.send("pong")
      }else if(msgJson.action == "client_id"){//设备上线
        client_id = msgJson.client_id
        var json = {
          jihuoma:jihuoma,
          w: device.width,
          h: device.height,
          brand: device.brand,
          product: device.product,
          release: device.release,
          android_id: device.getAndroidId(),
          client_id: client_id,
        }
        // print(json)
        var url = mytool.api + "/v1/autojs/online?user_id="+user_id
        r = http.postJson(url, json)
        // print(r.body.string())
      }else{
        print("下发任务 ",msgJson)
        if(msgJson.action == 'shutdown'){
          threads.shutDownAll()
          
          print('已下线，停止工作，激活码已未在该机器上使用')
          shutdown = true
        }else{
          doBiz(msgJson)
        }
      }
    },
    onClosing: function (webSocket, code, response) {
        print("正在关闭ws");
    },
    onClosed: function (webSocket, code, response) {
        print("已关闭ws");

        //auto ws 有关闭 bug ，一直在

        // var json = {
        //   client_id:client_id,
        // };
        // var url = mytool.api + "/v1/autojs/close";
        // r = http.postJson(url, json);
        // print(r.body.string())
    },
    onFailure: function (webSocket, t, response) {
        print("ws onFailure:");
        print( t);

        //auto ws 有关闭 bug ，一直在

        // var json = {
        //   client_id:client_id,
        // };
        // var url = mytool.api + "/v1/autojs/close";
        // r = http.postJson(url, json);
        // print(r.body.string())
    }
}

var webSocket= client.newWebSocket(request, new WebSocketListener(myListener)); //创建链接

//去掉弹窗 
let iloop = setInterval(function(){
  text('青少年模式').exists() && text('我知道了').exists() && mytool.click(text('我知道了').findOne())
  text('以后再说').exists() && text('立即升级').exists() && mytool.click(text('以后再说').findOne())
  text('反馈邀请').exists() && text('直接退出').exists() && mytool.click(text('直接退出').findOne())
  if(shutdown){
    clearInterval(iloop)
    exit()
  }
},3000)

