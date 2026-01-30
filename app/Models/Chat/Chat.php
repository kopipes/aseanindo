<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Chat extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'chats';

    protected $guarded = [];

    public function scopeByCustomerId($query,$customerId){
        return $query->join('chat_users as customer',function($join) use($customerId){
            $join->on('customer.chat_id','chats.id');
            $join->where('customer.user_id',$customerId);
            $join->where('customer.role','customer');
        });
    }
    public function agent(): HasOne
    {
        return $this->hasOne(ChatUser::class, 'chat_id', 'id')
            ->leftJoin('users', 'users.id', 'chat_users.user_id')
            ->leftJoin('company_users', function ($join) {
                $join->on('company_users.user_id', 'chat_users.user_id');
                $join->on('company_users.company_id', 'chat_users.company_id');
            })
            ->leftJoin(
                DB::raw('(
                    SELECT json_arrayagg(c.name) as helpdesk,h.user_id FROM user_helpdesk h
                    join company_helpdesk_categories c on c.id=h.helpdesk_id
                    GROUP BY h.user_id
                ) AS helpdesk'),
                function ($join) {
                    $join->on('chat_users.user_id', '=', 'helpdesk.user_id');
                }
            )
            ->where('chat_users.role', 'agent')
            ->addSelect('users.id', 'chat_users.chat_id', 'company_users.profile', 'users.name','helpdesk.helpdesk');
    }

    public function customer(): HasOne
    {
        return $this->hasOne(ChatUser::class, 'chat_id', 'id')
            ->where('chat_users.role', 'customer')
            ->addSelect('chat_users.chat_id', 'chat_users.role','chat_users.user_id as customer_id');
    }

    public function message(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_id', 'id')
            ->leftJoin('users', 'users.id', 'chat_messages.user_id')
            ->leftJoin('company_users', function ($join) {
                $join->on('company_users.user_id', 'chat_messages.user_id');
                $join->on('company_users.company_id', 'chat_messages.company_id');
            })
            ->orderBy('chat_messages.created_at', 'asc');
    }
}
