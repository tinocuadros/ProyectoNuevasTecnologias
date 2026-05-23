
@extends('layouts.app')

@section('contenido')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-header bg-custom text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Mi Perfil</h5>
                </div>
                <div class="card-body">
                    <form id="perfilForm">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Cédula</label>
                                <input type="text" name="cedula" class="form-control" value="{{ $user->cedula }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Primer Nombre *</label>
                                <input type="text" name="primer_nombre" class="form-control" value="{{ $user->primer_nombre }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Segundo Nombre</label>
                                <input type="text" name="segundo_nombre" class="form-control" value="{{ $user->segundo_nombre }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Primer Apellido *</label>
                                <input type="text" name="primer_apellido" class="form-control" value="{{ $user->primer_apellido }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Segundo Apellido</label>
                                <input type="text" name="segundo_apellido" class="form-control" value="{{ $user->segundo_apellido }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn bg-custom-btn">
                                <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                            </button>
                            <button type="button" class="btn bg-custom-btn" data-bs-toggle="modal" data-bs-target="#cambiarContrasenaModal">
                                <i class="bi bi-key me-1"></i> Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal fade" id="cambiarContrasenaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-key me-2"></i>Cambiar Contraseña</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cambiarContrasenaForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nueva Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" autocomplete="off" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="off" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña Actual *</label>
                        <input type="password" name="current_password" class="form-control" autocomplete="off" required>
                        <small class="text-muted">Ingresa tu contraseña actual para confirmar</small>
                    </div>
                    <button type="submit" class="btn bg-custom-btn w-100">
                        <i class="bi bi-check-lg me-1"></i> Actualizar Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script>
$(document).ready(function() {
    console.log('jQuery listo');
    
    $('#perfilForm').on('submit', function(e) {
        e.preventDefault();
        console.log('Formulario perfil enviado');
        
        $.ajax({
            url: '{{ url("/perfil") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(data) {
                console.log(data);
                if(data.success) {
                    alert('Perfil actualizado correctamente');
                } else {
                    alert('Error: ' + data.message);
                }
            }
        });
    });

    $('#cambiarContrasenaForm').on('submit', function(e) {
        e.preventDefault();
        
        if($('#password').val() !== $('#password_confirmation').val()) {
            alert('Las contraseñas no coinciden');
            return;
        }

        $.ajax({
            url: '{{ url("/perfil/contrasena") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(data) {
                if(data.success) {
                    alert('Contraseña actualizada correctamente');
                    $('#cambiarContrasenaModal').modal('hide');
                    $('#cambiarContrasenaForm')[0].reset();
                } else {
                    alert('Error: ' + data.message);
                }
            }
        });
    });
});
</script>
@endsection