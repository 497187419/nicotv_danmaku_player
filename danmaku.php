<?php

// 支持跨域
header("Access-Control-Allow-Origin:*");
// header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');

// 定义时区
date_default_timezone_set('PRC');

// 如果没传参
if(!array_key_exists('module', $_GET))
{
    echo '???';
    die;
}

// 解析请求的功能
switch ($_GET['module'])
{
    // 初始化弹幕池（只为拦截提示弹幕加载失败的消息）
    case 'init':
        header('Content-Type:application/json');
        echo '{"code":0,"data":[]}';
        break;
    // 发来独立弹幕
    case 'senddanmu':
        // 如果有提交弹幕
        if(array_key_exists('color', $_POST) && array_key_exists('num', $_POST) && array_key_exists('text', $_POST) && array_key_exists('time', $_POST) && array_key_exists('title', $_POST))
        {
            marklog(json_encode($_POST, JSON_UNESCAPED_UNICODE),'debug');
            // 录入到指定番剧+集数的数据库
            savaDanmuInBangumi($_POST, get_ip());
            header('Content-Type:application/json');
            echo '{"code":0,"data":[]}';
        }
        break;
    // 如果是搜索请求
    case 'searchdanmu':
        // 如果传来标题和集数
        if(array_key_exists('title', $_GET) && $_GET['title'] && array_key_exists('num', $_GET) && $_GET['num'])
        {
            marklog('搜索请求','module');
            marklog(json_encode([$_GET['title'], $_GET['num']], JSON_UNESCAPED_UNICODE), 'param');

            // 搜索B站相关视频，获取弹幕
            $b_danmu = getDanmuOnName($_GET['title'], $_GET['num']);

            // 获取独立弹幕
            $extra_danmu = getSelfDanmu((getBangumiOnName($_GET['title'], $_GET['num'])?getBangumiOnName($_GET['title'], $_GET['num'])['id']:''));

            header('Content-Type:application/json');
            // 如果b站弹幕存在，独立弹幕不存在
            if($b_danmu && count(json_decode($b_danmu, TRUE)['data']) > 0 && !$extra_danmu)
            {
                echo $b_danmu;
            }
            // 如果b站弹幕和独立弹幕都存在
            if($b_danmu && count(json_decode($b_danmu, TRUE)['data']) > 0 && $extra_danmu)
            {
                // 以补充方式追加
                echo substr($b_danmu, 0, strlen($b_danmu)-2) . ',' . $extra_danmu . ']}';
            }
            // 如果B站无弹幕，独立弹幕存在，直接返回独立弹幕
            else if($extra_danmu)
            {
                echo '{"code":0,"data":['.$extra_danmu.']}';
            }
            else
            {
                echo '';
            }
        }
        break;
    // 如果是地址解析？？？
    case 'getdanmu':
        marklog('地址解析','module');
        if(array_key_exists('url', $_GET))
        {
            $url = $_GET['url'];
            marklog($url, 'param');
            // 如果含有/ep，说明是番剧
            if(strpos($url, 'play/ep') > 0)
            {
                $epid = getEpidOnUrl($url);
                echo getDanmuOnCid(getCidOnEpid($epid));
            }else{
                echo '???';
            }
        }
        break;
    // 如果是用户提交调整请求
    case 'adjust':
        if(array_key_exists('action_id', $_POST) && array_key_exists('value', $_POST) && array_key_exists('name', $_POST) && array_key_exists('num', $_POST))
        {
            // 初始化数据
            $_POST['value'] = trim($_POST['value']);
            $_POST['name'] = trim($_POST['name']);
            $_POST['num'] = trim($_POST['num']);

            // 判断请求操作的功能
            switch ($_POST['action_id'])
            {
                // 调整视频名称
                case '1':
                    addBangumiName($_POST['name'], $_POST['value'], $_POST['num']);
                    callback(0, '', '');
                    break;
                // 调整集数名称
                case '2':
                    addBangumiKeyIndex($_POST['name'], $_POST['num'], $_POST['value']);
                    callback(0, '', '');
                    break;
                // 调整对应视频地址
                case '3':
                    addUrlOnName($_POST['name'], $_POST['value'], $_POST['num']);
                    callback(0, '', '');
                    break;
                // 提交意见或建议
                case '99':
                    addGuestsVoice($_POST['name'], $_POST['num'], $_POST['value']);
                    callback(0, '', '');
                    break;
                // default:
                //  # code...
                //  break;
            }
        }
        callback(0, '', '');
        break;
    default:
        echo '???';
        break;
}


