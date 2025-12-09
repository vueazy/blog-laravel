<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Permission;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $statistics = [
            'count_categories' => Category::count(),
            'count_tags' => Tag::count(),
            'count_posts' => Post::count(),
        ];

        if (Auth::user()->hasRole('admin')) {
            $statistics['count_users'] = User::count();
            $statistics['count_roles'] = Role::count();
            $statistics['count_permissions'] = Permission::count();
        }

        return responseJson(
            message: 'Statistic Data successfully retrieved',
            data: $statistics
        );
    }
}
