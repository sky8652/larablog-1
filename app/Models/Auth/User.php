<?php

namespace App\Models\Auth;

use App\Models\Portal\Article\Reply;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use App\Models\Portal\Article\Article;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable,MustVerifyEmailTrait;


//    use Notifiable {
//        notify as protected laravelNotify; // 给 Notifiable Trait 中的 notify 方法起别名
//    }
//
//    /**
//     * 每次调用 notify 方法时，自动将 users 表里的 notification_count +1
//     * 重写了 Notifiable Trait 中的 notify 方法
//     * 『\Illuminate\Notifications\RoutesNotifications::notify』
//     *
//     * @param $instance
//     */
//    public function notify($instance)
//    {
//        // 如果要通知的人是当前用户，就不必通知了
//        if ($this->id == \Auth::id()) return;
//
//        // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
//        if (method_exists($instance, 'toDatabase')) {
//            $this->increment('notification_count');
//        }
//
//        // 最后还是需要调用 Notifiable Trait 中的 notify 方法来发送通知
//        $this->laravelNotify($instance);
//    }



    // 用户默认头像
    const DEFAULT_HEADER = '/uploads/portal/img/auth/default-header.png';

    /**
     * 防止用户随意修改模型数据，只有在此属性里定义的字段，才允许修改，否则更新时会被忽略
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar'
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
    ];

    /**
     * 获取用户的头像路径
     *
     * @param $value  string  用户头像相对路径
     * @return string  用户头像带 url 链接的绝对路径
     */
    public function getAvatarAttribute($value)
    {
        if (!$value) $value = self::DEFAULT_HEADER;
        return $value;
        return config('app.url') . $value;
    }

    /**
     * 用户-文章 一对多关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'user_id', 'id');
    }

    /**
     * 一个用户可以拥有多条评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class, 'user_id', 'id');
    }

    /**
     * 授权验证
     *
     * @param $model  需要检验的模型实例
     * @return bool  当前用户可以操作则为 true，反之为 false
     */
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    /**
     * 标记通知消息为已读
     */
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        // 该方法来自 「Illuminate\Notifications\HasDatabaseNotifications::unreadNotifications」 Trait
        $this->unreadNotifications->markAsRead();
    }

}
