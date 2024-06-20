<?php

use App\Models\User;
$users = User::with('roles')->get();
?>

@extends('tablar::page')
@section('content')
    <!-- Page header -->

    <!-- Page body -->
    <div class="row row-cards">

        @foreach ($users as $user)
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <span class="avatar avatar-xl mb-3 rounded"
                            style="background-image: url(./static/avatars/000m.jpg)"></span>
                        <h3 class="m-0 mb-1"><a href="#">{{ $user->name }}</a></h3>
                        <div class="text-secondary">
                            @foreach ($user->roles as $role)
                                {{ $role->name }}
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-purple-lt">Activo</span>
                        </div>
                    </div>
                    <div class="d-flex">
                        <a href="{{ route('usuarios.edit', $user->id) }}" class="card-btn">
                            <!-- Download SVG icon from http://tabler-icons.io/i/mail -->

                            Editar

                        </a>
                        <a href="#" class="card-btn">
                            <!-- Download SVG icon from http://tabler-icons.io/i/phone -->

                            Eliminar
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
