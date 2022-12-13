var tool = {
  ws:"ws://jd.xiaozhumeimeigou.com:8282",
  api:"http://jd.xiaozhumeimeigou.com/api",
	now : function (type, addTime) {
		var dateObj = new Date();
		var cTime = dateObj.getTime();
		if(addTime){cTime += addTime;}
		if(!type){type = 'number';}
		if(type == 'number'){
			return cTime;
		}else if(type == 'str'){
			return this.toDate(cTime / 1000, 'str');
		}else if(type == 'array'){
			return this.toDate(cTime / 1000, 'array');
		}
	},
	// 时间戳转 YY-mm-dd HH:ii:ss
	toDate : function(timeStamp, returnType){
		timeStamp = parseInt(timeStamp);
		var date = new Date();
		if(timeStamp < 90000000000 ){
			date.setTime(timeStamp * 1000);
		}else{
			date.setTime(timeStamp );
		}
		var y = date.getFullYear();
		var m = date.getMonth() + 1;
		m = m < 10 ? ('0' + m) : m;
		var d = date.getDate();
		d = d < 10 ? ('0' + d) : d;
		var h = date.getHours();
		h = h < 10 ? ('0' + h) : h;
		var minute = date.getMinutes();
		var second = date.getSeconds();
		minute = minute < 10 ? ('0' + minute) : minute;
		second = second < 10 ? ('0' + second) : second;
		if(returnType == 'str'){return y + '-' + m + '-' + d + ' '+ h +':' + minute + ':' + second;}
		return [y, m, d, h, minute, second];
	},
}

// UUID
tool.uuid = function(len){
  var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
    var uuid = [], i;
    if(len){
        for (i = 0; i < len; i++){uuid[i] = chars[0 | Math.random() * chars.length];}
    }else{
        var r;
        uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
        uuid[14] = '4';
        for(i = 0; i < 36; i++){
            if (!uuid[i]){
                r = 0 | Math.random() * 16;
                uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
            }
        }
    }
    return uuid.join('');
}

tool.click = function(kongjian){
  try{
    if(typeof kongjian.clickable == 'function' && kongjian.clickable()){
      return kongjian.click()
    }else{
      x= kongjian.bounds().centerX()
      y= kongjian.bounds().centerY()
      return click(x,y)
    }
  }catch(e){
    print("click fail",e)
    return false
  }
}

//---------- 滑动
function swipeRnd(x1, y1, x2, y2, duration) {
    // log(arguments.callee.name + '开始')
    var k = 20
    var x1 = x1 + random(-(k), k)
    var y1 = y1 + random(-(k), k)
    var x2 = x2 + random(-(k), k)
    var y2 = y2 + random(-(k), k)
    var duration = duration + random(-(k), k)
    // swipeRnd2(x1, y1, x2, y2, duration)
    gesture(duration, [x1, y1], [x1 + 60, y1 - 80], [x2, y2])
  }
  
  // function swipeRnd2(x1, y1, x2, y2, duration) {
  //   gesture(duration, [x1, y1], [x1 + 60, y1 - 80], [x2, y2])
  // }
  
  tool.从下往上滑动 = function(y) {
    // log(arguments.callee.name + '开始')
    var y = y || 2
    var w = device.width
    var h = device.height
    var x1 = Math.floor(w / 5 * 2)
    var y1 = Math.floor(h / 5 * 4)
    var x2 = Math.floor(w / 5 * 1)
    var y2 = Math.floor(h / 5 * y)
    var duration = 300
    // log('滑动参数=', x1, y1, x2, y2, duration)
    swipeRnd(x1, y1, x2, y2, duration)
    // log(arguments.callee.name + '结束')
    sleep(1000)
  }

  tool.congshangwangxiahua = function(kongjian, times){
    x= kongjian.bounds().centerX()
    y= kongjian.bounds().centerY()
    var t = 0
    times =  times || 1
    while(++t <= times)
    {
      gesture(this.randomNum(800,1100), [x,y], [x + this.randomNum(1,10), y + this.randomNum(96,118)],[x+this.randomNum(2,12),1000])
    }
  }
  //-----------

  tool.getCurrentPackageName = function(){
    const ROOT_NODE_NAME = 'FrameLayout';
    const TIMEOUT_FOR_LOOKUP_NODE = 250;
    // 获取当前应用的包名
    const getCurrentPackage = function getPackageNameOfTheForegroundApplication(timeout) {
      const node = getRootNode(timeout);
      return node !== null ? node.packageName() : currentPackage();
    };
    // 获取 FrameLayout 根节点
    const getRootNode = function getFrameLayoutNode(timeout) {
      return className(ROOT_NODE_NAME).findOne(timeout || TIMEOUT_FOR_LOOKUP_NODE);
    };
    return getCurrentPackage()
}

  //---------------------返回当前页面的所有文字列表
  function 获取当前页面信息() {
    const ROOT_NODE_NAME = 'FrameLayout';
    const TIMEOUT_FOR_LOOKUP_NODE = 250;
    // 获取当前应用的包名
    const getCurrentPackage = function getPackageNameOfTheForegroundApplication(timeout) {
      const node = getRootNode(timeout);
      return node !== null ? node.packageName() : currentPackage();
    };
    // 获取 FrameLayout 根节点
    const getRootNode = function getFrameLayoutNode(timeout) {
      return className(ROOT_NODE_NAME).findOne(timeout || TIMEOUT_FOR_LOOKUP_NODE);
    };
    // 获取所有指定节点及其子节点的描述内容和文本内容
    const getAllTextualContent = function getAllDescriptionAndTextUnderNodeRecursively(node) {
      let items = [];
      const getDescAndText = function (node) {
        if (node !== null) {
          items.push(node.desc());
          items.push(node.text());
          for (let len = node.childCount(), i = 0; i < len; i++) {
            getDescAndText(node.child(i));
          }
        }
      };
      getDescAndText(node || getRootNode());
      return items.filter(item => item !== '' && item !== null);
    };
    return {
      getCurrentPackage: getCurrentPackage,
      getAllTextualContent: getAllTextualContent,
    };
  }
  
  function 返回当前页面的所有文字列表() {
    var 当前页面信息 = 获取当前页面信息()
    var 当前app = getAppName(当前页面信息.getCurrentPackage())
    log("当前app=", 当前app)
    var 当前页面所有文字列表 = 当前页面信息.getAllTextualContent()
    log("当前页面所有文字列表=", 当前页面所有文字列表)
    return 当前页面所有文字列表
  }
  
  tool.返回当前页面的所有文字列表 = 返回当前页面的所有文字列表
  //--------------


