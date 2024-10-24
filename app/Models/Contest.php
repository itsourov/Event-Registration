<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Contest extends Model implements HasMedia
{
    use HasSEO;
    use SoftDeletes, HasFactory;
    use InteractsWithMedia;
    protected $fillable = [
        'name',
        'slug',
        'semester',
        'semester',
        'description',
        'registration_fee',
        'registration_deadline',
        'countdown_text',
        'countdown_time',
        'sections',
        'departments',
        'lab_teacher_names',
        'dates',
        'room_data',
        'extra',
        'registration_limit',
    ];

    protected function casts(): array
    {
        return [
            'registration_deadline' => 'datetime',
            'countdown_time' => 'datetime',
            'sections' => 'array',
            'departments' => 'array',
            'lab_teacher_names' => 'array',
            'dates' => 'array',
            'room_data' => 'array',
            'extra' => 'array',
        ];
    }
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('contest-banner-images')
            ->useFallbackUrl(asset('images/no-image.png'))
            ->useFallbackPath(public_path('/images/no-image.png'));

    }
    public function registerMediaConversions(Media|null $media = null): void
    {

        $this
            ->addMediaConversion('medium')
            ->fit(Fit::Crop, 1000, 700)
            ->queued();
    }
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
    public function getDynamicSEOData(): SEOData
    {


        // Override only the properties you want:
        return new SEOData(
            title: $this->name,
            description:  Str::limit(strip_tags($this->description)) ,
            image: $this->getFirstMediaUrl('contest-banner-images'),
        );
    }
}
