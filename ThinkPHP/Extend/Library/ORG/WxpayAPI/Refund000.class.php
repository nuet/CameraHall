<?php

/**
 * 
 * 接口访问类，包含所有微信支付API列表的封装，类中方法为static方法，
 * 每个接口有默认超时时间（除提交被扫支付为10s，上报超时时间为1s外，其他均为6s）
 * @author widyhu
 *
 */
class WxPayConfig  extends  WxPayDataBase
{
    //=======【基本信息设置】=====================================
    //
    /**
     * TODO: 修改这里配置为您自己申请的商户信息
     * 微信公众号信息配置
     * 
     * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
     * 
     * MCHID：商户号（必须配置，开户邮件中可查看）
     * 
     * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
     * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
     * 
     * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
     * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
     * @var string
     */
    const APPID = 'wxe1585e2001d11e09';
    const MCHID = '1373995602';
    const KEY = 'zhxxhspringflowerszhaolq19910507';
    const APPSECRET = 'f76e5158a5108de0b0c60b3676bc25f3';
    
    //=======【证书路径设置】=====================================
    /**
     * TODO：设置商户证书路径
     * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
     * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
     * @var path
     */
    const SSLCERT_PATH = 'E:/wamp/www/WxpayAPI/cert/apiclient_cert.pem';
    const SSLKEY_PATH = 'E:/wamp/www/WxpayAPI/cert/apiclient_key.pem';
    
    //=======【curl代理设置】===================================
    /**
     * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     * @var unknown_type
     */
    const CURL_PROXY_HOST = "http://pbooks.tunnel.qydev.com";//"10.152.18.220";
    const CURL_PROXY_PORT = 0;//8080;
    
    //=======【上报信息配置】===================================
    /**
     * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
     * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
     * 开启错误上报。
     * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
     * @var int
     */
    const REPORT_LEVENL = 1;
}


class WxPayException extends Exception {
    public function errorMessage()
    {
        return $this->getMessage();
    }
}


class WxPayApi extends WxPayConfig
{

    /**
     * 
     * 申请退款，WxPayRefund中out_trade_no、transaction_id至少填一个且
     * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayRefund $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function refund($inputObj, $timeOut = 6)
    {
        echo "haha";
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
            throw new WxPayException("退款申请接口中，out_trade_no、transaction_id至少填一个！");
        }else if(!$inputObj->IsOut_refund_noSet()){
            throw new WxPayException("退款申请接口中，缺少必填参数out_refund_no！");
        }else if(!$inputObj->IsTotal_feeSet()){
            throw new WxPayException("退款申请接口中，缺少必填参数total_fee！");
        }else if(!$inputObj->IsRefund_feeSet()){
            throw new WxPayException("退款申请接口中，缺少必填参数refund_fee！");
        }else if(!$inputObj->IsOp_user_idSet()){
            throw new WxPayException("退款申请接口中，缺少必填参数op_user_id！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串
        
        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();
        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, true, $timeOut);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间
        
        return $result;
    }
    
    /**
     * 
     * 查询退款
     * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，
     * 用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
     * WxPayRefundQuery中out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayRefundQuery $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function refundQuery($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/refundquery";
        //检测必填参数
        if(!$inputObj->IsOut_refund_noSet() &&
            !$inputObj->IsOut_trade_noSet() &&
            !$inputObj->IsTransaction_idSet() &&
            !$inputObj->IsRefund_idSet()) {
            throw new WxPayException("退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串
        
        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();
        
        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间
        
        return $result;
    }
    

    
    /**
     * 
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public static function getNonceStr($length = 32) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        } 
        return $str;
    }
    
    /**
     * 直接输出xml
     * @param string $xml
     */
    public static function replyNotify($xml)
    {
        echo $xml;
    }
    
