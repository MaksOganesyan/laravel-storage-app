<?php

namespace App\Http\Controllers;

use App\Models\Thing;
use App\Models\User;
use App\Models\Usage;
use Illuminate\Http\Request;

class ThingController extends Controller
{
    /**
     * Показать список всех вещей текущего пользователя
     */
    public function index()
    {
        $things = auth()->user()->things()->latest()->get();
        return view('things.index', compact('things'));
    }

    /**
     * Показать форму для создания новой вещи
     */
    public function create()
    {
        return view('things.create');
    }

    /**
     * Сохранить новую вещь
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'wrnt' => 'nullable|date',
        ]);

        auth()->user()->things()->create($request->all());

        return redirect()->route('things.index')->with('success', 'Вещь успешно добавлена!');
    }

    /**
     * Показать форму для редактирования вещи
     */
    public function edit(Thing $thing)
    {
        $this->authorizeThing($thing);
        return view('things.edit', compact('thing'));
    }

    /**
     * Обновить вещь
     */
    public function update(Request $request, Thing $thing)
    {
        $this->authorizeThing($thing);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'wrnt' => 'nullable|date',
        ]);

        $thing->update($request->all());

        return redirect()->route('things.index')->with('success', 'Вещь обновлена!');
    }

    /**
     * Удалить вещь
     */
    public function destroy(Thing $thing)
    {
        $this->authorizeThing($thing);

        $thing->delete();

        return redirect()->route('things.index')->with('success', 'Вещь удалена!');
    }

    /**
     * Показать форму передачи вещи другому пользователю
     */
    public function transfer(Thing $thing)
    {
        $this->authorizeThing($thing);

        // Все пользователи кроме текущего
        $users = User::where('id', '!=', auth()->id())->get();

        return view('things.transfer', compact('thing', 'users'));
    }

    /**
     * Сохранить передачу вещи
     */
    public function transferStore(Request $request, Thing $thing)
    {
        $this->authorizeThing($thing);

        $request->validate([
            'user_id' => 'required|exists:users,id|not_in:' . auth()->id(),
            'amount' => 'required|integer|min:1',
        ]);

        Usage::create([
            'thing_id' => $thing->id,
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'place_id' => null, // можно добавить выбор места позже
        ]);

        return redirect()->route('things.index')->with('success', 'Вещь успешно передана!');
    }

    /**
     * Проверка, что вещь принадлежит текущему пользователю
     */
    private function authorizeThing(Thing $thing)
    {
        if ($thing->master_id !== auth()->id()) {
            abort(403, 'У вас нет доступа к этой вещи.');
        }
    }
}
