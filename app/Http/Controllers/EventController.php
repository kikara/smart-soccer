<?php

namespace App\Http\Controllers;

use App\Models\EventParam;
use App\Models\Events;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EventParamResource;

class EventController extends Controller
{
    public function index(): JsonResource
    {
        return EventResource::collection(Events::all());
    }

    public function params(): JsonResource
    {
        return EventParamResource::collection(EventParam::all());
    }
}
