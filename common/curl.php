<?php

class Curl
{
    public function curlPost($url, $data=array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    public function curlGet($url, $decode = true)
    {
        $ch = curl_init();
        $timeout = 10;
        $header = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: zh-CN,zh;q=0.9',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'Cookie: BIDUPSID=19821D4C920B694D55560B91CA043A8D; PSTM=1554689486; MCITY=-332%3A; BAIDUID=D8F317618B021FE91E1DD5B437B8BDED:FG=1; BDUSS=A1ZHRRREpIZ2Z2NHp1SzZ5VzZ4cGVESm5DODR-WFZWZGVmLVZaQnF4MkhGcDFlRVFBQUFBJCQAAAAAAAAAAAEAAAAhWzwAs6y8tsOx19PPt7eoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIeJdV6HiXVeeH; BDORZ=FFFB88E999055A3F8A630C64834BD6D0; BDRCVFR[hhEqlHuJUA_]=aeXf-1x8UdYcs; delPer=0; PSINO=2; H_PS_PSSID=1468_31121_21098_30904_31085_22159',
            'Host: opendata.baidu.com',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: none',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36',
        ];
        curl_setopt($ch, CURLOPT_URL, str_replace(' ', '', $url));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip"); //指定gzip压缩
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = curl_exec($ch);
        curl_close($ch);
        if ($decode) {
            $result = json_decode($result, true);
        }

        return $result;
    }
    
}
