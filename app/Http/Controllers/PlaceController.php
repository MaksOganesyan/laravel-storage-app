<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PlaceController extends Controller
{
    /**
     * Список всех мест с кэшированием
     */
    public function index()
    {
        $source = 'из кэша (загрузка мгновенная)';

        $places = Cache::remember(
            'places.all',
            now()->addMinutes(30),
            function () use (&$source) {
                $source = 'из базы данных (первый запрос)';
                return Place::orderBy('name')
                    ->withCount('things')  // сколько вещей в месте
                    ->get();
            }
        );

        return view('places.index', compact('places', 'source'));
    }

    /**
     * Детальная страница места с кэшированием
     */
    public function show(Place $place)
    {
        $source = 'из кэша (загрузка мгновенная)';

        $place = Cache::remember(
            "place.{$place->id}",
            now()->addMinutes(60),
            function () use ($place, &$source) {
                $source = 'из базы данных (первый запрос)';
                return $place->loadCount('things')
                             ->load(['things' => function ($query) {
                                 $query->latest()->take(10);
                             }]);
            }
        );

        return view('places.show', compact('place', 'source'));
    }

    public function create()
    {
        return view('places.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'repair'      => 'boolean',
            'work'        => 'boolean',
        ]);

        Place::create($validated);

        // Очищаем кэш списка мест
        Cache::forget('places.all');

        return redirect()->route('places.index')
            ->with('success', 'Место успешно добавлено!');
    }

    public function edit(Place $place)
    {
        return view('places.edit', compact('place'));
    }

    public function update(Request $request, Place $place)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'repair'      => 'boolean',
            'work'        => 'boolean',
        ]);

        $place->update($validated);

        // Очищаем кэш
        Cache::forget('places.all');
        Cache::forget("place.{$place->id}");

        return redirect()->route('places.index')
            ->with('success', 'Место обновлено!');
    }

    public function destroy(Place $place)
    {
        $place->delete();

        // Очищаем кэш
        Cache::forget('places.all');
        Cache::forget("place.{$place->id}");

        return redirect()->route('places.index')
            ->with('success', 'Место удалено!');
    }
}