    /**
     * 
     * 上报数据， 上报的时候将屏蔽所有异常流程
     * @param string $usrl
     * @param int $startTimeStamp
     * @param array $data
     */
    private static function reportCostTime($url, $startTimeStamp, $data)
    {
        //如果不需要上报数据
        if(WxPayConfig::REPORT_LEVENL == 0){
            return;
        } 
        //如果仅失败上报
        if(WxPayConfig::REPORT_LEVENL == 1 &&
             array_key_exists("return_code", $data) &&
             $data["return_code"] == "SUCCESS" &&
             array_key_exists("result_code", $data) &&
             $data["result_code"] == "SUCCESS")
         {
            return;
         }
         
        //上报逻辑
        $endTimeStamp = self::getMillisecond();
        $objInput = new WxPayReport();
        $objInput->SetInterface_url($url);
        $objInput->SetExecute_time_($endTimeStamp - $startTimeStamp);
        //返回状态码
        if(array_key_exists("return_code", $data)){
            $objInput->SetReturn_code($data["return_code"]);
        }
        //返回信息
        if(array_key_exists("return_msg", $data)){
            $objInput->SetReturn_msg($data["return_msg"]);
        }
        //业务结果
        if(array_key_exists("result_code", $data)){
            $objInput->SetResult_code($data["result_code"]);
        }
        //错误代码
        if(array_key_exists("err_code", $data)){
            $objInput->SetErr_code($data["err_code"]);
        }
        //错误代码描述
        if(array_key_exists("err_code_des", $data)){
            $objInput->SetErr_code_des($data["err_code_des"]);
        }
        //商户订单号
        if(array_key_exists("out_trade_no", $data)){
            $objInput->SetOut_trade_no($data["out_trade_no"]);
        }
        //设备号
        if(array_key_exists("device_info", $data)){
            $objInput->SetDevice_info($data["device_info"]);
        }
        
        try{
            self::report($objInput);
        } catch (WxPayException $e){
            //不做任何处理
        }
    }

    /**
     * 以post方式提交xml到对应的接口url
     * 
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    private static function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {       
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        
        //如果有配置代理这里就设置代理
        if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0" 
            && WxPayConfig::CURL_PROXY_PORT != 0){
            curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件


            // curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            // curl_setopt($ch,CURLOPT_SSLCERT, 'http://pbooks.tunnel.qydev.com/WxpayAPI/cert/apiclient_cert.pem');
            // curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            // curl_setopt($ch,CURLOPT_SSLKEY, 'http://pbooks.tunnel.qydev.com/WxpayAPI/cert/apiclient_key.pem');
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }
    
    /**
     * 获取毫秒级别的时间戳
     */
    private static function getMillisecond()
    {
        //获取毫秒的时间戳
        $time = explode ( " ", microtime () );
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode( ".", $time );
        $time = $time2[0];
        return $time;
    }
}

/**
 * 
 * 数据对象基础类，该类中定义数据类最基本的行为，包括：
 * 计算/设置/获取签名、输出xml格式的参数、从xml读取数据对象等
 * @author widyhu
 *
 */
class WxPayDataBase  extends WxPayException
{
    protected $values = array();
    
    /**
    * 设置签名，详见签名生成算法
    * @param string $value 
    **/
    public function SetSign()
    {
        $sign = $this->MakeSign();
        $this->values['sign'] = $sign;
        return $sign;
    }
    
    /**
    * 获取签名，详见签名生成算法的值
    * @return 值
    **/
    public function GetSign()
    {
        return $this->values['sign'];
    }
    
    /**
    * 判断签名，详见签名生成算法是否存在
    * @return true 或 false
    **/
    public function IsSignSet()
    {
        return array_key_exists('sign', $this->values);
    }

