<?php


namespace App\Service\Parse;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Exception;

class ParseService
{
    private $response_fields;

    public $base_url="vk.com";

    private $return_data = [
            'last_name' => 'last_name',
            'first_name' => 'first_name',
            'avatar' => 'photo_200_orig',
            'followers_count' => 'followers_count',
            'common_count' => 'common_count',
    ];

    public function parseProfile(string $method,string $link): array
    {
        $temp = (explode('id', $link, 2));

        if(isset($temp[1])){
            $id =$temp[1];
        }else{
          //  $id =// substr($link, 3, strpos($link, '/', strpos($link, '/')+1));
            $id = explode('vk.com/',$link);

            $id=$id[1];
        }


        return $this->sendRequest('users.get', ['user_id' => $id]);
    }

    private function sendRequest($method, $data)
    {
        $token = config('app.vk_token');
        $url = config('app.vk_api_url');
        $data['access_token'] = $token;
        $data['v'] = '5.131';

        $data['fields'] = [
                'followers_count',
                'common_count',
                'followers',
                'photos',
                'notes',
                'counters',
                'photo_200_orig'
        ];

       //  $response = Http::get('https://api.vk.com/method/users.get?user_id=94355184&access_token=79cddc74d8e31c1a67ac4c328acd6db89d3113e031ca88d10d59529b540648d188b92e984ed4f92955f0e&v=5.131');
        $response = Http::get($url . '/method/' . $method, $data);
        $json = $response->json();



        $parse = $this->parse($json);

        return $parse;
    }

    public function parse($json)
    {
        $response_array=[];

        if(isset($json['error'])){
            throw new Exception($json['error']['error_msg']);
        }

        if(isset($json['response'][0])){
        foreach ($json['response'][0] as $key=>$item){
            if(in_array($key,array_values($this->return_data))){
               $response_array[array_search($key,$this->return_data)] = $json['response'][0][$key];
            }
        }
        }

        return $response_array;
    }
}
