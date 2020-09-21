<?php

namespace App;

use App\Filters\AdvertFilter;
use App\Filters\GeoFilter;
use App\Filters\OptionFilter;
use App\Filters\ParameterFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**  @OA\Schema(
 *      schema="Advert",
 *      @OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="first_name",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="last_name",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="user",
 *          type="object",
 *          ref="#/components/schemas/User"
 *      ),
 *      @OA\Property(
 *          property="phones",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              ref="#/components/schemas/Phone"
 *          )
 *      ),
 *      @OA\Property(
 *          property="media",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              ref="#/components/schemas/AdvertImage"
 *          )
 *      ),
 *      @OA\Property(
 *          property="city",
 *          type="object",
 *          ref="#/components/schemas/City"
 *      ),
 *      @OA\Property(
 *          property="parameters",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              ref="#/components/schemas/Parameter"
 *          )
 *      ),
 *      @OA\Property(
 *          property="options",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              ref="#/components/schemas/Option"
 *          )
 *      ),
 *      @OA\Property(
 *          property="status",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="type",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="body",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="title",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="address",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string"
 *      ),
 *  )
 */

/**  @OA\Schema(
 *      schema="PublicAdvert",
 *      @OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="first_name",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="last_name",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="user_id",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="images",
 *          type="object",
 *          ref="#/components/schemas/AdvertImage"
 *      ),
 *      @OA\Property(
 *          property="city",
 *          type="object",
 *          ref="#/components/schemas/City"
 *      ),
 *      @OA\Property(
 *          property="parameters",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              ref="#/components/schemas/Parameter"
 *          )
 *      ),
 *      @OA\Property(
 *          property="options",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              ref="#/components/schemas/Option"
 *          )
 *      ),
 *      @OA\Property(
 *          property="status",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="type",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="body",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="title",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="address",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="phone",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string"
 *      ),
 *  )
 */

/**
 * @OA\Schema(
 *      schema="AdvertImage",
 *      @OA\Property(
 *          property="card",
 *          type="array",
 *          @OA\Items(
 *              type="string"
 *          )
 *      ),
 *      @OA\Property(
 *          property="thumb",
 *          type="array",
 *          @OA\Items(
 *              type="string"
 *          )
 *      ),
 *      @OA\Property(
 *          property="720p",
 *          type="array",
 *          @OA\Items(
 *              type="string"
 *          )
 *      ),
 *      @OA\Property(
 *          property="1080p",
 *          type="array",
 *          @OA\Items(
 *              type="string"
 *          )
 *      ),
 *  )
 */

/**
 * @OA\Schema(
 *      schema="Coordinate",
 *      @OA\Property(
 *          property="lat",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="lng",
 *          type="string"
 *      )
 *  )
 */

/**
 * @OA\Schema(
 *      schema="AdvertCoordinate",
 *      @OA\Property(
 *          property="id",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="lat",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="lng",
 *          type="string"
 *      )
 *  )
 */

/**
 * @OA\Schema(
 *      schema="SeoAdvert",
 *      @OA\Property(
 *          property="title",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="meta_description",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="h1",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="images",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              ref="#/components/schemas/SeoAdvertImage"
 *          )
 *      )
 *  )
 */

/**
 * @OA\Schema(
 *      schema="SeoAdvertList",
 *      @OA\Property(
 *          property="title",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="meta_description",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="h1",
 *          type="string"
 *      )
 *  )
 */

/**
 * @OA\Schema(
 *      schema="SeoAdvertImage",
 *      @OA\Property(
 *          property="alt",
 *          type="string"
 *      )
 * )
 */
class Advert extends Model implements HasMedia
{
    use HasMediaTrait;

    const STATUS_DRAFT = 'draft';
    const STATUS_DISABLED = 'disabled';
    const STATUS_ENABLED = 'enabled';
    const STATUS_MODERATE = 'moderate';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_BLOCKED = 'blocked';

    public static $statuses = [
        self::STATUS_DRAFT,
        self::STATUS_DISABLED,
        self::STATUS_ENABLED,
        self::STATUS_MODERATE,
        self::STATUS_REJECTED,
        self::STATUS_ACCEPTED,
        self::STATUS_BLOCKED,
    ];

    public static $types = [
        'flat', 'house', 'half-house', 'room'
    ];

    public static $parameters = [
        'type',
        'room_count',
        'price_month'
    ];

