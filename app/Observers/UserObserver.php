<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAction;

class UserObserver
{
    public function saved($model)
    {
        if ($model->wasRecentlyCreated == true) {
            // Data was just created
            $action = 'created';
        } else {
            // Data was updated
            $action = 'updated';
        }
        if (Auth::check()) {
            UserAction::create([
                'user_id'      => Auth::user()->id,
                'action'       => $action,
                'action_model' => $model->getTable(),
                'action_id'    => $model->id
            ]);
        }
    }
    /**
     * Handle the User "deleted" event.
     */
    public function deleted($model): void
    {
       $action='softDeleted';
       if (Auth::check()) {
        UserAction::create([
            'user_id'      => Auth::user()->id,
            'action'       => $action ,
            'action_model' => $model->getTable(),
            'action_id'    => $model->id
        ]);
    }
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored($model): void
    {
       $action='restored';
       if (Auth::check()) {
        UserAction::create([
            'user_id'      => Auth::user()->id,
            'action'       => $action,
            'action_model' => $model->getTable(),
            'action_id'    => $model->id
        ]);
    }
}

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted($model): void
    {
        $action='deleted-forever';
        if (Auth::check()) {
         UserAction::create([
             'user_id'      => Auth::user()->id,
             'action'       => $action,
             'action_model' => $model->getTable(),
             'action_id'    => $model->id
         ]);
    }
}
}
