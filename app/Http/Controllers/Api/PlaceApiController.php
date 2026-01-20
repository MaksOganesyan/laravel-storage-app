<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PlaceApiController extends Controller
{
    public function index()
    {
        $source = 'из кэша (загрузка мгновенная)';

        $places = Cache::remember(
            'places.all.api',
            now()->addMinutes(30),
            function () use (&$source) {
                $source = 'из базы данных (первый запрос)';
                return Place::orderBy('name')
                    ->withCount('things') 
                    ->get();
            }
        );

        return response()->json([
            'data' => $places,
            'source' => $source,
        ]);
    }

    /**
     * Детальная информация об одном месте 
     */
    public function show(Place $place)
    {
        $source = 'из кэша (загрузка мгновенная)';

        $place = Cache::remember(
            "place.api.{$place->id}",
            now()->addMinutes(60),
            function () use ($place, &$source) {
                $source = 'из базы данных (первый запрос)';
                return $place->loadCount('things')
                             ->load(['things' => function ($query) {
                                 $query->latest()->take(10); // последние 10 вещей
                             }]);
            }
        );

        return response()->json([
            'data' => $place,
            'source' => $source,
        ]);
    }

    /**
     * Сохранение нового места (только админ)
     */
    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['repair'] = $request->boolean('repair');
        $validated['work']   = $request->boolean('work');

        $place = Place::create($validated);

        event(new \App\Events\PlaceCreated($place)); // событие остаётся

        Cache::forget('places.all.api');

        return response()->json([
            'message' => 'Место успешно добавлено!',
            'data' => $place
        ], 201);
    }

    /**
     * Обновление места (только админ)
     */
    public function update(Request $request, Place $place)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['repair'] = $request->boolean('repair');
        $validated['work']   = $request->boolean('work');

        $place->update($validated);

        Cache::forget('places.all.api');
        Cache::forget("place.api.{$place->id}");

        return response()->json([
            'message' => 'Место обновлено!',
            'data' => $place
        ]);
    }

    /**
     * Удаление места (только админ)
     */
    public function destroy(Place $place)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещён. Только для администратора.'], 403);
        }

        $place->delete();

        Cache::forget('places.all.api');
        Cache::forget("place.api.{$place->id}");

        return response()->json([
            'message' => 'Место удалено!'
        ]);
    }
}
