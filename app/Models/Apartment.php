<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Property;
use App\Models\Bed;
use App\Models\Room;
use App\Models\ApartmentType;
use App\Models\Facility;
use App\Models\ApartmentPrice;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Apartment extends Model
{
    use HasFactory;
    use HasEagerLimit;

    protected $fillable = [
        'property_id',
        'apartment_type_id',
        'name',
        'capacity_adults',
        'capacity_children',
        'size',
        'bathrooms'

        ];

    public function bedsList(): Attribute
    {
            $allBeds = $this->beds;
            $bedsByType = $allBeds->groupBy('bed_type.name');
            $bedsList = '';
            if ($bedsByType->count() == 1) {
                $bedsList = $allBeds->count() . ' ' . str($bedsByType->keys()->first());
            }
            else if ($bedsByType->count() > 1) {
                $bedsList = $allBeds->count() . ' ' . str('bed')->plural($allBeds->count());
                $bedsListArray = [];
                foreach ($bedsByType as $bedType => $beds) {
                    $bedsListArray[] = $beds->count() . ' ' . str($bedType);
                }
                $bedsList .= ' ('.implode(', ' , $bedsListArray) .')';
            }
            return new Attribute(
                get: fn () => $bedsList
                );
    }

        public function property()
        {
            return $this->belongsTo(Property::class);
        }

        public function apartment_type()
        {
            return $this->belongsTo(ApartmentType::class);
        }

        public function rooms()
        {
            return $this->hasMany(Room::class);
        }

        public function beds()
        {
            return $this->hasManyThrough(Bed::class, Room::class);
        }

        public function facilities()
        {
            return $this->belongsToMany(Facility::class);
        }

        public function prices()
        {
            return $this->hasMany(ApartmentPrice::class);
        }
}