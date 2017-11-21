<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Http\Requests\StoreUserAmount;
use App\Http\Requests\StoreUsersTransfer;
use App\Repositories\UserInterface;
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
     * @param StoreUser $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance(StoreUser $request)
    {
        $user = $this->user->getById($request->input('user'));

        return response()->json($user);
    }

    /**
     * Add money to user
     *
     * @param StoreUserAmount $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deposit(StoreUserAmount $request)
    {
        try {
            $this->user->deposit($request->input('user'), $request->input('amount'));
        } catch (\Exception $exception) {
            return response()->json(['errors' => $exception->getMessage()], 422);
        }

        return response()->json();
    }

    /**
     * Withdraw money from user
     *
     * @param StoreUserAmount $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(StoreUserAmount $request)
    {
        try {
            $this->user->withdraw($request->input('user'), $request->input('amount'));
        } catch (\Exception $exception) {
            return response()->json(['errors' => $exception->getMessage()], 422);
        }

        return response()->json();
    }

    /**
     * Transfer money from one user to another
     *
     * @param StoreUsersTransfer $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function transfer(StoreUsersTransfer $request)
    {
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

        return response()->json();
    }
}
