<?php

namespace Vipertecpro\Settings\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Setting extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('settings.table', 'settings');
        parent::__construct($attributes);
    }

    protected $guarded = ['id'];

    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = str_slug($value, '_');
    }

    public function getTypeAttribute()
    {
        return $this->attributes['type'] = strtoupper($this->attributes['type']);
    }

    public function getValueAttribute()
    {
        $value = $this->attributes['value'];

        switch ($this->attributes['type']) {
            case 'FILE':
                if (!empty($value)) {
                    return Config::get('settings.upload_path') . '/' . $value;
                }
                break;
            case 'SELECT':
                $values = json_decode($value, true);
                if ($values) {
                    return $values;
                }
                return [];
                break;
            case 'BOOLEAN':
                return $value === 'true';
                break;
            case 'NUMBER':
                return (float)$value;
                break;
        }

        return $value;
    }
}
