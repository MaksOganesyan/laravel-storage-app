<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Place::class, 'place');
    }

    public function index()
    {
        $places = Place::latest()->paginate(10);
        return view('places.index', compact('places'));
    }

    public function create()
    {
        return view('places.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Place::create($request->all());

        return redirect()->route('places.index')->with('success', 'Место добавлено!');
    }

    public function edit(Place $place)
    {
        return view('places.edit', compact('place'));
    }

    public function update(Request $request, Place $place)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $place->update($request->all());

        return redirect()->route('places.index')->with('success', 'Место обновлено!');
    }

    public function destroy(Place $place)
    {
        $place->delete();

        return redirect()->route('places.index')->with('success', 'Место удалено!');
    }
}
