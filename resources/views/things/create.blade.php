@extends('layouts.app')

@section('title', 'Добавить вещь')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Добавить новую вещь</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('things.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Название <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Гарантия / срок годности</label>
                <input type="date" name="wrnt" class="form-control" value="{{ old('wrnt') }}">
            </div>
            <div class="mb-3">
    <label for="amount" class="form-label">Количество <span class="text-danger">*</span></label>
    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
           value="{{ old('amount', 1) }}" min="1" required>
    @error('amount')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
            <div class="mb-3">
    <label for="place_id" class="form-label">Место хранения</label>
    <select name="place_id" id="place_id" class="form-control @error('place_id') is-invalid @enderror">
        <option value="">-- Не выбрано --</option>
        @foreach(auth()->user()->places as $place)
            <option value="{{ $place->id }}" {{ old('place_id', $thing->place_id ?? '') == $place->id ? 'selected' : '' }}>
                {{ $place->name }}
            </option>
        @endforeach
    </select>
    @error('place_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

            <button type="submit" class="btn btn-success">Сохранить вещь</button>
            <a href="{{ route('things.index') }}" class="btn btn-secondary">Отмена</a>
            
        </form>
        
    </div>
@endsection
