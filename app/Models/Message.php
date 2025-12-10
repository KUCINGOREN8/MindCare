<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'message',
        'attachment_path',
        'attachment_name',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    protected $appends = ['attachment_url', 'attachment_type'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
        return $this;
    }

    public function getAttachmentUrlAttribute()
    {
        if (!$this->attachment_path) {
            return null;
        }

        if (Storage::disk('public')->exists($this->attachment_path)) {
            return asset('storage/' . $this->attachment_path);
        }
        return null;
    }

    public function getAttachmentTypeAttribute()
    {
        if (!$this->attachment_path) {
            return null;
        }

        $extension = strtolower(pathinfo($this->attachment_path, PATHINFO_EXTENSION));

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        $documentExtensions = ['pdf', 'doc', 'docx', 'txt', 'rtf'];
        $spreadsheetExtensions = ['xls', 'xlsx', 'csv'];
        $presentationExtensions = ['ppt', 'pptx'];
        $archiveExtensions = ['zip', 'rar', '7z'];

        if (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $documentExtensions)) {
            return 'document';
        } elseif (in_array($extension, $spreadsheetExtensions)) {
            return 'spreadsheet';
        } elseif (in_array($extension, $presentationExtensions)) {
            return 'presentation';
        } elseif (in_array($extension, $archiveExtensions)) {
            return 'archive';
        }

        return 'file';
    }

    public function hasAttachment()
    {
        return !is_null($this->attachment_path);
    }

    public function getAttachmentIcon()
    {
        if (!$this->attachment_path) {
            return null;
        }

        $type = $this->attachment_type;

        $icons = [
            'image' => '🖼️',
            'document' => '📄',
            'spreadsheet' => '📊',
            'presentation' => '🎁',
            'archive' => '📦',
            'file' => '📁'
        ];

        return $icons[$type] ?? '📁';
    }
}
