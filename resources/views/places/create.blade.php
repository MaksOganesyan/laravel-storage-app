@extends('layouts.app')

@section('title', 'Добавить место хранения')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Добавить новое место хранения</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('places.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Название <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="repair" class="form-check-input" id="repair" {{ old('repair') ? 'checked' : '' }}>
                <label class="form-check-label" for="repair">Это место для ремонта / мойки</label>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="work" class="form-check-input" id="work" {{ old('work', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="work">Место в работе (активно)</label>
            </div>

            <button type="submit" class="btn btn-success">Сохранить место</button>
            <a href="{{ route('places.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection
