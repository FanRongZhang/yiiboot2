// console.show()
var mytool = require('tool.js')



text('青少年模式').exists() && text('我知道了').exists() && mytool.click(text('我知道了').findOne())