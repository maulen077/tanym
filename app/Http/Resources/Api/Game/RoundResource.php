<?php

namespace App\Http\Resources\Api\Game;

use App\Models\Answer;
use App\Models\CorrectAnswer;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $question = Question::whereId($this->question_id)->first();

        return [
            'id' => $question->id,
            'image' => $question->image,
            'video' => $question->video,
            'question' => $question->text,
            'answers' => Answer::whereQuestionId($question->id)->get(),
            'correct_answers' => CorrectAnswer::whereQuestionId($question->id)->value('answer_id'),
            'my_answer' => UserAnswer::whereMatchId($this->id)->pluck('answer_id'),
        ];
    }
}