/*——————————————————————————————— 通用方法 ———————————————————————————————*/
// 从地址获取epid
function getEpidOnUrl($url)
{
    marklog('以地址提取epid', 'log');
    $url = explode('/ep', $url)[1];
    marklog($url, 'epid');
    return $url;
}

// 从(番剧)epid获取cid
function getCidOnEpid($id)
{
    marklog('以epid获取cid', 'log');
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.bilibili.com/pgc/view/web/season?ep_id='.$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        //CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'authority: api.bilibili.com',
            'pragma: no-cache',
            'cache-control: no-cache',
            'accept: application/json, text/plain, */*',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
            'dnt: 1',
            'origin: https://www.bilibili.com',
            'sec-fetch-site: same-site',
            'sec-fetch-mode: cors',
            'sec-fetch-dest: empty',
            'referer: https://api.bilibili.com/pgc/view/web/season?ep_id='.$id,
            'accept-language: zh-CN,zh;q=0.9',
            'cookie: bfe_id=5db70a86bd1cbe8a88817507134f7bb5'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response, true)['result']['episodes'];
    foreach ($response as $key => $value)
    {
        if($value['id'] == $id)
        {
            marklog($value['cid'], 'cid');
            return $value['cid'];
            break;
        }
    }
}

// 以cid获取xml弹幕
function getDanmuOnCid($id)
{
    marklog('以epid获取xml弹幕', 'log');
    // 如果已录入，且1小时前修改过，直接读取
    if(file_exists('./json/' . $id . '.json') && time()-filemtime('./json/' . $id . '.json') > 3600)
    {
        marklog('1小时前已存在弹幕，直接使用', 'log');
        header('Content-Type:application/json');
        return file_get_contents('./json/' . $id . '.json');
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://comment.bilibili.com/'.$id.'.xml',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        //CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    // 拆解成json弹幕
    $preg = "/<d p=\"(.*?)\">(.*?)<\/d>/";
    preg_match_all($preg, $response, $arr);
    $res = [];
    foreach ($arr[1] as $key => $value) {
        $arr_1 = explode(',', $value);
        $res[] = [
            $arr_1[0],
            (($arr_1[1]==1) ? 0 : 1),
            (int)$arr_1[3],
            $arr_1[6],
            $arr[2][$key]
        ];
    }
    
    $res = json_encode(['code'=>0,'data'=>$res], JSON_UNESCAPED_UNICODE);
    file_put_contents('./json/' . $id . '.json', $res);
    header('Content-Type:application/json');
    return $res;
}

// 以番剧名称请求B站搜索接口
function searchBilibiliOnName($keyword)
{
    marklog('以番剧名称搜索B站', 'log');
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.bilibili.com/x/web-interface/search/type?context=&search_type=media_bangumi&page=1&order=&keyword='.urlencode($keyword).'&category_id=&__refresh__=true&_extra=&highlight=1&single_column=0',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        //CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'authority: api.bilibili.com',
            'pragma: no-cache',
            'cache-control: no-cache',
            'accept: application/json, text/plain, */*',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
            'dnt: 1',
            'origin: https://search.bilibili.com',
            'sec-fetch-site: same-site',
            'sec-fetch-mode: cors',
            'sec-fetch-dest: empty',
            'referer: https://search.bilibili.com/all?keyword='.urlencode($keyword).'&from_source=web_search',
            'accept-language: zh-CN,zh;q=0.9',
            'cookie: CURRENT_FNVAL=80; blackside_state=1; sid=ivrfex5t; rpdid=|(umR)|lklmu0J\'uYk~||u~~Y; bfe_id=5db70a86bd1cbe8a88817507134f7bb5'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    if(!$response) return false;
    return $response;
}

// 以名称+集数搜索并输出弹幕
function getDanmuOnName($keyword, $index_title)
{
    $keyword = trim($keyword);
    $index_title = trim($index_title);
    marklog('以集数名称匹配并输出弹幕', 'log');
    
    // 以名称搜索B站，获取搜索结果
    $response = searchBilibiliOnName($keyword);
    // 拦截为空
    if(!$response) return '';

    // 转数组
    $response = json_decode($response, TRUE);

    // 如果B站搜索不到，可能名称不对或无版权或仅供港澳台
    if(!array_key_exists('result' ,$response['data']))
    {
        marklog('B站搜索不到', 'log');
        // 再尝试获取用户提交的关联地址
        $info = getBangumiOnName($keyword, $index_title);
        if($info && array_key_exists('url', $info) && strlen($info['url']) > 5)
        {
            marklog('尝试查用户提交关联地址', 'log');
            // 从地址拆解epid，换取cid，获取弹幕
            return getDanmuOnCid(getCidOnEpid(getEpidOnUrl($info['url'])));
        }
        // 如果都没有，直接为空
        else
        {
            marklog('数据不存在或用户没提交关联地址', 'log');
            return '';
        }

        // 以番剧ID获取用户提交的最新最多次的辅助名称
        $new_name = getAuxiliaryNameOnId($list['id']);
        if($new_name && array_key_exists('name', $new_name) && $new_name['name'])
        {
            // 以名称搜索B站，获取搜索结果
            $response = searchBilibiliOnName($new_name['name']);
            // 拦截为空
            if(!$response) return '';
            // 转数组
            $response = json_decode($response, TRUE);
            // 如果还是没有，返回空
            if(!array_key_exists('result' ,$response['data'])) return '';
        }
    }

    // 如果B站番剧能搜索出来，获取集数分组
    $data = $response['data']['result'][0]['eps'];
    // 尝试匹配集数
    foreach ($data as $key => $value)
    {
        // 如果找到对应cid，直接请求获取输出json弹幕
        if($value['index_title'] == $index_title)
        {
            return getDanmuOnCid(getCidOnEpid($value['id']));
            break;
        }
    }

    // 如果找不到对应集数，查数据库集数对照表
    $index_title = getAdjustKey($keyword, $index_title);
    // 如果数据库都没人提交，返回无
    if(!$index_title) return '';

    // 如果有用户提交，重新尝试匹配
    foreach ($data as $key => $value)
    {
        // 如果找到对应cid，直接请求获取输出json弹幕
        if($value['index_title'] == trim($index_title))
        {
            return getDanmuOnCid(getCidOnEpid($value['id']));
            break;
        }
    }

    // 如果还是没匹配到，返回无
    return '';
}

// 记录日志
function marklog($message, $mark='')
{
    $time = time();
    error_log('---------------------------------------------------------------' . "\n" . '[ ' . date("Y-m-d H:i:s", $time) . ' ]' . "\n" . ($mark ? ('[ ' . $mark . ' ]') : '' ) . $message . "\n", 3, './log/log_' . date("Ymd", $time) . '.txt');
}

// 数据库配置
function dbConfig($key)
{
    $config = [
        'dbms'=>'mysql',
        'host'=>'localhost',
        'dbname'=>'danmu',
        'user'=>'11a1ea8ebf8d4f7b',
        'pass'=>'c9e72ecb87428fe2',
    ];
    return $config[$key];
}

// 以番剧名称+集数搜索番剧详情
function getBangumiOnName($name, $num)
{
    try
    {
        $pdo = new PDO('mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname') . ';charset=utf8', dbConfig('user'), dbConfig('pass'));
    }
    catch(PDOException $e)
    {
        die('数据库连接失败 原因是：' . $e->getMessage());
    }

    // 查一次番剧名称是否存在
    $sql = "select id,name from lk_bangumi where name=? and num=? AND is_enable=1 AND is_show=1 AND is_checked=1 limit 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $name);
    $stmt->bindValue(2, $num);
    $stmt->execute();
    $list = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$list)
    {
        marklog('找不到'.$name.$num.'，直接创建', 'log');
        // 创建
        $id = addBangumi($name, $num);
        $list = ['id'=>$id, 'name'=>$name];
    }

    marklog('找到番剧尝试追加url', 'log');

    // 获取用户提交的调整地址
    $sql = "select url from lk_bangumi_url where bangumi_id=? order by id DESC limit 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $list['id']);
    $stmt->execute();
    $url = $stmt->fetch(PDO::FETCH_ASSOC);
    $list['url'] = $url ? $url['url'] : '';
    return $list;
}

