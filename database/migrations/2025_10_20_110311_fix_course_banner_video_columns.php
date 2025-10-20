<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تنظيف البيانات الموجودة: نقل القيم من banner_url إلى banner
        \DB::table('courses')
            ->whereNotNull('banner_url')
            ->where('banner_source', 'device')
            ->update([
                'banner' => \DB::raw("SUBSTRING_INDEX(banner_url, '/', -1)"),
            ]);

        // تنظيف البيانات الموجودة: نقل القيم من video_url إلى promo_video
        \DB::table('courses')
            ->whereNotNull('video_url')
            ->where('video_source', 'device')
            ->update([
                'promo_video' => \DB::raw("SUBSTRING_INDEX(video_url, '/', -1)"),
            ]);

        // مسح banner_url و video_url للملفات المرفوعة من الجهاز فقط
        \DB::table('courses')
            ->where('banner_source', 'device')
            ->update(['banner_url' => null]);

        \DB::table('courses')
            ->where('video_source', 'device')
            ->update(['video_url' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا يمكن التراجع عن هذا التعديل بشكل كامل
    }
};
