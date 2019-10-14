@extends('layout')

@section('title','Proyectos')

@section('content')
	<h1>Proyectos</h1>
@auth
			<a href="{{ route('projects.create')}}">Crear Proyecto</a>
@endauth
	<ul>
			@forelse($projects as $project)
				<li><a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a></li>
			@empty
				<li>No hay Proyectos</li>
			@endforelse
			{{ $projects->links() }}

	</ul>
@endsection