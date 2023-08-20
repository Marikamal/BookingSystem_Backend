<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ApartmentDetailsResource;
use App\Models\Apartment;

class ApartmentController extends Controller
{

    public function __invoke(Apartment $apartment)
    {
        $apartment->load('facilities.category');
        $apartment->setAttribute(
            'facility_categories',
            $apartment->facilities->groupBy('category.name')->mapWithKeys(function ($facilities, $category) {
                return [$category => $facilities->pluck('name')];
            })
        );
        
        return new ApartmentDetailsResource($apartment);
    }
}
