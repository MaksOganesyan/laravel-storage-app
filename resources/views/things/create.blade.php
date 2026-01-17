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
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Гарантия / срок годности</label>
                <input type="date" name="wrnt" class="form-control @error('wrnt') is-invalid @enderror" value="{{ old('wrnt') }}">
                @error('wrnt')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Количество <span class="text-danger">*</span></label>
                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
                       value="{{ old('amount', 1) }}" min="1" required>
                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Размерность количества — вот это по ТЗ допа №6 -->
            <div class="mb-3">
                <label for="unit_id" class="form-label">Размерность количества</label>
                <select name="unit_id" id="unit_id" class="form-select @error('unit_id') is-invalid @enderror">
                    <option value="">-- Не указывать --</option>
                    @foreach(\App\Models\Unit::all() as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }} ({{ $unit->short }})
                        </option>
                    @endforeach
                </select>
                @error('unit_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="place_id" class="form-label">Место хранения</label>
                <select name="place_id" id="place_id" class="form-select @error('place_id') is-invalid @enderror">
                    <option value="">-- Не выбрано --</option>
                    @foreach(auth()->user()->places as $place)
                        <option value="{{ $place->id }}" {{ old('place_id') == $place->id ? 'selected' : '' }}>
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
