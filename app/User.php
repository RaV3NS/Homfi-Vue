<?php

namespace App;

use App\Mails\UserResetPassword;
use App\Mails\UserVerifyEmail;
use App\Mails\UserVerifyEmailChanged;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**  @OA\Schema(
 *      schema="User",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          format="int32"
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
 *          property="fullname",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          format="email"
 *      ),
 *      @OA\Property(
 *          property="email_verified_at",
 *          type="string",
 *          format="datetime"
 *      ),
 *      @OA\Property(
 *          property="social_links",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="last_login",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="status",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="datetime"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          format="datetime"
 *      ),
 *     @OA\Property(
 *          property="patronymic",
 *          type="string"
 *      )
 *  )
 *
 * @OA\Schema(
 *      schema="UserProfile",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="status",
 *          type="string"
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
 *          property="email",
 *          type="string",
 *          format="email"
 *      ),
 *      @OA\Property(
 *          property="phones",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              ref="#/components/schemas/Phone"
 *          )
 *      )
 *  )
 */
class User extends Authenticatable implements JWTSubject, MustVerifyEmail, CanResetPassword
{
    use Notifiable, MustVerifyEmailTrait;

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLED = 'disabled';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_DELETED = 'deleted';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'status', 'email_verified_at', 'last_login', 'social_links', 'patronymic'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'social_links' => 'array'
    ];

    protected $appends = ['fullname'];

    public function phones()
    {
        return $this->hasMany(Phone::class, 'model_id')->where('model', '=', self::class);
    }

    public function adverts()
    {
        return $this->hasMany(Advert::class);
    }

    public function complains()
    {
        return $this->hasMany(Complain::class);
    }

    public function history()
    {
        return $this->hasMany(UserLog::class)->orderBy('created_at', 'desc');
    }

    protected function getFullnameAttribute() {
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->patronymic;
    }

    public function manageAdvertsForStatus($status)
    {
        if ($status === $this::STATUS_BLOCKED) {
            $this->adverts->each(function ($advert) use ($status) {
                if ($advert->status === Advert::STATUS_ENABLED) {
                    $advert->update(['status' => Advert::STATUS_BLOCKED]);
                }
            });
        } elseif ($status === $this::STATUS_ACTIVE) {
            $this->adverts->each(function ($advert) {
                if ($advert->prev_status === Advert::STATUS_ENABLED) {
                    $advert->update(['status' => Advert::STATUS_ENABLED]);
                }
            });
        }

        return $this;
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class)->latest();
    }

    public function notes()
    {
        return $this->hasMany(UserNote::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new UserVerifyEmail(app()->getLocale()));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailChangedVerificationNotification()
    {
        $this->notify(new UserVerifyEmailChanged(app()->getLocale()));
    }

    /**
     * Send the password reset notification.
     *
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPassword($token));
    }
}