//姓名随机函数
tool.getRandomName = function() {
    var firstNames = new Array("赵", "钱", "孙", "李", "周", "吴", "郑", "王", "冯", "陈",
      "褚", "卫", "蒋", "沈", "韩", "杨", "朱", "秦", "尤", "许", "何", "吕", "施", "张", "孔", "曹", "严", "华", "金", "魏",
      "陶", "姜", "戚", "谢", "邹", "喻", "柏", "水", "窦", "章", "云", "苏", "潘", "葛", "奚", "范", "彭", "郎", "鲁", "韦",
      "昌", "马", "苗", "凤", "花", "方", "俞", "任", "袁", "柳", "酆", "鲍", "史", "唐", "费", "廉", "岑", "薛", "雷", "贺",
      "倪", "汤", "滕", "殷", "罗", "毕", "郝", "邬", "安", "常", "乐", "于", "时", "傅", "皮", "卞", "齐", "康", "伍", "余",
      "元", "卜", "顾", "孟", "平", "黄", "和", "穆", "萧", "尹", "欧阳", "慕容"
    );
    var secondNames = new Array("子璇", "淼子", "国栋", "夫子", "瑞堂", "甜", "敏", "尚", "国贤", "贺祥", "晨涛", "昊轩", "易轩", "益辰", "益帆", "益冉", "瑾春", "瑾昆", "春齐", "杨", "文昊", "东东", "雄霖", "浩晨", "熙涵", "溶溶", "冰枫", "欣欣", "宜豪", "欣慧", "建政", "美欣", "淑慧", "文轩", "文杰", "欣源", "忠林", "榕润", "欣汝", "慧嘉", "新建", "建林", "亦菲", "林", "冰洁", "佳欣", "涵涵", "禹辰", "淳美", "泽惠", "伟洋", "涵越", "润丽", "翔", "淑华", "晶莹", "凌晶", "苒溪", "雨涵", "嘉怡", "佳毅", "子辰", "佳琪", "紫轩", "瑞辰", "昕蕊", "萌", "明远", "欣宜", "泽远", "欣怡", "佳怡", "佳惠", "晨茜", "晨璐", "运昊", "汝鑫", "淑君", "晶滢", "润莎", "榕汕", "佳钰", "佳玉", "晓庆", "一鸣", "语晨", "添池", "添昊", "雨泽", "雅晗", "雅涵", "清妍", "诗悦", "嘉乐", "晨涵", "天赫", "玥傲", "佳昊", "天昊", "萌萌", "若萌");
    var thirdNames = new Array("1", "2", "3", "4", "5", "6", "7", "8", "9");
    var firstLength = firstNames.length;
    var secondLength = secondNames.length;
    var thirdLength = thirdNames.length;
    var i = parseInt(Math.random() * firstLength);
    var j = parseInt(Math.random() * secondLength);
    var k = parseInt(Math.random() * thirdLength);
    var name = firstNames[i] + secondNames[j] + thirdNames[k] + thirdNames[random(0, 8)] + thirdNames[random(0, 8)];
    return name;
  };
//-----------

//-------------一个字一个字的输入
tool.input = function(str){
    var strArray=str.split("")
    for(var i=0;i<strArray.length;i++){
      var char=strArray[i]
      input(char)
      sleep(random(300,500))
    }
}
//--------

//------静默安装app
tool.silentInstallApp=function(apk路径){
    shell("pm install -r " + apk路径 , true)
}
//------

//指定字符的classname
tool.findClassNameWithText = function(cname,text){
  var all = className(cname).find()
  for(var i = 0;i<all.length; i++){
    if(all[i].text() == text){
      return all[i]
    }
  }
  return false
}

//包含制定字符的classname
tool.findClassNameContainsText = function(cname,text){
  var all = className(cname).find()
  for(var i=0;i<all.length;i++){
    if(all[i].text().indexOf(text) !== -1){
      return all[i]
    }
  }
  return false
}

tool.findClassNameContainsDesc = function(cname,text){
  var all = className(cname).find()
  for(var i=0;i<all.length;i++){
    if(all[i].desc().indexOf(text) !== -1){
      return all[i]
    }
  }
  return false
}

module.exports = tool
