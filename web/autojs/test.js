


function personPageInfo(){
    sleep(2000)
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
  

  print(personPageInfo())