<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Workspace extends Model
{
    protected $fillable = [
        'name',
    ];

    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }

    public function defaultChannel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'default_channel_id');
    }

    public function createChannel(string $channelName): Channel
    {
        return $this->channels()->create([
            'name' => $channelName,
        ]);
    }

    public function createDefaultChannel(string $channelName): Channel
    {
        $channel = $this->createChannel($channelName);

        $this->defaultChannel()->associate($channel)->save();

        return $channel;
    }
}
