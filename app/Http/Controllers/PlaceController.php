<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /**
     * Показать список всех мест (только админ)
     */
    public function index()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Доступ только для администратора.');
        }

        $places = Place::latest()->paginate(10);
        return view('places.index', compact('places'));
    }

    /**
     * Показать форму для создания нового места (только админ)
     */
    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Доступ только для администратора.');
        }

        return view('places.create');
    }

    /**
     * Сохранить новое место (только админ)
     */
    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Доступ только для администратора.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Place::create($request->all());  // админ создаёт любое место

        return redirect()->route('places.index')->with('success', 'Место хранения успешно добавлено!');
    }

    /**
     * Показать форму для редактирования места (только админ)
     */
    public function edit(Place $place)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Доступ только для администратора.');
        }

        return view('places.edit', compact('place'));
    }

    /**
     * Обновить место (только админ)
     */
    public function update(Request $request, Place $place)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Доступ только для администратора.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $place->update($request->all());

        return redirect()->route('places.index')->with('success', 'Место обновлено!');
    }

    /**
     * Удалить место (только админ)
     */
    public function destroy(Place $place)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Доступ только для администратора.');
        }

        $place->delete();

        return redirect()->route('places.index')->with('success', 'Место удалено!');
    }
}
