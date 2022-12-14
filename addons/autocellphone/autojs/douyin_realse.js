auto()

let api = 'http://jd.xiaozhumeimeigou.com/api'

let file = "/sdcard/autojs/douyin.js"
if(files.exists(file) == false)
{
    files.createWithDirs(file)
}

var storage = storages.create("xx@qq.com:ABC")
let filemd5 = ''
let exf = {}
let user_id =  storage.get('user_id')
let jihuoma =  storage.get('jihuoma')

function pullCode(){
    var r = http.get(api + "/v1/autojscode/js?jihuoma="+jihuoma+'&user_id='+user_id)
    r = r.body.json().data
    
    if(r.msg){
        toast(r.msg)
        return false
    }else{
        filemd5 = r.md5
        try{
            files.write(file, r.code)
            return true
        }catch(e){}
    }
}

function appmsg(msg){
    toast(msg)
    print(msg)
}

function pullAndRun(){
    if( !user_id || (user_id && !confirm('是否使用上次保存下的用户ID')) )
    {
        user_id = rawInput("请输入用户ID，在个人中心查看", "")
        if(!user_id || Number(user_id) != user_id){
            return
        }
        storage.put('user_id', user_id) //保存起来
    }else{
        appmsg("用户ID"+user_id)
    }

    if( !jihuoma  || (jihuoma && !confirm('是否使用上次保存下的激活码')) )
    {
        jihuoma = rawInput("请输入激活码", "")
        if(!jihuoma || jihuoma.length < 3){
            return
        }
        storage.put('jihuoma', jihuoma) //保存起来
    }else{
        appmsg("激活码:"+jihuoma)
    }

    try{
        if( ! pullCode() ){
            pullAndRun()
            return
        }
        
        exf = engines.execScriptFile(file)

        //定时查 激活码 是否过期
        setInterval(()=>{
            if(!jihuoma || jihuoma.length < 3)return

            var res = http.get( api + "/v1/autojscode/check?jihuoma="+jihuoma+'&md5='+filemd5+'&user_id='+user_id)
            res = res.body.json().data
            if( ! res.isvalid){
                appmsg('检查出激活码已无效')
                !exf.getEngine().isDestroyed() && exf.getEngine().forceStop()
            }else{

                //看是否有 更新代码
                if(res.upgrade_code){
                    appmsg('检查出有更新代码指令')
                    !exf.getEngine().isDestroyed() && exf.getEngine().forceStop()
                    pullCode() && engines.execScriptFile(file)
                }

            }

        },3000)
    }catch(e2){
        print( e2 )
    }
}

pullAndRun()