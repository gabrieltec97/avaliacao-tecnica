@extends('layout.app')

@section('title', 'Edição de contato')

@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <!-- Coluna do título -->
                            <div class="col-md-6 col-12 mb-3">
                                <h5 class="mb-0">Editar contato</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-2">
                        <div class="container-fluid">
                            <form action="{{ route('contatos.update', $contact->id )}}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="row pb-2">
                                    <div class="col-12 col-md-3 first-item">
                                        <span class="font-weight-bold modal-label">Nome:</span>
                                        <input type="text" name="name" value="{{ $contact->name }}" placeholder="Digite o nome" class="form-control mt-2"  required>
                                    </div>

                                    <div class="col-12 col-md-3 mt-3 mt-lg-0">
                                        <span class="font-weight-bold modal-label">Telefone:</span>
                                        <input type="number" name="phone" value="{{ $contact->phone }}" placeholder="Digite o telefone" class="form-control mt-2" required>
                                    </div>

                                    <div class="col-12 col-md-3 mt-3 mt-lg-0">
                                        <span class="font-weight-bold modal-label">E-mail:</span>
                                        <input type="email" name="email" value="{{ $contact->email }}" placeholder="Digite o e-mail" class="form-control mt-2"  required>
                                    </div>

                                    <div class="col-12 col-md-3 mt-3 mt-lg-0">
                                        <span class="font-weight-bold modal-label">Cep:</span>
                                        <input type="text" name="cep" value="{{ $contact->cep }}" placeholder="Digite o cep" class="form-control mt-2"  required>
                                    </div>

                                    <div class="col-12 mt-3 d-flex justify-content-end">
                                        <button class="btn btn-custom" type="submit">
                                            <span class="button-text"><i class="fa-solid fa-floppy-disk mr-4"></i> Salvar alterações</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('msg-error'))
            <script>
                const notyf = new Notyf({
                    position: {
                        x: 'right',
                        y: 'top',
                    }
                });

                notyf
                    .error({
                        message: '{{ session('msg-error') }}',
                        dismissible: true,
                        duration: 4000
                    });
            </script>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    const notyf = new Notyf({
                        position: {
                            x: 'right',
                            y: 'top',
                        }
                    });

                    notyf
                        .error({
                            message: '{{ $error }}',
                            dismissible: true,
                            duration: 4000
                        });
                </script>
            @endforeach
        @endif
    </div>
@endsection