    /**
     * 输出xml字符
     * @throws WxPayException
    **/
    public function ToXml()
    {
        if(!is_array($this->values) 
            || count($this->values) <= 0)
        {
            throw new WxPayException("数组数据异常！");
        }
        
        $xml = "<xml>";
        foreach ($this->values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
    }
    
    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {   
        if(!$xml){
            throw new WxPayException("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $this->values;
    }
    
    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        
        $buff = trim($buff, "&");
        return $buff;
    }
    
    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function MakeSign()
    {
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".WxPayConfig::KEY;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    
    /**
     * 获取设置的值
     */
    public function GetValues()
    {
        return $this->values;
    }
}

/**
 * 
 * 接口调用结果类
 * @author widyhu
 *
 */
class WxPayResults extends WxPayDataBase
{
    /**
     * 
     * 检测签名
     */
    public function CheckSign()
    {
        //fix异常
        if(!$this->IsSignSet()){
            throw new WxPayException("签名错误！");
        }
        
        $sign = $this->MakeSign();
        if($this->GetSign() == $sign){
            return true;
        }
        throw new WxPayException("签名错误！");
    }
    
    /**
     * 
     * 使用数组初始化
     * @param array $array
     */
    public function FromArray($array)
    {
        $this->values = $array;
    }
    
    /**
     * 
     * 使用数组初始化对象
     * @param array $array
     * @param 是否检测签名 $noCheckSign
     */
    public static function InitFromArray($array, $noCheckSign = false)
    {
        $obj = new self();
        $obj->FromArray($array);
        if($noCheckSign == false){
            $obj->CheckSign();
        }
        return $obj;
    }
    
    /**
     * 
     * 设置参数
     * @param string $key
     * @param string $value
     */
    public function SetData($key, $value)
    {
        $this->values[$key] = $value;
    }
    
    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public static function Init($xml)
    {   
        $obj = new self();
        $obj->FromXml($xml);
        //fix bug 2015-06-29
        if($obj->values['return_code'] != 'SUCCESS'){
             return $obj->GetValues();
        }
        $obj->CheckSign();
        return $obj->GetValues();
    }
}

/**
 * 
 * 回调基础类
 * @author widyhu
 *
 */
class WxPayNotifyReply extends  WxPayDataBase
{
    /**
     * 
     * 设置错误码 FAIL 或者 SUCCESS
     * @param string
     */
    public function SetReturn_code($return_code)
    {
        $this->values['return_code'] = $return_code;
    }
    
    /**
     * 
     * 获取错误码 FAIL 或者 SUCCESS
     * @return string $return_code
     */
    public function GetReturn_code()
    {
        return $this->values['return_code'];
    }

    /**
     * 
     * 设置错误信息
     * @param string $return_code
     */
    public function SetReturn_msg($return_msg)
    {
        $this->values['return_msg'] = $return_msg;
    }
    
    /**
     * 
     * 获取错误信息
     * @return string
     */
    public function GetReturn_msg()
    {
        return $this->values['return_msg'];
    }
    
    /**
     * 
     * 设置返回参数
     * @param string $key
     * @param string $value
     */
    public function SetData($key, $value)
    {
        $this->values[$key] = $value;
    }
}







/**
 * 
 * 提交退款输入对象
 * @author widyhu
 *
 */
class WxPayRefund extends WxPayDataBase
{
    /**
    * 设置微信分配的公众账号ID
    * @param string $value 
    **/
    public function SetAppid($value)
    {
        $this->values['appid'] = $value;
    }
    /**
    * 获取微信分配的公众账号ID的值
    * @return 值
    **/
    public function GetAppid()
    {
        return $this->values['appid'];
    }
    /**
    * 判断微信分配的公众账号ID是否存在
    * @return true 或 false
    **/
    public function IsAppidSet()
    {
        return array_key_exists('appid', $this->values);
    }


    /**
    * 设置微信支付分配的商户号
    * @param string $value 
    **/
    public function SetMch_id($value)
    {
        $this->values['mch_id'] = $value;
    }
    /**
    * 获取微信支付分配的商户号的值
    * @return 值
    **/
    public function GetMch_id()
    {
        return $this->values['mch_id'];
    }
    /**
    * 判断微信支付分配的商户号是否存在
    * @return true 或 false
    **/
    public function IsMch_idSet()
    {
        return array_key_exists('mch_id', $this->values);
    }


    /**
    * 设置微信支付分配的终端设备号，与下单一致
    * @param string $value 
    **/
    public function SetDevice_info($value)
    {
        $this->values['device_info'] = $value;
    }
    /**
    * 获取微信支付分配的终端设备号，与下单一致的值
    * @return 值
    **/
    public function GetDevice_info()
    {
        return $this->values['device_info'];
    }
    /**
    * 判断微信支付分配的终端设备号，与下单一致是否存在
    * @return true 或 false
    **/
    public function IsDevice_infoSet()
    {
        return array_key_exists('device_info', $this->values);
    }


    /**
    * 设置随机字符串，不长于32位。推荐随机数生成算法
    * @param string $value 
    **/
    public function SetNonce_str($value)
    {
        $this->values['nonce_str'] = $value;
    }
    /**
    * 获取随机字符串，不长于32位。推荐随机数生成算法的值
    * @return 值
    **/
    public function GetNonce_str()
    {
        return $this->values['nonce_str'];
    }
    /**
    * 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
    * @return true 或 false
    **/
    public function IsNonce_strSet()
    {
        return array_key_exists('nonce_str', $this->values);
    }

    /**
    * 设置微信订单号
    * @param string $value 
    **/
    public function SetTransaction_id($value)
    {
        $this->values['transaction_id'] = $value;
    }
    /**
    * 获取微信订单号的值
    * @return 值
    **/
    public function GetTransaction_id()
    {
        return $this->values['transaction_id'];
    }
    /**
    * 判断微信订单号是否存在
    * @return true 或 false
    **/
    public function IsTransaction_idSet()
    {
        return array_key_exists('transaction_id', $this->values);
    }


    /**
    * 设置商户系统内部的订单号,transaction_id、out_trade_no二选一，如果同时存在优先级：transaction_id> out_trade_no
    * @param string $value 
    **/
    public function SetOut_trade_no($value)
    {
        $this->values['out_trade_no'] = $value;
    }
    /**
    * 获取商户系统内部的订单号,transaction_id、out_trade_no二选一，如果同时存在优先级：transaction_id> out_trade_no的值
    * @return 值
    **/
    public function GetOut_trade_no()
    {
        return $this->values['out_trade_no'];
    }
    /**
    * 判断商户系统内部的订单号,transaction_id、out_trade_no二选一，如果同时存在优先级：transaction_id> out_trade_no是否存在
    * @return true 或 false
    **/
    public function IsOut_trade_noSet()
    {
        return array_key_exists('out_trade_no', $this->values);
    }


    /**
    * 设置商户系统内部的退款单号，商户系统内部唯一，同一退款单号多次请求只退一笔
    * @param string $value 
    **/
    public function SetOut_refund_no($value)
    {
        $this->values['out_refund_no'] = $value;
    }
    /**
    * 获取商户系统内部的退款单号，商户系统内部唯一，同一退款单号多次请求只退一笔的值
    * @return 值
    **/
    public function GetOut_refund_no()
    {
        return $this->values['out_refund_no'];
    }
    /**
    * 判断商户系统内部的退款单号，商户系统内部唯一，同一退款单号多次请求只退一笔是否存在
    * @return true 或 false
    **/
    public function IsOut_refund_noSet()
    {
        return array_key_exists('out_refund_no', $this->values);
    }


    /**
    * 设置订单总金额，单位为分，只能为整数，详见支付金额
    * @param string $value 
    **/
    public function SetTotal_fee($value)
    {
        $this->values['total_fee'] = $value;
    }
    /**
    * 获取订单总金额，单位为分，只能为整数，详见支付金额的值
    * @return 值
    **/
    public function GetTotal_fee()
    {
        return $this->values['total_fee'];
    }
    /**
    * 判断订单总金额，单位为分，只能为整数，详见支付金额是否存在
    * @return true 或 false
    **/
    public function IsTotal_feeSet()
    {
        return array_key_exists('total_fee', $this->values);
    }


    /**
    * 设置退款总金额，订单总金额，单位为分，只能为整数，详见支付金额
    * @param string $value 
    **/
    public function SetRefund_fee($value)
    {
        $this->values['refund_fee'] = $value;
    }
    /**
    * 获取退款总金额，订单总金额，单位为分，只能为整数，详见支付金额的值
    * @return 值
    **/
    public function GetRefund_fee()
    {
        return $this->values['refund_fee'];
    }
    /**
    * 判断退款总金额，订单总金额，单位为分，只能为整数，详见支付金额是否存在
    * @return true 或 false
    **/
    public function IsRefund_feeSet()
    {
        return array_key_exists('refund_fee', $this->values);
    }


    /**
    * 设置货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
    * @param string $value 
    **/
    public function SetRefund_fee_type($value)
    {
        $this->values['refund_fee_type'] = $value;
    }
    /**
    * 获取货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型的值
    * @return 值
    **/
    public function GetRefund_fee_type()
    {
        return $this->values['refund_fee_type'];
    }
    /**
    * 判断货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型是否存在
    * @return true 或 false
    **/
    public function IsRefund_fee_typeSet()
    {
        return array_key_exists('refund_fee_type', $this->values);
    }


    /**
    * 设置操作员帐号, 默认为商户号
    * @param string $value 
    **/
    public function SetOp_user_id($value)
    {
        $this->values['op_user_id'] = $value;
    }
    /**
    * 获取操作员帐号, 默认为商户号的值
    * @return 值
    **/
    public function GetOp_user_id()
    {
        return $this->values['op_user_id'];
    }
    /**
    * 判断操作员帐号, 默认为商户号是否存在
    * @return true 或 false
    **/
    public function IsOp_user_idSet()
    {
        return array_key_exists('op_user_id', $this->values);
    }
}

/**
 * 
 * 退款查询输入对象
 * @author widyhu
 *
 */
class WxPayRefundQuery extends WxPayDataBase
{
    /**
    * 设置微信分配的公众账号ID
    * @param string $value 
    **/
    public function SetAppid($value)
    {
        $this->values['appid'] = $value;
    }
    /**
    * 获取微信分配的公众账号ID的值
    * @return 值
    **/
    public function GetAppid()
    {
        return $this->values['appid'];
    }
    /**
    * 判断微信分配的公众账号ID是否存在
    * @return true 或 false
    **/
    public function IsAppidSet()
    {
        return array_key_exists('appid', $this->values);
    }


    /**
    * 设置微信支付分配的商户号
    * @param string $value 
    **/
    public function SetMch_id($value)
    {
        $this->values['mch_id'] = $value;
    }
    /**
    * 获取微信支付分配的商户号的值
    * @return 值
    **/
    public function GetMch_id()
    {
        return $this->values['mch_id'];
    }
    /**
    * 判断微信支付分配的商户号是否存在
    * @return true 或 false
    **/
    public function IsMch_idSet()
    {
        return array_key_exists('mch_id', $this->values);
    }


    /**
    * 设置微信支付分配的终端设备号
    * @param string $value 
    **/
    public function SetDevice_info($value)
    {
        $this->values['device_info'] = $value;
    }
    /**
    * 获取微信支付分配的终端设备号的值
    * @return 值
    **/
    public function GetDevice_info()
    {
        return $this->values['device_info'];
    }
    /**
    * 判断微信支付分配的终端设备号是否存在
    * @return true 或 false
    **/
    public function IsDevice_infoSet()
    {
        return array_key_exists('device_info', $this->values);
    }


    /**
    * 设置随机字符串，不长于32位。推荐随机数生成算法
    * @param string $value 
    **/
    public function SetNonce_str($value)
    {
        $this->values['nonce_str'] = $value;
    }
    /**
    * 获取随机字符串，不长于32位。推荐随机数生成算法的值
    * @return 值
    **/
    public function GetNonce_str()
    {
        return $this->values['nonce_str'];
    }
    /**
    * 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
    * @return true 或 false
    **/
    public function IsNonce_strSet()
    {
        return array_key_exists('nonce_str', $this->values);
    }

    /**
    * 设置微信订单号
    * @param string $value 
    **/
    public function SetTransaction_id($value)
    {
        $this->values['transaction_id'] = $value;
    }
    /**
    * 获取微信订单号的值
    * @return 值
    **/
    public function GetTransaction_id()
    {
        return $this->values['transaction_id'];
    }
    /**
    * 判断微信订单号是否存在
    * @return true 或 false
    **/
    public function IsTransaction_idSet()
    {
        return array_key_exists('transaction_id', $this->values);
    }


    /**
    * 设置商户系统内部的订单号
    * @param string $value 
    **/
    public function SetOut_trade_no($value)
    {
        $this->values['out_trade_no'] = $value;
    }
    /**
    * 获取商户系统内部的订单号的值
    * @return 值
    **/
    public function GetOut_trade_no()
    {
        return $this->values['out_trade_no'];
    }
    /**
    * 判断商户系统内部的订单号是否存在
    * @return true 或 false
    **/
    public function IsOut_trade_noSet()
    {
        return array_key_exists('out_trade_no', $this->values);
    }


    /**
    * 设置商户退款单号
    * @param string $value 
    **/
    public function SetOut_refund_no($value)
    {
        $this->values['out_refund_no'] = $value;
    }
    /**
    * 获取商户退款单号的值
    * @return 值
    **/
    public function GetOut_refund_no()
    {
        return $this->values['out_refund_no'];
    }
    /**
    * 判断商户退款单号是否存在
    * @return true 或 false
    **/
    public function IsOut_refund_noSet()
    {
        return array_key_exists('out_refund_no', $this->values);
    }


    /**
    * 设置微信退款单号refund_id、out_refund_no、out_trade_no、transaction_id四个参数必填一个，如果同时存在优先级为：refund_id>out_refund_no>transaction_id>out_trade_no
    * @param string $value 
    **/
    public function SetRefund_id($value)
    {
        $this->values['refund_id'] = $value;
    }
    /**
    * 获取微信退款单号refund_id、out_refund_no、out_trade_no、transaction_id四个参数必填一个，如果同时存在优先级为：refund_id>out_refund_no>transaction_id>out_trade_no的值
    * @return 值
    **/
    public function GetRefund_id()
    {
        return $this->values['refund_id'];
    }
    /**
    * 判断微信退款单号refund_id、out_refund_no、out_trade_no、transaction_id四个参数必填一个，如果同时存在优先级为：refund_id>out_refund_no>transaction_id>out_trade_no是否存在
    * @return true 或 false
    **/
    public function IsRefund_idSet()
    {
        return array_key_exists('refund_id', $this->values);
    }
}