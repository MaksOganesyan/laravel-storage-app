@extends('layouts.app')

@section('title', 'Редактировать место хранения')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Редактировать место хранения</h1>

        <form action="{{ route('places.update', $place) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Название *</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $place->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $place->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_repair" id="is_repair" class="form-check-input" {{ old('is_repair', $place->is_repair ?? false) ? 'checked' : '' }}>
                <label for="is_repair" class="form-check-label">Это место для ремонта / мойки</label>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active', $place->is_active ?? true) ? 'checked' : '' }}>
                <label for="is_active" class="form-check-label">Место в работе (активно)</label>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="{{ route('places.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection
