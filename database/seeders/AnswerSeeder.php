<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\CorrectAnswer;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = Question::all();

        foreach ($questions as $question) {
            $answers = [
                'Answer text 1 for question ' . $question->id,
                'Answer text 2 for question ' . $question->id,
                'Answer text 3 for question ' . $question->id,
                'Answer text 4 for question ' . $question->id,
            ];

            $correctAnwerIndex = rand(0, 3);

            foreach ($answers as $key => $text) {
                $lastAnswer =   Answer::create([
                    'question_id' => $question->id,
                    'text' => $text,
                ]);
            }
            CorrectAnswer::create([
                'question_id' => $question->id,
                'answer_id' => $lastAnswer->id,
            ]);
        }
    }
}
