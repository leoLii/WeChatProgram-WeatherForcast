<?php
/**
  * wechat php test
  */

//define your token
//define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
$wechatObj->responseMsg();
class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
          
          $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
          $fromUsername = $postObj->FromUserName;
          $toUsername = $postObj->ToUserName;
          $type = $postObj->MsgType;
          $customrevent = $postObj->Event;
          $latitude  = $postObj->Location_X;
          $longitude = $postObj->Location_Y;
          $keyword = trim($postObj->Content);
          $time = time();
          $textTpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[%s]]></MsgType>
                      <Content><![CDATA[%s]]></Content>
                      <FuncFlag>0</FuncFlag>
                      </xml>";             
          switch ($type)
          {   
            case "event";
              if ($customrevent=="subscribe")
              {$contentStr = "嗨~\n回复1摸摸头\n回复2捏捏脸\n回复3举高高\n发送城市名获得天气预报\n";}
              break;
              
            case "image";
              $contentStr = "你的图片很棒！";
              break;
              
            case "location";
              $weatherurl="http://api.map.baidu.com/telematics/v2/weather?location={$longitude},{$latitude}&ak=1a3cde429f38434f1811a75e1a90310c";                 
              $apistr=file_get_contents($weatherurl);
              $apiobj=simplexml_load_string($apistr);
              $placeobj=$apiobj->currentCity;//读取城市
              $todayobj=$apiobj->results->result[0]->date;//读取星期
              $weatherobj=$apiobj->results->result[0]->weather;//读取天气
              $windobj=$apiobj->results->result[0]->wind;//读取风力
              $temobj=$apiobj->results->result[0]->temperature;//读取温度
              $contentStr = "{$placeobj}\n"."($longitude".","."$latitude)"."\n{$todayobj}\n天气{$weatherobj}\n风力{$windobj}\n温度{$temobj}";
              break;
              
            case "link" ;
              $contentStr = "你的链接有病毒吧！";
              break;
              
            case "text";   
              switch ($keyword)
              {
                case "1";
                  $contentStr = "摸摸头~";
                  break;
                case "2";
                  $contentStr = "捏捏脸~";
                  break;
                case "3";
                  $contentStr = "举高高~";
                  break;
                default;
                  $weatherurl="http://api.map.baidu.com/telematics/v2/weather?location={$keyword}&ak=1a3cde429f38434f1811a75e1a90310c";
                  $apistr=file_get_contents($weatherurl);
                  $apiobj=simplexml_load_string($apistr);
          		  $placeobj=$apiobj->currentCity;//读取城市
                  $todayobj=$apiobj->results->result[0]->date;//读取星期
                  $weatherobj=$apiobj->results->result[0]->weather;//读取天气
              	  $windobj=$apiobj->results->result[0]->wind;//读取风力
                  $temobj=$apiobj->results->result[0]->temperature;//读取温度
                  $contentStr = "{$placeobj}\n{$todayobj}\n天气{$weatherobj}\n风力{$windobj}\n温度{$temobj}";
                  break;
              }
              break;    
              
            default;
              $contentStr ="此项功能尚未开发";   
            }
          
          $msgType="text";
          $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
          echo $resultStr;

        }
      else {
        echo "";
        exit;
      }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];    

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

?>