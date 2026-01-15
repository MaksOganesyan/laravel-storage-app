<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /**
     * Показать список всех мест текущего пользователя
     */
    public function index()
    {
        $places = auth()->user()->places()->latest()->get();
        return view('places.index', compact('places'));
    }

    /**
     * Показать форму для создания нового места
     */
    public function create()
    {
        return view('places.create');
    }

    /**
     * Сохранить новое место
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        auth()->user()->places()->create($request->all());

        return redirect()->route('places.index')->with('success', 'Место хранения успешно добавлено!');
    }

    /**
     * Показать форму для редактирования места
     */
    public function edit(Place $place)
    {
        $this->authorizePlace($place);
        return view('places.edit', compact('place'));
    }

    /**
     * Обновить место
     */
    public function update(Request $request, Place $place)
    {
        $this->authorizePlace($place);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $place->update($request->all());

        return redirect()->route('places.index')->with('success', 'Место обновлено!');
    }

    /**
     * Удалить место
     */
    public function destroy(Place $place)
    {
        $this->authorizePlace($place);

        $place->delete();

        return redirect()->route('places.index')->with('success', 'Место удалено!');
    }

    /**
     * Проверка, что место принадлежит текущему пользователю
     */
    private function authorizePlace(Place $place)
    {
        if ($place->user_id !== auth()->id()) {
            abort(403, 'У вас нет доступа к этому месту хранения.');
        }
    }
}
