<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class AdminNotification extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_READ = 'read';

    const TYPE_NEW_ADVERT = 'new_advert';
    const TYPE_COMPLAIN = 'complain';
    const TYPE_ADVERT_MODERATE = 'advert_moderate';

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(AdminUser::class);
    }

    public static function getTitle($type, $id)
    {
        $title = '';
        switch ($type) {
            case self::TYPE_NEW_ADVERT:
                $title = 'Создано новое объявление <a href="' . URL::route('admin.adverts.show', $id, false) . '">ID - '.$id.'</a>';
                break;
            case self::TYPE_COMPLAIN:
                $title = 'На объявление <a href="' . URL::route('admin.adverts.show', $id, false) . '">ID - '.$id.'</a> поступила жалоба';
                break;
            case self::TYPE_ADVERT_MODERATE:
                $title = 'Объявление <a href="' . URL::route('admin.adverts.show', $id, false) . '">ID - '.$id.'</a> было отредактировано';
                break;
        }

        return $title;
    }

    public static function getNewCount()
    {
        return self::where('status', 'new')->count();
    }
}
