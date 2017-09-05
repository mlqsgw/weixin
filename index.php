<?php
/**
 * wechat php test
 */
//define your token
header("Content-type: text/html; charset=utf-8");
define("TOKEN", "liuli");
$wechatObj = new wechatCallbackapiTest();//将11行的class类实例化  
//$wechatObj->valid();//使用-》访问类中valid方法，用来验证开发模式
$wechatObj->responseMsg();//使用-》访问类中responseMsg方法，用来微信自动回复
//11--23行代码为签名及接口验证。
class wechatCallbackapiTest
{
    public function valid()//验证接口的方法  
    {
        $echoStr = $_GET["echostr"];//从微信用户端获取一个随机字符赋予变量echostr  

        //valid signature , option访问地61行的checkSignature签名验证方法，如果签名一致，输出变量echostr，完整验证配置接口的操作  
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
    //公有的responseMsg的方法，是我们回复微信的关键。以后的章节修改代码就是修改这个。  
    public function responseMsg()
    {
        //get post data, May be due to the different environments  
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//将用户端放松的数据保存到变量postStr中，由于微信端发送的都是xml，使用postStr无法解析，故使用$GLOBALS["HTTP_RAW_POST_DATA"]获取  

        //extract post data如果用户端数据不为空，执行30-55否则56-58  
        if (!empty($postStr)){

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);//将postStr变量进行解析并赋予变量postObj。simplexml_load_string（）函数是php中一个解析XML的函数，SimpleXMLElement为新对象的类，LIBXML_NOCDATA表示将CDATA设置为文本节点，CDATA标签中的文本XML不进行解析
            $fromUsername = $postObj->FromUserName;//将微信用户端的用户名赋予变量FromUserName
            $toUsername = $postObj->ToUserName;//将你的微信公众账号ID赋予变量ToUserName
            $keyword = trim($postObj->Content);//将用户微信发来的文本内容去掉空格后赋予变量keyword
            //接收用户消息类型
            $msgType = $postObj->MsgType;
            //定义$logitude 与$latitude 接收用户发送的经纬度信息
            $latitude = $postObj->Location_X;
            $longitude = $postObj->Location_Y;
            $time = time();//将系统时间赋予变量time
            //构建XML格式的文本赋予变量textTpl，注意XML格式为微信内容固定格式，详见文档
            $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>  
                            <FromUserName><![CDATA[%s]]></FromUserName>  
                            <CreateTime>%s</CreateTime>  
                            <MsgType><![CDATA[%s]]></MsgType>  
                            <Content><![CDATA[%s]]></Content>  
                            <FuncFlag>0</FuncFlag>  
                            </xml>";
            //音乐发送模版
            $musicTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Music>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <MusicUrl><![CDATA[%s]]></MusicUrl>
                            <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                            </Music>
                        </xml>";
            //图文发送模版
            $newsTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <ArticleCount>%s</ArticleCount>
                            %s
                        </xml>";
            if ($msgType == 'text') {
                //判断用户发送关键词是否为空
                if(!empty( $keyword ))
                {
                    if($keyword == "文本"){
                        //设置回复类型为文本类型"text"
                        $msgType = "text";
                        //设置回复内容
                        $contentStr = "您发送的是文本消息";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);//将XML格式中的变量分别赋值。注意sprintf函数
                        //返回XML数据到客户端
                        echo $resultStr;
                    } elseif($keyword == "？" || $keyword == "?"){
                        //定义回复类型
                        $msgType = "text";
                        //回复内容
                        $contentStr = "【1】特种服务号码\n【2】通讯服务号码\n【3】银行服务号码\n您可以通过输入【】方括号获取内容哦！";
                        //格式化字符串
                        $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                        //返回数据到微信
                        echo $resultStr;
                    } elseif($keyword == "1"){
                        //定义回复类型
                        $msgType = "text";
                        //回复内容
                        $contentStr = "常用特殊服务号码：\n匪警：110\n火警：119";
                        //格式化字符串
                        $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                        //返回数据到微信
                        echo $resultStr;
                    } elseif($keyword == "2"){
                        //定义回复类型
                        $msgType = "text";
                        //回复内容
                        $contentStr = "常用通讯服务号码：\n中国移动：10086\n中国电信：10000";
                        //格式化字符串
                        $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                        //返回数据到微信
                        echo $resultStr;
                    } elseif ($keyword == "3") {
                        //定义回复类型
                        $msgType = "text";
                        //回复内容
                        $contentStr = "常用银行服务号码：\n工商银行：95588\n建设银行：95533";
                        //格式化字符串
                        $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                        //返回数据到微信
                        echo $resultStr;
                    } elseif ($keyword == "音乐"){
                        //定义回复类型
                        $msgType = "music";
                        //定义音乐标题
                        $title = "儿歌 - 嘻哈小子";
                        //定义音乐描述
                        $desc = "<嘻哈小子>原生大碟...";
                        //定义音乐链接
                        $url = 'http://www.luoliwuxie.com/weixin/music/music.mp3';
                        //定义高清音乐链接
                        $hqurl = 'http://www.luoliwuxie.com/weixin/music/music.mp3';
                        //格式化字符串
                        $resultStr = sprintf($musicTpl,$fromUsername,$toUsername,$time,$msgType,$title,$desc,$url,$hqurl);
                        //返回XML数据到微信客户端
                        echo $resultStr;
                    } elseif ($keyword == "图文"){
                        //设置回复类型
                        $msgType = "news";
                        //设置返回图文数量
                        $count = 4;
                        //设置要回复的图文数据
                        $str = '<Articles>';
                        for($i=1; $i<= $count; $i++){
                            $str .= "<item>
                                    <Title><![CDATA[图文标题{$i}]]></Title>
                                    <Description><![CDATA[微信图文回复开发]]></Description>
                                    <PicUrl><![CDATA[http://www.luoliwuxie.com/weixin/images/{$i}.jpg]]></PicUrl>
                                    <Url><![CDATA[http://www.luoliwuxie.com]]></Url>
                                    </item>";
                        }
                        $str .= '</Articles>';
                        //格式化字符串
                        $resultStr = sprintf($newsTpl,$fromUsername,$toUsername,$time,$msgType,$count,$str);
                        //返回XML数据到微信客户端
                        echo $resultStr;
                    }
                }else{
                    echo "Input something...";//不发送到微信端，只是测试使用
                }
            } elseif($msgType == 'image') {
                $msgType = "text";//回复文本信息类型为text型，变量类型为msgType
                $contentStr = "您发送的是图片消息";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);//将XML格式中的变量分别赋值。注意sprintf函数
                echo $resultStr;//输出回复信息，即发送微信
            } elseif($msgType == 'location'){
                //回复类型
                $msgType = 'text';
                //获取位置坐标
                $url = "http://api.map.baidu.com/geocoder/v2/?batch=false&location={$latitude},{$longitude}&output=json&pois=0&ak=UxjZsry2jxLGm84OXXK3Y2shtqNONUYM";
                //模拟http中get请求
                $str = file_get_contents($url);
                //转化json格式数据为数组或对象
                $str = json_decode($str,true);
                $address_one = $str['result']['formatted_address'];
                $address_two = $str['result']['sematic_description'];
                $address = $address_one.$address_two;
                //回复内容
                $contentStr = "您发送的是地理位置信息,您的位置：{$address}";
                //格式化字符串
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                //返回xml数据到微信客户端
                echo $resultStr;
            }

        }else {
            echo "";//回复为空，无意义，调试用  
            exit;
        }
    }
    //签名验证程序    ，checkSignature被18行调用。官方加密、校验流程：将token，timestamp，nonce这三个参数进行字典序排序，然后将这三个参数字符串拼接成一个字符串惊喜shal加密，开发者获得加密后的字符串可以与signature对比，表示该请求来源于微信。  
    private function checkSignature()
    {
        $signature = $_GET["signature"];//从用户端获取签名赋予变量signature  
        $timestamp = $_GET["timestamp"];//从用户端获取时间戳赋予变量timestamp  
        $nonce = $_GET["nonce"];    //从用户端获取随机数赋予变量nonce  

        $token = TOKEN;//将常量token赋予变量token  
        $tmpArr = array($token, $timestamp, $nonce);//简历数组变量tmpArr  
        sort($tmpArr, SORT_STRING);//新建排序  
        $tmpStr = implode( $tmpArr );//字典排序  
        $tmpStr = sha1( $tmpStr );//shal加密  
        //tmpStr与signature值相同，返回真，否则返回假  
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

?>  
