@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Gerenciar Cursos</h3>
        <a href="{{ route('admin.panel') }}" class="btn btn-secondary">Voltar ao Painel</a>
    </div>

    {{-- Adicionar Curso --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Adicionar Novo Curso</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.courses.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-8">
                    <label class="form-label" for="course-name">Nome do Curso</label>
                    <input type="text" id="course-name" name="name" class="form-control" placeholder="Ex.: Inglês Básico" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus me-1"></i> Adicionar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Lista de Cursos --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Cursos Existentes</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $course->course_name }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editCourseModal" onclick="setEditAction({{ $course->course_id }}, '{{ addslashes($course->course_name) }}')"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCourseModal" onclick="setDeleteAction({{ $course->course_id }})"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Nenhum curso cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="deleteCourseForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Excluir Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <strong>ATENÇÃO:</strong> Esta ação é <span class="text-danger">permanente e irreversível</span>.<br>
                    Tem certeza que deseja excluir este curso para sempre?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editCourseForm" method="POST">
            @csrf
            
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome do Curso</label>
                        <input type="text" class="form-control" name="name" id="editCourseName" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function setDeleteAction(id){
    document.getElementById('deleteCourseForm').action = `/admin/courses/${id}`;
}
function setEditAction(id,name){
    document.getElementById('editCourseForm').action = `/admin/courses/${id}`;
    document.getElementById('editCourseName').value = name;
}
</script>
@endpush
@endsection
