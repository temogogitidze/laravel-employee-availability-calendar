<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexPublicHolidayRequest;
use App\Http\Resources\PublicHolidayResource;
use App\Models\PublicHoliday;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PublicHolidayController extends Controller
{

    public function __construct(private PublicHoliday $model)
    {
    }

    public function index(IndexPublicHolidayRequest $request): AnonymousResourceCollection
    {
        $validatedData = $request->validated();

        $query = $this->model->newQuery();

        if ($validatedData['country']) {
            $query->where('country', $validatedData['country']);
        }

        return PublicHolidayResource::collection($query->get());
    }
}
