<?php

namespace App\Http\Controllers;

use App\Models\Thing;
use App\Models\User;
use App\Models\Usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThingController extends Controller
{
    /**
     * Список вещей текущего пользователя с кэшированием
     */
    public function index()
    {
        $userId = auth()->id();

        $source = 'из кэша (загрузка мгновенная)';

        $things = Cache::remember(
            "things.my.paginated.{$userId}",
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

        return view('things.index', compact('things', 'source'));
    }

    public function create()
    {
        return view('things.create');
    }

    public function store(Request $request)
    {
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

        Cache::forget("things.my.paginated." . auth()->id());

        return redirect()->route('things.index')
            ->with('success', 'Вещь успешно добавлена!');
    }

    public function show(Thing $thing)
    {
        $thing->load(['place', 'unit', 'usages']);

        return view('things.show', compact('thing'));
    }

    public function edit(Thing $thing)
    {
        $this->authorizeThing($thing);

        return view('things.edit', compact('thing'));
    }

    public function update(Request $request, Thing $thing)
    {
        $this->authorizeThing($thing);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'wrnt'        => 'nullable|date',
            'amount'      => 'required|integer|min:1',
            'place_id'    => 'nullable|exists:places,id',
            'unit_id'     => 'nullable|exists:units,id',
        ]);

        $thing->update($validated);

        Cache::forget("things.my.paginated." . $thing->master_id);

        return redirect()->route('things.index')
            ->with('success', 'Вещь успешно обновлена!');
    }

    public function destroy(Thing $thing)
    {
        $this->authorizeThing($thing);

        $ownerId = $thing->master_id;
        $thing->delete();

        Cache::forget("things.my.paginated.{$ownerId}");

        return redirect()->route('things.index')
            ->with('success', 'Вещь удалена!');
    }

    public function transfer(Thing $thing)
    {
        $this->authorizeThing($thing);

        $users = User::where('id', '!=', auth()->id())->get();

        return view('things.transfer', compact('thing', 'users'));
    }

    public function transferStore(Request $request, Thing $thing)
    {
        $this->authorizeThing($thing);

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

        Cache::forget("things.my.paginated." . auth()->id());

        return redirect()->route('things.index')
            ->with('success', 'Вещь успешно передана!');
    }

    public function received()
    {
        $received = auth()->user()->receivedThings()
            ->withPivot('amount')
            ->latest()
            ->paginate(10);

        return view('things.received', compact('received'));
    }

    public function returnThing(Thing $thing)
    {
        $user = auth()->user();

        if (!$user->receivedThings()->where('thing_id', $thing->id)->exists()) {
            return redirect()->route('received.things')
                ->with('error', 'Эта вещь не была вам передана.');
        }

        $pivot = $user->receivedThings()->where('thing_id', $thing->id)->first()->pivot;
        $amount = $pivot->amount;

        $thing->increment('amount', $amount);
        $user->receivedThings()->detach($thing->id);

        return redirect()->route('received.things')
            ->with('success', 'Вещь успешно возвращена владельцу!');
    }

    public function myThings()
    {
        $things = auth()->user()->things()->latest()->paginate(10);
        $title = 'Мои вещи';

        return view('things.list', compact('things', 'title'));
    }

    public function repairThings()
    {
        $things = Thing::whereHas('place', fn($q) => $q->where('repair', true))
            ->latest()
            ->paginate(10);
        $title = 'В ремонте';

        return view('things.list', compact('things', 'title'));
    }

    public function workThings()
    {
        $things = Thing::whereHas('place', fn($q) => $q->where('work', true))
            ->latest()
            ->paginate(10);
        $title = 'В работе';

        return view('things.list', compact('things', 'title'));
    }

    public function usedThings()
    {
        $things = Thing::whereHas('usages', fn($q) => $q->where('user_id', '!=', auth()->id()))
            ->latest()
            ->paginate(10);
        $title = 'Переданные мной';

        return view('things.list', compact('things', 'title'));
    }

   public function allThings()
{
    $source = 'из кэша (загрузка мгновенная)';

    $things = Cache::remember(
        'things.all.paginated', 
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

    return view('things.list', compact('things', 'title', 'source'));
}

    private function authorizeThing(Thing $thing): void
    {
        if ($thing->master_id !== auth()->id()) {
            abort(403, 'Это не ваша вещь');
        }
    }
}
