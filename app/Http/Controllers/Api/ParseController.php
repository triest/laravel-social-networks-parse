<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\Parse\ParseService;
use Illuminate\Http\Request;
use Exception;

class ParseController extends Controller
{
    public $parseService;

    private $services = [];

    /**
     * ParseController constructor.
     * @param $parseService
     */
    public function __construct(ParseService $parseService)
    {
        $this->services[] = $parseService;
    }


    public function parse(Request $request){
        $data = $request->validate(['link'=>'url']);
        $parse = parse_url($data['link']);

        if(!isset($parse['host'])){
            throw new Exception('Host not found');
        }

        $response = [];
        foreach ($this->services as $service){
            if($service->base_url==$parse['host']){
                $response = $service->parseProfile('users.get',$data['link']);
            }
        }

        return response()->json($response);
    }
}
