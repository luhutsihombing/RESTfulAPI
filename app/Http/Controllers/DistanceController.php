<?php

namespace App\Http\Controllers;
use Response;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class DistanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "only post";
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lat1   = Input::get('origin.0.latitude');
        $long1  = Input::get('origin.0.longitude');
        $lat2   = Input::get('destionation.0.latitude');
        $long2  = Input::get('destionation.0.longitude');       

        $url = 'https://maps.googleapis.com/maps/api/directions/json?origin='.$lat1.'%2C'.$long1.'&destination='.$lat2.'%2C'.$long2.'&key=AIzaSyDnWAQSCnh130lcAXKuI5xRCON9NW4hQE8';
        $response = file_get_contents($url);
        $result     = ($response);   
        $data       = json_decode($result);       

        $Cost1      = Input::get('vehicle.0.distance_per_litre');
        $Cost2      = Input::get('vehicle.0.price_per_litre');
        $distance   = $data->routes[0]->legs[0]->distance->text;
        $duration   = $data->routes[0]->legs[0]->duration->text;
        $cost       = $Cost2*floatval($duration);

        $success=array('distance'=>$distance,'duration'=>$duration,'cost'=>$cost);
        if(!$success)
        {
                 return Response::json("error Get API",500);
        }
 
        return Response::json($success,201);
    }
   
}
