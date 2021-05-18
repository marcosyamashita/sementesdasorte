<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$requester = function($cpf, $ano = null){
    $anoPesquisado= $ano ? $ano : date('Y');
    
    $cpf = str_replace(['.', '-'], '', $cpf);
    $url = "http://187.32.157.12/siscupons/4198/$anoPesquisado/scripts/executar-visualizar-userhotsite.php?acao=visualizar_cupons_userhotsite&data1=$cpf";
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1'
    ]);
    $resp = curl_exec($curl);
    $resp = str_replace("<hr><br><input type='image' src='../images/bt_imprimir.png' onclick='cont();' >", '', $resp);
    curl_close($curl);

    return $resp;
};

Route::get('/', function () {
    return view('index');
});

Route::get('/check-cupom/{cpf}/{ano?}', function($cpf, $ano = null) use ($requester){
    if ($ano) {
        $response = $requester($cpf, $ano);
    } else {
        $response = $requester($cpf);
    }
    
    return response($response);
});

Route::get('/check-cupom/print/{cpf}/{ano?}', function($cpf, $ano = null) use ($requester){
    if ($ano) {
        $response = $requester($cpf, $ano);
    } else {
        $response = $requester($cpf);
    }

    return view('print', ['content'=>$response]);
});

Route::post('/contato', function(\Illuminate\Http\Request $request){
    $recipiente = new \stdClass();
    $recipiente->name = "Equipe Sicoob Crediembrapa";
    $recipiente->email = "agenciavirtual@crediembrapa.com.br";


    \Illuminate\Support\Facades\Mail::to($recipiente)->send(new App\Mail\ContatoMail($request->all()));

    return response()->json(['error'=>false, 'message'=>'Recebemos o seu contato. Em breve retornaremos']);
});