/**
 *  新增集数对照数据
 *  @access public
 *  @param string $name 番剧名称
 *  @param string $old 原集数名称
 *  @param string $new 新集数名称
 *  @return string
 *  @throws Exception
 *  @author ld
 *  @copyright 2021
 */
function addBangumiKeyIndex($name, $old, $new)
{
    // 初始化参数
    $name = trim($name);
    $old = trim($old);
    $new = trim($new);

    // 拦截相同集数名称
    if($old == $new) return '';

    // 查一次番剧名称是否存在
    $list = getBangumiOnName($name, $old);

    try
    {
        $pdo = new PDO('mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname') . ';charset=utf8', dbConfig('user'), dbConfig('pass'));
    }
    catch(PDOException $e)
    {
        die('数据库连接失败 原因是：' . $e->getMessage());
    }

    // 如果番剧名称存在
    if($list && array_key_exists('name', $list))
    {
        // 集数对照表新增数据（就算有重复，使用时直接查最新一条或group by出现条目数最多的一条）
        $sql = "insert into lk_bangumi_key_index values(null,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $list['id']);
        $stmt->bindValue(2, $new);
        $stmt->bindValue(3, 1);
        $stmt->bindValue(4, 1);
        $stmt->bindValue(5, 1);
        $stmt->execute();
    }
    // 不存在则新增两个数据
    else
    {
        // 番剧表新增数据
        $sql = "insert into lk_bangumi values(null,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $old);
        $stmt->bindValue(3, 1);
        $stmt->bindValue(4, 1);
        $stmt->bindValue(5, 1);
        $stmt->execute();
        // 得到自增id
        $id = $pdo->lastInsertId();

        // 集数对照表新增数据
        $sql = "insert into lk_bangumi_key_index values(null,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $new);
        $stmt->bindValue(3, 1);
        $stmt->bindValue(4, 1);
        $stmt->bindValue(5, 1);
        $stmt->execute();
    }

    return true;
}

