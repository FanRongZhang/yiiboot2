
let api = 'http://192.168.0.125/api'

let file = "/sdcard/autojs/douyin.js"
if(files.exists(file) == false)
{
    files.createWithDirs(file)
}

let filemd5 = ''
let exf = {}

function pullCode(){
    var r2 = http.get(api + "/v1/autojscode/js?jihuoma="+jihuoma)

    try{
        files.write(file, r2.body.string())
    }catch(e){
        // print( files.read(file) )
    }
}

function pullAndRun(){
    var jihuoma = rawInput("请输入激活码", "")
    try{

        var r1 = http.get(api + "/v1/autojscode/isvalid?jihuoma="+jihuoma)
        var r1 = r1.body.json().data
        if(r1.isvalid == false){
            toast('激活码无效或已使用或已过期')
            return
        }

        pullCode()
        exf = engines.execScriptFile(file)

        //定时查 激活码 是否过期
        setInterval(()=>{

            var res = http.get( api + "/v1/autojscode/check?jihuoma="+jihuoma)
            res = res.body.json().data
            if(res.isvalid == false){
                !exf.getEngine().isDestroyed() && exf.getEngine().forceStop()
            }else{
                if(!filemd5){
                    filemd5 = res.md5
                }
                if(filemd5 != res.md5){
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