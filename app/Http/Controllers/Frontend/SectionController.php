<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GuestLesson;
use App\Models\Section;

class SectionController extends Controller
{
    /**
     * 章节信息
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $section = Section::with('lesson')->findOrFail($id);
        $guest = guest_user();

        $is_not_buy = GuestLesson::where('lesson_id', $section->lesson->id)
            ->where('guest_id', $guest->id)
            ->where('is_pay',1)
            ->get()
            ->isEmpty();

        if ($section->lesson->type == 2 && !$section->is_free && $is_not_buy) {//精品课程

            return response()->json(['status' => false, 'message' => '您没有观看此视频，如需观看请购买此课程']);

        } elseif ($section->lesson->type == 3 && !$guest->vip_id && !$section->is_free) {//vip课程

            return response()->json(['status' => false, 'message' => '您没有观看此视频，如需观看请开通vip']);

        } else {

            $this->updateLearned($id, $section->lesson, $guest);
            Section::find($id)->increment('play_times');

            return response()->json(['status' => true, 'sections' => new \App\Http\Resources\Section($section)]);

        }

    }

    /**
     * 更新播放记录
     * @param $lesson
     */
    public function updateLearned($id, $lesson, $guest)
    {
        $guest_lessons = GuestLesson::where('lesson_id', $lesson->id)->where('guest_id', $guest->id)->get();

        if ($guest_lessons->isEmpty()) {

            $data = [
                'is_like' => false,
                'is_pay' => false,
                'is_collect' => false,
                'collect_date' => null,
                'sections' => [$id],
                'last_section' => $id,
                'add_date' => now()
            ];

            $guest->lessons()->attach($lesson->id, $data);


        } else {

            $guest_lesson_sections = is_array($guest_lessons->first()->sections) ? $guest_lessons->first()->sections : [];
            $sections = array_unique( array_merge($guest_lesson_sections,[$id])) ;
            $data = [
                'is_like' => $guest_lessons->first()->is_like,
                'is_pay' => $guest_lessons->first()->is_pay,
                'is_collect' => $guest_lessons->first()->is_collect,
                'collect_date' => $guest_lessons->first()->collect_date,
                'sections' => $sections,
                'last_section' => $id,
                'add_date' => now()
            ];

            $guest->lessons()->updateExistingPivot($lesson->id ,$data);

        }

    }

}
