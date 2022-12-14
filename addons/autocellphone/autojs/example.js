var mytool = require('tool.js')

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