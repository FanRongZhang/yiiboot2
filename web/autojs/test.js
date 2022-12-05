console.show()


function personPageInfo(){

    var huozan = id('cpo').findOne().text()
    var guanzhu = id('d51').findOne().text()
    var fensi = id('d5t').findOne().text()
    var nick = id('iaz').findOne().text()
    var dyh = textStartsWith('抖音号：').exists() ? textStartsWith('抖音号：').findOne().text().replace('抖音号：','') : ''
  
    var note = ''
    if(textEndsWith('更多').exists()) {
      var more = textEndsWith('更多').findOne()
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
  
    var fensiqun = 0
    if(textEndsWith('个群聊').exists()){
      fensiqun = textEndsWith('个群聊').findOne().text().replace('个群聊','')
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
    }
  
  }

  print(personPageInfo())