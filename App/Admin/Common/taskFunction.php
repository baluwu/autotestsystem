<?PHP

function tasklog($msg, $lv = 'INFO') { 
    error_log(
        '[' . date('H:i:s') . '] ' . $lv . ' ' . $msg . PHP_EOL, 3, 
        ABS_ROOT . '/App/Runtime/Logs/swoole_' . date('Y-m-d') . '.log'
    );
}

/**
 * 新增任务
 * @param int $taskData 任务数据
 * @param int $type 0 用例  1 用例组
 * @desc 数据必须包含  id
 */
function AddTask($taskData = []) {

    $client = new \swoole_client(SWOOLE_SOCK_TCP);
    if ($client->connect('127.0.0.1', C("SWOOLE_PORT"))) {
        $client->send(json_encode($taskData, true));
        $data = $client->recv(65535, 1);
        tasklog('客户端收到数据:' . $data . ',errorcode=' . $client->errCode . ',' . $client->errorNo);
        return true;
    }

    return false;
}

/**
 * 定时任务
 * @desc 如：每天12点执行
 */
function cronTask($task_id, $time) {}

/**
 * 计划任务
 * @desc 如：2016-07-12 12:20:22 执行
 */
function scheduleTask($task_id, $time) {

}

/**
 * 及时任务
 * @desc 立即执行任务
 */
function timelyTask($task_id) {
    tasking($task_id);
}

//单条条件判定
//[{"v1":"aaa","dept":"\u5305\u542b","v2":"ttt"},{"v1":"bbb","dept":"\u5305\u542b","v2":"yyydsff"},{"v1":"ccc","dept":"\u5305\u542b","v2":"fgfd"}]
function judged_all($data = [], $validates) {
    $_isPass = true;
    $validates = unserialize($validates);
    \Think\Log::write('任务成功 数据：' . json_encode($data, JSON_UNESCAPED_UNICODE), 'info');
    \Think\Log::write('任务成功 条件：' . json_encode($validates, JSON_UNESCAPED_UNICODE), 'info');
    print_r($data);
//  [{"v1":"qq","dept":"\u5305\u542b","v2":"ee"},{"v1":"bb","dept":"\u5927\u4e8e","v2":"tt"},{"v1":"tt","dept":"\u5927\u4e8e","v2":"yy"}]
    foreach ($validates as $k => $validate) {
        if (!$_isPass) return false;
        \Think\Log::write('任务成功 条件' . $k . ':' . $validate['v1'], 'info');

        $v = explode('.', $validate['v1']);
        $_data = $data;
        for ($x = 0; $x < count($v); $x++) {
            if (!isset($_data[$v[$x]])) {
                $_isPass = false;
                return false;
            }
            \Think\Log::write('任务成功 条件 --- $v[$x] ---  ' . $v[$x] . ' ：' . json_encode($_data[$v[$x]], JSON_UNESCAPED_UNICODE), 'info');
            if ($x == count($v) - 1) {
                if (!judged($_data[$v[$x]], $validate)) {
                    $_isPass = false;
                    return false;
                };
            }
            $_data = $_data[$v[$x]];
        }
    }
    return $_isPass;
}

//单条条件判定
//{"v1":"aaa","dept":"\u5305\u542b","v2":"ttt"}
function judged($data, $validate) {
    \Think\Log::write('判定数据 数据：' . $data . ' 条件：' . json_encode($validate, JSON_UNESCAPED_UNICODE), 'info');
    \Think\Log::write('判定数据 类型：' . $validate['dept'], 'info');
    $res = true;
    switch ($validate['dept']) {
        case '包含':
            $res = strpos($data, $validate['v2']) !== false;
            break;
        case '不包含':
            $res = strpos($data, $validate['v2']) === false;
            break;
        case '大于':
            $res = intval($data) > intval($validate['v2']);
            break;
        case '小于':
            $res = intval($data) < intval($validate['v2']);
            break;
        case '等于':
            $res = ($data == $validate['v2']);
            break;
        case '不等于':
            $res = ($data != $validate['v2']);
            break;

        case '字符大于':
            $res = strlen($data) > strlen($validate['v2']);
            break;
        case '字符小于':
            $res = strlen($data) < strlen($validate['v2']);
            break;
        
//        case '等于不严谨':
//            $res = ($data == $validate['v2']);
//            break;
//        case '不等于不严谨':
//            $res = ($data != $validate['v2']);
//            break;
    }
    \Think\Log::write('判定数据 结果：' . ($res ? "成功" : "失败"), 'info');
    return $res;
}

/**
 * 任务运行
 * @desc 任务运行
 */
function tasking($task_id) {

}

/**
 * 同步任务，需要返回执行内容
 * @param int $taskData 任务数据
 * @param int $type 0 用例  1 用例组
 * @desc 数据必须包含  id
 * @author chengbin
 */
function SyncTask($taskData = []) {

    $client = new \swoole_client(SWOOLE_SOCK_TCP);
    if ($client->connect('127.0.0.1', C("SWOOLE_PORT"), 30)) {
        $client->send(@json_encode($taskData, true));
		$info = $client->recv();
        tasklog('客户端任务返回数据:' . $info);

        $client->close();
        return $info;
    }

    return false;
}

function sendRequest($url, $params, $timeOut=10) {
    $postFields = is_array($params) ? http_build_query($params) : $params;

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_FAILONERROR, false );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
    curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeOut );

    $response = curl_exec ( $ch );
    if (curl_errno ( $ch )) {
        $curl_error = curl_error ( $ch );
        return false;
    } else {
        $httpStatusCode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
        if (200 !== $httpStatusCode) {
            return false;
        }
    }
    curl_close ( $ch );

    return $response;
}

/*
function sendRequest($url, $params, $timeOut = 10) {
    $data = http_build_query($params);  
    $opts = array(  
        'http'=>array(  
            'method'=>"POST",  
            'header'=>"Content-type: application/x-www-form-urlencoded\r\n".  
            "Content-length:".strlen($data)."\r\n" .   
            "\r\n",  
            'content' => $data,  
        )  
    );  
    $cxContext = stream_context_create($opts);  
    $sFile = file_get_contents($url, false, $cxContext);

    return $cxContext;
}
*/
