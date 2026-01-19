<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PlaceController extends Controller
{
    /**
     * Список всех мест с кэшированием
     * После первого запроса данные берутся из кэша (быстрая загрузка)
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
                    ->withCount('things') // сколько вещей в месте
                    ->get();
            }
        );

        return view('places.index', compact('places', 'source'));
    }

    /**
     * Детальная страница одного места с кэшированием
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
                                 $query->latest()->take(10); // последние 10 вещей
                             }]);
            }
        );

        return view('places.show', compact('place', 'source'));
    }

    /**
     * Форма добавления нового места
     */
    public function create()
    {
        return view('places.create');
    }

    /**
     * Сохранение нового места
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $validated['repair'] = $request->boolean('repair');
    $validated['work']   = $request->boolean('work');

    $place = Place::create($validated);

    event(new \App\Events\PlaceCreated($place)); // ← отправляем событие

    Cache::forget('places.all');

    return redirect()->route('places.index')
        ->with('success', 'Место успешно добавлено!');
}

    /**
     * Форма редактирования места
     */
    public function edit(Place $place)
    {
        return view('places.edit', compact('place'));
    }

    /**
     * Обновление места
     */
    public function update(Request $request, Place $place)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['repair'] = $request->boolean('repair');
        $validated['work']   = $request->boolean('work');

        $place->update($validated);

        // Очищаем кэш
        Cache::forget('places.all');
        Cache::forget("place.{$place->id}");

        return redirect()->route('places.index')
            ->with('success', 'Место обновлено!');
    }

    /**
     * Удаление места
     */
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
