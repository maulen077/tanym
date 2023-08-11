<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Game\GameResultResource;
use App\Http\Resources\Api\Game\RoundResource;
use App\Http\Resources\Api\Game\GameResource;
use App\Models\Cat;
use App\Models\Level;
use App\Models\Matches;
use App\Models\UserAnswer;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Game;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use App\Models\Question;

class GameController extends Controller
{
    public function store(Request $request)
    {
//        $request->validate([
//            'status' => ['required', Rule::in(['active', 'end'])],
//        ]);

//        $user = User::find($request->user_id);
//
//        if (!$user){
//            return response()->json([
//                'message' => 'not found'
//            ], 404);
//        }

        $data['game'] = Game::create([
            'user_id' => $request->user()->id,
            'cat_id' => $request->cat_id,
            'status' => 'active',
        ]);


        for ($level = 1; $level <= 3; $level++) {
            $levelModel = Level::create(['game_id' => $data['game']->id, 'number' => $level, 'point' => $level * 20, 'life' => 3]);
            $data['levels'][] = $levelModel;
            for ($roundNumber = 1; $roundNumber <= 20; $roundNumber++){
                $randomQuestion = Question::inRandomOrder()->value('id');

                 Matches::create([
                    'game_id' => $data['game']->id,
                    'round' => $roundNumber,
                    'question_id' => $randomQuestion,
                    'level_id' => $levelModel->id,
                    //add level 1-2-3 60round 1-20 1 lvl , 20-40 2 lvl, 40-60 3 lvl
                ]);


            }


        }




        //40 round

        //40random questions

   //     [
    //        'game'
      //      'rounds' ---> suraktar
        //]

        return response()->json($data,200);

    }

    public function showRound(Request $request)
    {

        $game = Game::find($request->game_id);

        if (!$game) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }

        $user = $request->user();

      //  $levels = Level::whereGameId($game->id)->pluck('id');
        $matches = Matches::whereLevelId($request['level_id'])->get();
        //add column gameId UserAnswer

        $currentRoundId =   UserAnswer::whereGameId($game->id)->whereNotNull('answer_id')->orderBy('match_id','desc')->value('match_id');

        $data = [
            'game_id' => $game->id,
            'level_id' => $request['level_id'],
            'cat_name' => Cat::whereId($game->cat_id)->value('name'),
            'current_round' => $currentRoundId != null ? Matches::whereId($currentRoundId)->value('round') : 1,
            'current_level' => $currentRoundId != null ? Matches::whereId($currentRoundId)->value('level') : 1,

    //            'rounds' => $game->rounds->map(function ($round){
//                return [
//                    'id' => $round->id,
//                    'image' => $round->image,
//                    'video' => $round->video,
//                    'question' => $round->question->text,
//                    'answers' => $round->question->answers->pluck('text'),
//                    'correct_asnwers' => $round->question->correctAnswer->text,
//                    'my_answer' => $round->userAnswer->where('user_id', auth()->id())
//                ];
//            })

            'rounds' =>  RoundResource::collection($matches)
        ];
        return response()->json($data, 200);
    }

    public function roundSoloShow(Request $request)
    {
        $roundId = $request->input('round');

        $round = Game::with('rounds.question.answers.correctAnswer', 'rounds.userAnswers')
            ->findOrFail($roundId);

        $data = [
            'round' => [
                'id' => $round->id,
                'image' => $round->image,
                'video' => $round->video,
                'question' => $round->question->text,
                'answers' => $round->question->answers->pluck('text'),
                'correct_answers' => $round->question->correctAnswer->pluck('text'),
                'my_answer' => $round->userAnswers->pluck('answer_id'),
            ],
        ];

        return response()->json($data, 200);
    }



    public function StoreRound(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games, id',
            'round_id' => 'required|exists:rounds,id',
            'answer_id' => 'required|exists:answers, id',
        ]);


        $userAnswer = UserAnswer::create([
            'game_id' => $request->game_id,
            'round_id' => $request->round_id,
            'user_id' => auth()->id(),
            'answer_id' => $request->answer_id,
        ]);

        return response()->json([
            'message' => 'Успешно',
            'data' => $userAnswer,
        ]);
    }

    public function endGame(Request $request)
    {
        $gameId = $request->input('game_id');
        $game = Game::findOrFail($gameId);
        $game->status = 'end';
        $game->save();

        return new GameResultResource($game);
    }

    public function showUserGame(Request $request)
    {
        $userId = $request->user()->id;
        $lastGame = Game::where('user_id', $userId)
            ->orderby('create_at', 'desc')
            ->first();

        if (!$lastGame) {
            return response()->json([
                'message' => 'игра не найдена'
            ],404);
        }
        return new GameResource($lastGame);
    }

}