    public static $filters = [
        'type',
        'room_count',
        'price_month',
        'publish_date',
        'total_space',
        'living_space',
        'kitchen_space',
        'build_year',
        'height',
        'floor',
        'total_floors',
        'joint_rent',
        'not_first_floor',
        'not_last_floor',
        'street',
        'administrative',
        'subway'
    ];

    protected $guarded = [];

    protected $casts = [
        'social_links' => 'array',
    ];

    protected $dates = [
        'publish_date'
    ];

    protected $with = [
        'city',
        'street',
        'user',
        'phones',
    ];

    protected $fillable = ['views', 'first_name', 'last_name', 'body', 'room_count',
        'email', 'status', 'city_id', 'district_id', 'lat', 'lng', 'social_links', 'street_id',
        'show_contacts', 'price_month', 'address', 'editing', 'subway_id', 'administrative_id', 'views', 'prev_status'];

    protected $appends = ['phone', 'full_address', 'title'];

    protected static function boot()
    {
        parent::boot();

        static::updating(function($advert) {
           if($advert->status === 'accepted') {
               $advert->status = 'enabled';
           }
        });
    }

    public function getPhoneAttribute()
    {
        return encrypt([$this->id, self::class, now()->addHour()->getTimestamp()], true);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function complains()
    {
        return $this->hasMany(Complain::class, 'advert_id');
    }

    public function phones()
    {
        return $this->hasMany(Phone::class, 'model_id')->where('model', '=', self::class);
    }

    public function parameters()
    {
        return $this->belongsToMany(Parameter::class, 'advert_parameter', 'advert_id', 'parameter_id')->withPivot('value');
    }

    public function page_parameters()
    {
        return $this->belongsToMany(Parameter::class, 'advert_parameter', 'advert_id', 'parameter_id')
            ->whereIn('parameters.key', config('settings.advert_page_parameters'))
            ->withPivot('value');
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'advert_option', 'advert_id', 'option_id');
    }

    public function newAdminNotifications()
    {
        return $this->hasMany(AdminNotification::class)->where('status', '=', 'new');
    }

    public function notification()
    {
        return $this->hasOne(UserNotification::class)->without('owner')->whereIn('type', ['advert_blocked', 'advert_rejected'])->latest();//->limit(1);
    }

    public function administrative()
    {
        return $this->belongsTo(Administrative::class, 'administrative_id', 'id');
    }

    public function street()
    {
        return $this->belongsTo(Street::class, 'street_id', 'id');
    }

    public function subway()
    {
        return $this->belongsTo(Subway::class, 'subway_id', 'id');
    }

