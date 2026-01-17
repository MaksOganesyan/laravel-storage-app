<?php

namespace App\Http\Controllers;

use App\Models\Thing;
use App\Models\User;
use App\Models\Usage;
use Illuminate\Http\Request;

class ThingController extends Controller
{
    public function index()
    {
        $things = auth()->user()->things()
            ->with('usages')
            ->latest()
            ->paginate(10);  
        foreach ($things as $thing) {
        $used = $thing->usages->sum('amount'); 
        $thing->available_amount = $thing->amount - $used;
    }

        return view('things.index', compact('things'));
    }

    public function create()
    {
        return view('things.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'wrnt'        => 'nullable|date',
            'amount'      => 'required|integer|min:1',
            'place_id'    => 'nullable|exists:places,id',

        ]);

        auth()->user()->things()->create($request->all());

        return redirect()->route('things.index')
            ->with('success', 'Вещь успешно добавлена!');
    }

    public function edit(Thing $thing)
    {
        $this->authorizeThing($thing);
        return view('things.edit', compact('thing'));
    }

    public function update(Request $request, Thing $thing)
    {
        $this->authorizeThing($thing);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'wrnt'        => 'nullable|date',
            'amount'      => 'required|integer|min:1',
            'place_id'    => 'nullable|exists:places,id',

        ]);

        $thing->update($request->all());

        return redirect()->route('things.index')
            ->with('success', 'Вещь обновлена!');
    }

    public function destroy(Thing $thing)
    {
        $this->authorizeThing($thing);
        $thing->delete();

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

        $request->validate([
            'user_id' => 'required|exists:users,id|not_in:' . auth()->id(),
            'amount'  => [
                'required',
                'integer',
                'min:1',
                'max:' . $thing->available_amount,
            ],
        ]);

        if ($request->amount > $thing->available_amount) {
            return back()->withErrors(['amount' => 'Нельзя передать больше, чем есть'])->withInput();
        }

        Usage::create([
            'thing_id' => $thing->id,
            'user_id'  => $request->user_id,
            'amount'   => $request->amount,
            'place_id' => null,
        ]);

        $thing->decrement('amount', $request->amount);

        return redirect()->route('things.index')
            ->with('success', 'Вещь успешно передана!');
    }

    public function received()
    {
        $received = auth()->user()->receivedThings()
            ->withPivot('amount')
            ->paginate(10);  

        return view('things.received', compact('received'));
    }

    public function returnThing(Thing $thing)
    {
        $user = auth()->user();

        if ($user->receivedThings()->where('thing_id', $thing->id)->exists()) {
            $pivot = $user->receivedThings()->where('thing_id', $thing->id)->first()->pivot;
            $amount = $pivot->amount;

            $thing->increment('amount', $amount);

            $user->receivedThings()->detach($thing->id);

            session()->flash('success', 'Вещь успешно возвращена владельцу!');
        } else {
            session()->flash('error', 'Эта вещь не была вам передана.');
        }

        return redirect()->route('received.things');
    }

    private function authorizeThing(Thing $thing)
    {
        if ($thing->master_id !== auth()->id()) {
            abort(403, 'У вас нет доступа к этой вещи.');
        }
    }
    /**
 * Мои вещи (где я хозяин)
 */
public function myThings()
{
    $things = auth()->user()->things()->latest()->paginate(10);
    $title = 'My things';
    return view('things.list', compact('things', 'title'));
}

/**
 * Вещи в специальных местах (repair = true)
 */
public function repairThings()
{
    $things = Thing::whereHas('place', fn($q) => $q->where('repair', true))
        ->latest()
        ->paginate(10);
    $title = 'В ремонте ';
    return view('things.list', compact('things', 'title'));
}

/**
 * Вещи в работе (work = true)
 */
public function workThings()
{
    $things = Thing::whereHas('place', fn($q) => $q->where('work', true))
        ->latest()
        ->paginate(10);
    $title = 'В работе';
    return view('things.list', compact('things', 'title'));
}

/**
 * Вещи, которые я передал другим (Used things)
 */
public function usedThings()
{
    $things = Thing::whereHas('usages', fn($q) => $q->where('user_id', '!=', auth()->id()))
        ->latest()
        ->paginate(10);
    $title = 'Переданные мною';
    return view('things.list', compact('things', 'title'));
}

/**
 * Общий список всех вещей (для админа/проверки)
 */
public function allThings()
{
    if (!auth()->user()->is_admin) {
        abort(403, 'Доступ только для администратора.');
    }
    $things = Thing::latest()->paginate(10);
    $title = 'Все вещи';
    return view('things.list', compact('things', 'title'));
}
public function show(Thing $thing)
{
    $this->authorizeThing($thing);
    return view('things.show', compact('thing'));
}
}
