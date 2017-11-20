<?php

namespace App\Http\Controllers;

use App\Repositories\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * The user repository instance.
     */
    protected $user;

    /**
     * UserController constructor.
     *
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Get user balance
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user' => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $this->user->getById($request->input('user'));

        return response()->json($user);
    }

    /**
     * Add money to user
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deposit(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->user->deposit($request->input('user'), $request->input('amount'));
        } catch (\Exception $exception) {
            return response()->json(['errors' => $exception->getMessage()], 422);
        }

        return response()->json([]);
    }

    /**
     * Withdraw money from user
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user' => 'required|numeric|exists:users,id',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->user->withdraw($request->input('user'), $request->input('amount'));
        } catch (\Exception $exception) {

            return response()->json(['errors' => $exception->getMessage()], 422);
        }

        return response()->json([]);
    }

    /**
     * Transfer money from one user to another
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function transfer(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from' => 'required|numeric|exists:users,id',
            'to' => 'required|numeric|not_same:from',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $this->user->withdraw($request->input('from'), $request->input('amount'));
        } catch (\Exception $exception) {
            DB::rollback();

            return response()->json(['errors' => $exception->getMessage()], 422);
        }

        try {
            $this->user->deposit($request->input('to'), $request->input('amount'));
        } catch (\Exception $exception) {
            DB::rollback();

            return response()->json(['errors' => $exception->getMessage()], 422);
        }

        DB::commit();

        return response()->json([]);
    }
}
