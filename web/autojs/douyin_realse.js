
let api = 'http://192.168.0.125/api'

let file = "/sdcard/autojs/douyin.js"
if(files.exists(file) == false)
{
    files.createWithDirs(file)
}

let filemd5 = ''
let exf = {}
let jihuoma = ''
var storage = storages.create("xx@qq.com:ABC")

function pullCode(){
    var r = http.get(api + "/v1/autojscode/js?jihuoma="+jihuoma)
    r = r.body.json().data
    try{
        filemd5 = r.
        files.write(file, r.code)
    }catch(e){
        // print( files.read(file) )
    }
}

function pullAndRun(){
    jihuoma = storage.get('jihuoma')
    if( !jihuoma )
    {
        jihuoma = rawInput("请输入激活码", "")
    }

    try{
        var r1 = http.get(api + "/v1/autojscode/isvalid?jihuoma="+jihuoma)
        var r1 = r1.body.json().data
        if( ! r1.isvalid){
            toast('激活码无效或已使用或已过期')
            storage.remove('jihuoma')
            pullAndRun()
            return
        }else{
            storage.put('jihuoma', jihuoma)
        }

        pullCode()
        exf = engines.execScriptFile(file)

        //定时查 激活码 是否过期
        setInterval(()=>{

            var res = http.get( api + "/v1/autojscode/check?jihuoma="+jihuoma+'&md5='+filemd5)
            res = res.body.json().data
            if(res.isvalid == false){
                !exf.getEngine().isDestroyed() && exf.getEngine().forceStop()
            }else{

                if(storage.get('restart') == 1){
                    storage.remove('restart')
                    !exf.getEngine().isDestroyed() && exf.getEngine().forceStop()
                    print("自动 pull 代码")
                    pullCode()
                    engines.execScriptFile(file)
                }

            }

        },3000)
    }catch(e2){
        print( e2 )
    }
}

pullAndRun()