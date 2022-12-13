// console.show()
var mytool = require('tool.js')


function shanghua(){
    mytool.从下往上滑动(2.3)
  }
  

function tuijianzhaoqun(){
    mytool.click(descContains('推荐').findOne())
    // sleep(2000)
    
    var left = 660
    var top = 583
    var right = 10
    var bottom = 660
  
    while(true){
      if(textContains('直播中').exists() == false){
        click(left,top)
        // getAndPostPersonPageInfo()
        back()    
      }
      shanghua()
      // sleep(random(1200, 2000 ))
    }
  }

  tuijianzhaoqun()
// print(id('user_avatar').find().size(),'----')
// // id('user_avatar').find().children()[0].click()
// id('user_avatar').find().each(function(v){
//     print(v.desc(),' ,,, ',v.text())
// })

// var w = boundsContains(left, top, device.width - right, bottom).find()
// print(w.size())
// w.each(function(v){
//     print( v.id() )
// })
// w.click()

    // print(textContains('直播中').exists() == false,x,y)
    // print(longClick(660, 583))
//   if(textContains('直播中').exists() == false){
//     // getAndPostPersonPageInfo()
//     // back()    
//   }
  // sleep(random(1200, 2000 ))
