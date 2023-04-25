<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameSettingTemplateResource;
use App\Models\GameSettingTemplate;
use Illuminate\Http\Resources\Json\JsonResource;

class GameSettingTemplateController extends Controller
{
    public function show(GameSettingTemplate $template): JsonResource
    {
        return GameSettingTemplateResource::make($template);
    }
}
