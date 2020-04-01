<?php

class Ip {
    function index()
    {
    	//国内 返回true
        $ip =$this->getIp();
        return in_array($ip, array('unknown', '127.0.0.1')) ? false : $this->judgeIpByTaobao($ip);
    }

    function getIp()
    {
        if ($_SERVER["HTTP_CLIENT_IP"] && strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown")) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            if ($_SERVER["HTTP_X_FORWARDED_FOR"] && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown")) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else {
                if ($_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) {
                    $ip = $_SERVER["REMOTE_ADDR"];
                } else {
                    if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],
                            "unknown")
                    ) {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    } else {
                        $ip = "unknown";
                    }
                }
            }
        }

        return $ip;
    }

    public function judgeIpByTaobao($ip)
    {   
        $url = "http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
        $res = file_get_contents($url);
        if (!empty($res)) {
            $ipData = json_decode($res,true);
            if ($ipData['code']==0 && in_array($ipData['data']['country_id'],['CN','HK','TW'])) {
              return true;
            }
        }
        return false;
    }
}
