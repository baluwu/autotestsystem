<?php

namespace Admin\Controller;

use Think\Controller;

class TestController extends Controller {
  private $serv;

  protected function _initialize() {

  }

  public function testRule() {
//    {"v1":"aaa","dept":"\u5305\u542b","v2":"ttt"}

    $rule = [
      "v1" => "aaa",
      "dept"=>"等于",
      "v2"=>1,
    ];

    $data="1";

    echo judged($data,$rule)?'success':'fail';

  }

  public function index() {
    $isloop = 0;
    G('start_loop');
    while ($isloop < 100) {
      D('ExecHistory')->ExecuteSingle(105, '127.0.0.1', 9502);
      $isloop++;
    }
    G('end_loop');
    echo G('start_loop', 'end_loop');
  }

  public function http() {
    $serv = new \swoole_http_server("127.0.0.1", 9502);

    $serv->on('Request', function ($request, $response) {

      var_dump($request->post);
//      var_dump($response);
//      sleep(mt_rand(1, 5) * 0.01);
      $response->header('Content-Type', 'application/json');

//      http://www.tuling123.com/openapi/api

//      if($request->server['request_uri']=='asrToNlp'){
//
//      }

      $HttpClient = new \Leaps\HttpClient\Adapter\Curl();
      $HttpClient->setOption(CURLOPT_TIMEOUT, 10);

      $tuling = $HttpClient->post('http://www.tuling123.com/openapi/api', [
        'key'    => '14f95b0e2f24e3829314a39cb99f8deb',
        'info'   => $request->post['asrToNlp'],
        'userid' => '123456'
      ]);
//      $response->end(json_encode(['asd'=>'asd2']));
//      $response->end($request->post['nlp']);


//      $response->end($tuling->getContent());
      $response->end(json_encode([
        'success'    => mt_rand(1, 3) >= 2,
        'errorCode'  => mt_rand(200, 700),
        'finished'   => mt_rand(1, 3) >= 2,
        'activation' => mt_rand(1, 3) >= 2,
        'asr'        => $this->getRandChar(mt_rand(10, 20)),
        'domain'     => $this->getRandChar(mt_rand(10, 20)),
        'content'    => contentAsArray($tuling->getContent())
      ], JSON_UNESCAPED_UNICODE));
    });

    $serv->start();
  }

  private function getRandChar($length) {
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0; $i < $length; $i++) {
      $str .= $strPol[mt_rand(0, $max)];
    }
    return $str;
  }

}
