<?php

namespace App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use App\Models\{GenerationTree, BinaryTree, User};
use Illuminate\Support\Facades\Auth;
use App\Traits\Reply;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TreeController extends Controller
{
    use Reply;

    public function binary()
    {
        $parent = BinaryTree::where('user_id', Auth::id())->first();

        if (!$parent) {
            return $this->failed("Invalid Data!!");
        }

        $data = BinaryTree::with(['user:id,name,user_unique_id,plan_active'])->withCount([
            'staked_plan' => function ($query) {
                return   $query->select(DB::raw("SUM(usdt_amount) as total_business"));
            }
        ])->descendantsAndSelf($parent->id)->toTree()->first();

        return $this->success("Binary Tree Fetched Successfully!!", $data);
    }

    public function generation()
    {
        $parent = GenerationTree::where('user_id', Auth::id())->first();

        if (!$parent) {
            return $this->failed("Invalid Data!!");
        }

        $data = GenerationTree::with('user:id,name,user_unique_id,plan_active')->descendantsAndSelf($parent->id)->toTree()->first();

        return $this->success("Generation Tree Fetched Successfully!!", $data);
    }

    public function tree_node(Request $request)
    {

        try {
            $validator  = Validator::make(
                ["sponser_id" => $request->sponser_id],
                [
                    'sponser_id' => 'required|integer|exists:users,user_unique_id',
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $user_id = User::where("user_unique_id", $request->sponser_id)->value('id') ?? 0;

            $binary_id = BinaryTree::where('user_id', $user_id)->value('id') ?? 0;

            $data = BinaryTree::with("user:id,name,user_unique_id,plan_active")->where("parent_id", $binary_id)->get(["id", "user_id", "side", "team"])->toArray() ?? [];

            if (count($data) == 1) {
                $side = $data[0]['side'] == "left" ? "right" : "left";

                $dummy = [
                    "id" =>  0,
                    "user_id" =>  0,
                    "side" =>  $side,
                    "team" =>  $side . "_team",
                    "user" =>  [
                        "id" =>  0,
                        "name" =>  "Empty",
                        "user_unique_id" =>  00000000,
                        "plan_active" =>  0
                    ],
                ];

                array_push($data, $dummy);
            }

            if (count($data) == 0) {


                $data = [
                    [
                        "id" =>  0,
                        "user_id" =>  0,
                        "side" =>  "left",
                        "team" =>  "left_team",
                        "user" =>  [
                            "id" =>  0,
                            "name" =>  "Empty",
                            "user_unique_id" =>  00000000,
                            "plan_active" =>  0
                        ]
                    ],
                    [
                        "id" =>  0,
                        "user_id" =>  0,
                        "side" =>  "right",
                        "team" =>  "right_team",
                        "user" =>  [
                            "id" =>  0,
                            "name" =>  "Empty",
                            "user_unique_id" =>  00000000,
                            "plan_active" =>  0
                        ]
                    ]
                ];
            }

            $response = [];

            $position = $data[0]['side'] == "left" ? "ok" : "notok";

            if ($position == "notok") {
                $response[0] = $data[1];
                $response[1] = $data[0];
            }

            $response = $data;

            return $this->success("Binary Tree Fetched Successfully!!", $response);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function tree_node_generation(Request $request)
    {

        try {
            $validator  = Validator::make(
                ["sponser_id" => $request->sponser_id],
                [
                    'sponser_id' => 'required|integer|exists:users,user_unique_id',
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $user_id = User::where("user_unique_id", $request->sponser_id)->value('id') ?? 0;

            $binary_id = GenerationTree::where('user_id', $user_id)->value('id') ?? 0;

            $data = GenerationTree::with("user:id,name,user_unique_id,plan_active")->where("parent_id", $binary_id)->get(["id", "user_id"]) ?? [];

            return $this->success("Generation Tree Fetched Successfully!!", $data);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function getsponser_detail(Request $request)
    {
        try {
            $validator  = Validator::make(
                ["sponser_id" => $request->sponser_id],
                [
                    'sponser_id' => 'required|integer|exists:users,user_unique_id',
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $data = User::where("user_unique_id", $request->sponser_id)->first(['name', 'user_unique_id', 'plan_active']) ?? 0;

            return $this->success("User Fetched Successfully!!", $data);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }
}
