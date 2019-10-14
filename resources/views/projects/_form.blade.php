@csrf
<label>
	Titulo del proyecto <br>
	@include('partials.validation-errors')
	<input type="text" name="title" value="{{ old('title', $project->title) }}">
</label>
<br>
<label>
	Descripción del proyecto <br>
	<textarea name="description">{{ old('description', $project->description) }}</textarea>
</label>
<br>
<button>{{ $btnText }}</button>