// 以番剧名称+集数搜索调整的集数名称
function getAdjustKey($name, $key)
{
    try
    {
        $pdo = new PDO('mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname') . ';charset=utf8', dbConfig('user'), dbConfig('pass'));
    }
    catch(PDOException $e)
    {
        die('数据库连接失败 原因是：' . $e->getMessage());
    }

    $sql = "select b.new_key from lk_bangumi a INNER JOIN lk_bangumi_key_index b ON a.id=b.bangumi_id where a.name=? AND a.num=? GROUP BY b.new_key ORDER BY count(b.id) DESC,b.id DESC limit 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $name);
    $stmt->bindValue(2, $key);
    $stmt->execute();
    $list = $stmt->fetch(PDO::FETCH_ASSOC);
    // 拦截无数据
    if(!$list) return false;

    return $list['new_key'];
}

// 返回通用json格式回调
function callback($code, $msg, $res)
{
    header('Content-Type:application/json;Charset=utf-8');
    echo json_encode([
        'code'=>$code,
        'msg'=>$msg,
        '$res'=>$res
    ], JSON_UNESCAPED_UNICODE);
    die;
}

// 调整视频名称
function addBangumiName($old_name, $new_name, $num)
{
    // 查一次番剧名称是否存在
    $list = getBangumiOnName($old_name, $num);

    try
    {
        $pdo = new PDO('mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname') . ';charset=utf8', dbConfig('user'), dbConfig('pass'));
    }
    catch(PDOException $e)
    {
        die('数据库连接失败 原因是：' . $e->getMessage());
    }

    // 如果番剧名称存在
    if($list && array_key_exists('name', $list))
    {
        // 番剧名称映射表新增数据（就算有重复，使用时直接查最新一条或group by出现条目数最多的一条）
        $sql = "insert into lk_bangumi_name_map values(null,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $list['id']);
        $stmt->bindValue(2, $new_name);
        $stmt->bindValue(3, 1);
        $stmt->bindValue(4, 1);
        $stmt->bindValue(5, 1);
        $stmt->execute();
    }
    // 不存在则新增两个数据
    else
    {
        // 番剧表新增数据
        $sql = "insert into lk_bangumi values(null,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $num);
        $stmt->bindValue(3, 1);
        $stmt->bindValue(4, 1);
        $stmt->bindValue(5, 1);
        $stmt->execute();
        // 得到自增id
        $id = $pdo->lastInsertId();

        // 番剧名称映射表新增数据
        $sql = "insert into lk_bangumi_name_map values(null,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $new_name);
        $stmt->bindValue(3, 1);
        $stmt->bindValue(4, 1);
        $stmt->bindValue(5, 1);
        $stmt->execute();
    }

    return true;
}

// 修改对应弹幕视频地址
function addUrlOnName($name, $url, $num)
{
    // 查一次番剧名称是否存在
    $list = getBangumiOnName($name, $num);

    try
    {
        $pdo = new PDO('mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname') . ';charset=utf8', dbConfig('user'), dbConfig('pass'));
    }
    catch(PDOException $e)
    {
        die('数据库连接失败 原因是：' . $e->getMessage());
    }

    // 如果番剧名称存在
    if($list)
    {
        // 番剧对照地址表新增数据
        $sql = "insert into lk_bangumi_url values(null,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $list['id']);
        $stmt->bindValue(2, $url);
        $stmt->execute();
    }
    else
    {
        // 番剧表新增数据
        $sql = "insert into lk_bangumi values(null,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $num);
        $stmt->bindValue(3, 1);
        $stmt->bindValue(4, 1);
        $stmt->bindValue(5, 1);
        $stmt->execute();
        // 得到自增id
        $id = $pdo->lastInsertId();

        // 番剧名称映射表新增数据
        $sql = "insert into lk_bangumi_url values(null,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $url);
        $stmt->execute();
    }
    return true;
}

