<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thing;
use App\Models\Place;
use App\Models\Unit;
use App\Models\Usage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThingApiController extends Controller
{
    /**
     * Список вещей текущего пользователя с кэшированием (JSON)
     */
    public function index()
    {
        $userId = auth()->id();

        $source = 'из кэша (загрузка мгновенная)';

        $things = Cache::remember(
            "things.my.paginated.api.{$userId}",
            now()->addMinutes(20),
            function () use ($userId, &$source) {
                $source = 'из базы данных (первый запрос)';
                return Thing::where('master_id', $userId)
                    ->with(['place', 'unit'])
                    ->withSum('usages as used_amount', 'amount')
                    ->latest()
                    ->paginate(10);
            }
        );

        return response()->json([
            'data' => $things,
            'source' => $source,
        ]);
    }

    /**
     * Форма добавления новой вещи — только для администратора (JSON)
     */
    public function create()
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $places = Place::orderBy('name')->get();

        return response()->json([
            'places' => $places,
        ]);
    }

    /**
     * Сохранение новой вещи — только для администратора (JSON)
     */
    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'wrnt'        => 'nullable|date',
            'amount'      => 'required|integer|min:1',
            'place_id'    => 'nullable|exists:places,id',
            'unit_id'     => 'nullable|exists:units,id',
        ]);

        $thing = auth()->user()->things()->create($validated);
        event(new \App\Events\ThingCreated($thing));

        Cache::forget("things.my.paginated.api." . auth()->id());

        return response()->json([
            'message' => 'Вещь успешно добавлена!',
            'data' => $thing,
        ], 201);
    }

    /**
     * Детальная информация о вещи (JSON)
     */
    public function show(Thing $thing)
    {
        $thing->load(['place', 'unit', 'usages']);

        return response()->json($thing);
    }

    /**
     * Форма редактирования вещи — только для администратора (JSON)
     */
    public function edit(Thing $thing)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $places = Place::orderBy('name')->get();

        return response()->json([
            'thing' => $thing,
            'places' => $places,
        ]);
    }

    /**
     * Обновление вещи — только для администратора (JSON)
     */
    public function update(Request $request, Thing $thing)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'wrnt'        => 'nullable|date',
            'amount'      => 'required|integer|min:1',
            'place_id'    => 'nullable|exists:places,id',
            'unit_id'     => 'nullable|exists:units,id',
        ]);

        $thing->update($validated);

        Cache::forget("things.my.paginated.api." . $thing->master_id);

        return response()->json([
            'message' => 'Вещь успешно обновлена!',
            'data' => $thing,
        ]);
    }

    /**
     * Удаление вещи — только для администратора (JSON)
     */
    public function destroy(Thing $thing)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $ownerId = $thing->master_id;
        $thing->delete();

        Cache::forget("things.my.paginated.api.{$ownerId}");

        return response()->json(['message' => 'Вещь удалена!']);
    }

    /**
     * Форма передачи вещи (JSON)
     */
    public function transfer(Thing $thing)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $users = User::where('id', '!=', auth()->id())->get();

        return response()->json([
            'thing' => $thing,
            'users' => $users,
        ]);
    }

    /**
     * Передача вещи (JSON)
     */
    public function transferStore(Request $request, Thing $thing)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|not_in:' . auth()->id(),
            'amount'  => [
                'required',
                'integer',
                'min:1',
                'max:' . $thing->available_amount,
            ],
        ]);

        Usage::create([
            'thing_id' => $thing->id,
            'user_id'  => $validated['user_id'],
            'amount'   => $validated['amount'],
            'place_id' => null,
        ]);

        $thing->decrement('amount', $validated['amount']);

        Cache::forget("things.my.paginated.api." . auth()->id());

        return response()->json([
            'message' => 'Вещь успешно передана!',
        ], 200);
    }

    /**
     * Вещи, которые мне передали (JSON)
     */
    public function received()
    {
        $received = auth()->user()->receivedThings()
            ->withPivot('amount')
            ->latest()
            ->paginate(10);

        return response()->json($received);
    }

    /**
     * Возврат вещи владельцу (JSON)
     */
    public function returnThing(Thing $thing)
    {
        $user = auth()->user();

        if (!$user->receivedThings()->where('thing_id', $thing->id)->exists()) {
            return response()->json(['error' => 'Эта вещь не была вам передана.'], 403);
        }

        $pivot = $user->receivedThings()->where('thing_id', $thing->id)->first()->pivot;
        $amount = $pivot->amount;

        $thing->increment('amount', $amount);
        $user->receivedThings()->detach($thing->id);

        return response()->json(['message' => 'Вещь успешно возвращена владельцу!']);
    }

    /**
     * Мои вещи (JSON)
     */
    public function myThings()
    {
        $things = auth()->user()->things()->latest()->paginate(10);
        $title = 'Мои вещи';

        return response()->json([
            'title' => $title,
            'things' => $things,
        ]);
    }

    /**
     * В ремонте (JSON)
     */
    public function repairThings()
    {
        $things = Thing::whereHas('place', fn($q) => $q->where('repair', true))
            ->latest()
            ->paginate(10);
        $title = 'В ремонте';

        return response()->json([
            'title' => $title,
            'things' => $things,
        ]);
    }

    /**
     * В работе (JSON)
     */
    public function workThings()
    {
        $things = Thing::whereHas('place', fn($q) => $q->where('work', true))
            ->latest()
            ->paginate(10);
        $title = 'В работе';

        return response()->json([
            'title' => $title,
            'things' => $things,
        ]);
    }

    /**
     * Переданные мной (JSON)
     */
    public function usedThings()
    {
        $things = Thing::whereHas('usages', fn($q) => $q->where('user_id', '!=', auth()->id()))
            ->latest()
            ->paginate(10);
        $title = 'Переданные мной';

        return response()->json([
            'title' => $title,
            'things' => $things,
        ]);
    }

    /**
     * Все вещи (JSON) — только админ
     */
    public function allThings()
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $source = 'из кэша (загрузка мгновенная)';

        $things = Cache::remember(
            'things.all.paginated.api',
            now()->addMinutes(30),
            function () use (&$source) {
                $source = 'из базы данных (первый запрос)';
                return Thing::with(['place', 'unit'])
                    ->withSum('usages as used_amount', 'amount')
                    ->latest()
                    ->paginate(10);
            }
        );

        $title = 'Все вещи';

        return response()->json([
            'title' => $title,
            'things' => $things,
            'source' => $source,
        ]);
    }

    private function authorizeThing(Thing $thing): void
    {
        if ($thing->master_id !== auth()->id()) {
            abort(403, 'Это не ваша вещь');
        }
    }
}
