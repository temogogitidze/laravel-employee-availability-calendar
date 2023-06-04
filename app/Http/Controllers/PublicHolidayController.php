<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexPublicHolidayRequest;
use App\Http\Resources\PublicHolidayResource;
use App\Models\PublicHoliday;
use Illuminate\Http\Request;

class PublicHolidayController extends Controller
{

    public function __construct(private PublicHoliday $model)
    {
    }

    public function index(IndexPublicHolidayRequest $request)
    {
        $validatedData = $request->validated();

        $query = $this->model->newQuery();

        return PublicHolidayResource::collection($query->get());
    }
}