// 记录用户提交意见
function addGuestsVoice($name, $num, $mark)
{
    try
    {
        $pdo = new PDO('mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname') . ';charset=utf8', dbConfig('user'), dbConfig('pass'));
    }
    catch(PDOException $e)
    {
        die('数据库连接失败 原因是：' . $e->getMessage());
    }

    // 留言表新增数据
    $sql = "insert into lk_guests_voice values(null,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $mark);
    $stmt->bindValue(2, $name);
    $stmt->bindValue(3, $num);
    $stmt->bindValue(4, date("Y-m-d H:i:s", time()));
    $stmt->execute();

    return true;
}

// 以番剧ID获取调整名
function getAuxiliaryNameOnId($id)
{
    try
    {
        $pdo = new PDO('mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname') . ';charset=utf8', dbConfig('user'), dbConfig('pass'));
    }
    catch(PDOException $e)
    {
        die('数据库连接失败 原因是：' . $e->getMessage());
    }

    $sql = "select b.name from lk_bangumi a INNER JOIN lk_bangumi_name_map b ON a.id=b.bangumi_id where a.name=? AND a.num=? GROUP BY b.name ORDER BY count(b.id) DESC,b.id DESC limit 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $name);
    $stmt->bindValue(2, $key);
    $stmt->execute();
    $list = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$list || !array_key_exists('name', $list)) return '';
    return $list['name'];
}

// 创建番剧表数据
function addBangumi($name, $num)
{
    try
    {
        $pdo = new PDO('mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname') . ';charset=utf8', dbConfig('user'), dbConfig('pass'));
    }
    catch(PDOException $e)
    {
        die('数据库连接失败 原因是：' . $e->getMessage());
    }

    // 番剧表新增数据
    $sql = "insert into lk_bangumi values(null,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $name);
    $stmt->bindValue(2, $num);
    $stmt->bindValue(3, 1);
    $stmt->bindValue(4, 1);
    $stmt->bindValue(5, 1);
    $stmt->execute();
    // 得到自增id
    return $pdo->lastInsertId();
}

// 保存独立弹幕
function savaDanmuInBangumi($data, $ip)
{
    // 以番剧名称+集数获取番剧id
    $id = getBangumiOnName($data['title'], $data['num']);
    if(!$id) return false;

    // 重组json用弹幕
    $mark = json_encode([
        // 出现时间
        $data['time'],
        // 类型
        ((int)$data['type']),
        // 颜色
        ((int)$data['color']),
        // 常见显示时长
        ($data['type']==0 ? 16777215 : 41194),
        // 弹幕内容
        $data['text'],
    ], JSON_UNESCAPED_UNICODE);

    try
    {
        $pdo = new PDO('mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname') . ';charset=utf8', dbConfig('user'), dbConfig('pass'));
    }
    catch(PDOException $e)
    {
        die('数据库连接失败 原因是：' . $e->getMessage());
    }

    // 录入到弹幕表
    $sql = "insert into lk_self_danmu values(null,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $id['id']);
    $stmt->bindValue(2, $mark);
    $stmt->bindValue(3, date("Y-m-d H:i:s", time()));
    $stmt->bindValue(4, $ip);   
    $stmt->execute();
    return true;
}

// 获取来源IP
function get_ip() {
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER["HTTP_X_FORWARDED_FOR"])
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && $_SERVER["HTTP_CLIENT_IP"])
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if (array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER["REMOTE_ADDR"])
        $ip = $_SERVER["REMOTE_ADDR"];
    else if (getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else
        $ip = "Unknown";
    return $ip;
}

// 以番剧id获取独立弹幕
function getSelfDanmu($id)
{
    try
    {
        $pdo = new PDO('mysql:host=' . dbConfig('host') . ';dbname=' . dbConfig('dbname') . ';charset=utf8', dbConfig('user'), dbConfig('pass'));
    }
    catch(PDOException $e)
    {
        die('数据库连接失败 原因是：' . $e->getMessage());
    }

    $sql = "select mark from lk_self_danmu where bangumi_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$list) return '';
    $list = array_column($list, "mark");
    return implode(',', $list);
}

/*——————————————————————————————— /通用方法 ———————————————————————————————*/