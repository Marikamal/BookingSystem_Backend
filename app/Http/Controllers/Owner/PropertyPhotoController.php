<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PropertyPhotoController extends Controller
{
    
    public function store(Property $property, Request $request)
    {
        $request->validate([
            'photo' => ['image', 'max:5000']
        ]);
        if ($property->owner_id != auth()->id()) {
            abort(403);
        }

        $photo = $property->addMediaFromRequest('photo')->toMediaCollection();
        $photo->save();
        return [
            'filename' => $photo->getUrl(),
            'thumbnail' => $photo->getUrl('thumbnail'),
        ];
            
    }

    public function reorder(Property $property, Media $photo, int $newPosition)
    {
        if ($property->owner_id != auth()->id() || $photo->model_id != $property->id){
            abort(403);
        }
        
        $photo2= Media::where('model_type', 'App\Models\Property')
        ->where('model_id', $photo->model_id)
        ->where('order_column',$newPosition)
        ->first();
        $current_order = $photo->order_column;
        $photo->order_column = $newPosition;
        $photo->save();
        $photo2->order_column=$current_order;
        $photo2->save();
        return [
            'newPosition' => $photo->order_column
        ];
    }



    public function reorderd(Property $property, Media $currentPhoto, int $newPosition)
    {
        abort_if($property->owner_id != auth()->id() || $currentPhoto->model_id != $property->id, 403);
    
        $newPositionPhoto = $property->media()->where('order_column', $newPosition)->first();
    
        DB::transaction(function () use ($currentPhoto, $newPositionPhoto, $newPosition) {
            $currentPhoto->order_column = $newPosition;
            $currentPhoto->save();
    
            $newPositionPhoto->order_column = $currentPhoto->order_column;
            $newPositionPhoto->save();
        });
    
        return [
            'newPosition' => $currentPhoto->order_column
        ];
    }
    













}