    public function history()
    {
        return $this->hasMany(UserLog::class, 'advert_id', 'id')->latest();
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('images')
//            ->acceptsFile(function (File $file) {
//                return $file->mimeType === 'image/jpeg';
//            })
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('card')
                    ->width(400)
                    ->height(300);
                $this->addMediaConversion('1080p')
                    ->width(1920)
                    ->height(1080);
                $this->addMediaConversion('720p')
                    ->width(1280)
                    ->height(720);
                $this->addMediaConversion('thumb')
                    ->height(200);
            });

    }

    public function getImageUrls()
    {
        $images = [];
        foreach ($this->getMedia('images') as $media) {
            $image = new \StdClass();
            $image->id = $media->id;
            $image->card = $media->getFullUrl('card');
            $image->thumb = $media->getFullUrl('thumb');
            $image->{'720p'} = $media->getFullUrl('720p');
            $image->{'1080p'} = $media->getFullUrl('1080p');

            $images[] = $image;
        }
        $this->images = $images;

        return $this;
    }

    /**
     * Apply all relevant advert filters.
     *
     * @param  Builder $query
     * @param AdvertFilter $filters
     * @return Builder
     */
    public function scopeAdvertFilter($query, AdvertFilter $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Apply all relevant parameters filters.
     *
     * @param  Builder $query
     * @param  ParameterFilter $filters
     * @return Builder
     */
    public function scopeParameterFilter($query, ParameterFilter $filters)
    {
        $query->leftJoin('advert_parameter', 'adverts.id', '=', 'advert_parameter.advert_id');
        return $filters->apply($query);
    }

    /**
     * Apply all relevant parameters filters.
     *
     * @param  Builder $query
     * @param  ParameterFilter $filters
     * @return Builder
     */
    public function scopeOptionFilter($query, OptionFilter $filters)
    {
        $query->leftJoin('advert_option', 'adverts.id', '=', 'advert_option.advert_id');
        return $filters->apply($query);
    }

    /**
     * Apply all relevant geo filters.
     *
     * @param  Builder $query
     * @param GeoFilter $filters
     * @return Builder
     */
    public function scopeGeoFilter($query, GeoFilter $filters)
    {
        return $filters->apply($query);
    }

    public function getFullAddressAttribute()
    {
        $fullAddressRu = $fullAddressUk = [];

        if(!empty($this->city->region->district)) {
            $fullAddressUk[] = $this->city->region->district->name_uk . ' область, ';
        }

        if(!empty($this->city)) {
            $fullAddressUk[] = $this->city->name_uk . ', ';
        }

        if(!empty($this->street)) {
            $fullAddressUk[] = $this->street->name_uk;
        }

        if(!empty($this->address)) {
            $fullAddressUk[] = ', ' . $this->address;
        }

        if(!empty($this->subway)) {
            $fullAddressUk[] = ', станція метро ' . $this->subway->name;
        }
        $fullAddressUk = join('', $fullAddressUk);


        if(!empty($this->city->region->district)) {
            $fullAddressRu[] = $this->city->region->district->name_ru . ' область, ';
        }

        if(!empty($this->city)) {
            $fullAddressRu[] = $this->city->name_ru . ', ';
        }

        if(!empty($this->street)) {
            $fullAddressRu[] = $this->street->name_ru;
        }

        if(!empty($this->address)) {
            $fullAddressRu[] = ', ' . $this->address;
        }

        if(!empty($this->subway->name_ru)) {
            $fullAddressRu[] = ', станция метро ' . $this->subway->name_ru;
        }
        $fullAddressRu = join('', $fullAddressRu);

        return [
            'uk' => $fullAddressUk,
            'ru' => $fullAddressRu
        ];
    }

    public function getTitleAttribute()
    {
        $resultUk = $resultRu = [];

        $locale = 'uk';
        $fieldNameLocale = 'name_' . $locale;

        $type = trans_fb('parameter_values.type.' . $this->type, '', [], '', $locale);
        if(!empty($type)) {
            $resultUk[] = $type;
        }

        $room_count = trans_fb('parameter_values.room_count.title.' . $this->room_count, '', [], '', $locale);
        if(!empty($room_count)) {
            $resultUk[] = $room_count;
        }

        if (!empty($this->street)) {
            $resultUk[] = $this->street->{$fieldNameLocale} . ',';
        }
        if (!empty($this->subway)) {
            $resultUk[] = 'станція метро ' . $this->subway->name . ',';
        }
        if (!empty($this->administrative)) {
            $resultUk[] = $this->administrative->{$fieldNameLocale} . ',';
        }
        if (!empty($this->city)) {
            $resultUk[] = $this->city->{$fieldNameLocale};
        }
        $resultUk = mb_ucfirst(join(' ', array_values($resultUk)));

        $locale = 'ru';
        $fieldNameLocale = 'name_' . $locale;

        $type = trans_fb('parameter_values.type.' . $this->type, '', [], $locale);
        if(!empty($type)) {
            $resultRu[] = $type;
        }

        $room_count = trans_fb('parameter_values.room_count.title.' . $this->room_count, '', [], $locale);
        if(!empty($room_count)) {
            $resultRu[] = $room_count;
        }

        if (!empty($this->street)) {
            $resultRu[] = $this->street->{$fieldNameLocale} . ',';
        }
        if (!empty($this->subway)) {
            $resultRu[] = 'станция метро ' . $this->subway->{$fieldNameLocale} . ',';
        }
        if (!empty($this->administrative)) {
            $resultRu[] = $this->administrative->{$fieldNameLocale} . ',';
        }
        if (!empty($this->city)) {
            $resultRu[] = $this->city->{$fieldNameLocale};
        }

        $resultRu = mb_ucfirst(join(' ', array_values($resultRu)));

        return [
            'uk' => $resultUk,
            'ru' => $resultRu
        ];
    }

    public function setStatusAttribute($status)
    {
        if(isset($this->attributes['status']) && $status !== $this->attributes['status']){
            $this->prev_status = $this->status;
        }
        $this->attributes['status'] = $status;

        return $this;
    }

}
