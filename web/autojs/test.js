// console.show()
var mytool = require('tool.js')


print(textContains('直播中').exists() == false)

exit()
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

click(x,y)