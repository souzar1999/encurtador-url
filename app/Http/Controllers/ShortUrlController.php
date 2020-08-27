<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\ShortUrl;

class ShortUrlController extends Controller
{
  public function access($hash)
  {
    $shorturl = ShortUrl::where('hash', $hash)->first();
    if($shorturl){
      $shorturl->access_count += 1;
      $shorturl->save();
      return Redirect::to("$shorturl->url" , 301);
    } else {
      return Redirect::to("/" , 301);
    }
  }
  
  public function accessDetails($hash)
  {
    $shorturl = ShortUrl::where('hash', $hash)->orWhere('id', $hash)->first();
    if($shorturl){
      return response()->json($shorturl, 200);
    } else {
      return response()->json(['data' => "URL não encontrada"], 406);
    }
  }

  public function store(Request $request)
  {
    $shorturl = ShortUrl::where('url', $request->url)->first();
    
    if(!$shorturl){
        
      $caracters = 'abcdefghijklmnopqrstuvxwyzABCDEFGHIJKLMNOPQRSTUVXWYZ0123456789';

      $hash = '';
      $creater = $request->header('username');

      $shorturl = ShortUrl::create([
        "url" => $request->url,
        "creater" => $creater,
        "access_count" => 0,
        "hash" => ''
      ]);

      $count = $shorturl->id;
      
      while ($count >= 1 ) {
        $position = $count % 62;
        $hash .= substr($caracters, $position - 1, 1); 
        $count = $count / 62;
      }

      $shorturl->hash = $hash;
      $shorturl->save();

    } else {
      $hash = $shorturl->hash;
    }

    return response()->json($hash, 201);
  }
  
  public function update(Request $request, $hash)
  {
    $creater = $request->header('username');

    $shorturl = ShortUrl::where('creater', $creater)
                  ->orWhere(function($query) use ($hash) {
                    $query->where('hash', $hash)->where('id', $hash);
                  })
                  ->first();

    if($shorturl){
      $shorturl->url = $request->url;
      $shorturl->save();

      return response()->json($shorturl, 200);
    } else {
      return response()->json(['data' => 'Problemas ao editar URL'], 406);
    }
  }
  
  public function delete(Request $request, $hash)
  {
    $creater = $request->header('username');

    $delReturn = ShortUrl::where('creater', $creater)
                  ->orWhere(function($query) use ($hash) {
                    $query->where('hash', $hash)->where('id', $hash);
                  })
                  ->delete();

    if($delReturn){
      return response()->json(['data' => 'Registro deletado com sucesso'], 200);
    } else {
      return response()->json(['data' => 'Problemas ao deletar URL'], 406);
    }
  }
}