<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteSettings extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'settings',
        'qb_access_token_key',
        'qb_refresh_token_key',
        'qb_realm_id'
    ];

    protected $casts = [
        'settings' => 'array'
    ];

    /**
     * @param int $status
     *
     * @return bool
     */
    public function switchStatus(int $status): bool
    {
        $settings              = $this->settings;
        $settings['is_active'] = $status;
        $this->settings        = $settings;
        $this->save();

        return true;
    }
}
