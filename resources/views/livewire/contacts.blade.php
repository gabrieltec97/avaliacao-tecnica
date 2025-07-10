<div>
    <input type="search" class="form-control mb-2" wire:model.live.debounce.150ms="searchTerm">

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th class="ps-2 text-start text-xxs font-weight-bolder opacity-7">Nome</th>
                <th class="text-start text-xxs font-weight-bolder opacity-7">Telefone</th>
                <th class= text-xxs font-weight-bolder opacity-7 ps-2">E-mail</th>
                <th class="text-center text-xxs font-weight-bolder opacity-7">Cep</th>
                <th class="text-center text-xxs font-weight-bolder opacity-7">Ações</th>

            </tr>
            </thead>
            <tbody>
            @if($contacts && $contacts->count() > 0)
                @foreach($contacts as $contact)
                    <tr>
                        <td class="ps-2 align-middle">
                            <h6 class="text-sm" style="text-decoration: none;">{{ $contact->name }}</a></h6>
                        </td>
                        <td class="align-middle">
                            <h6 class="text-sm">{{ $contact->phone }}</a></h6>
                        </td>
                        <td class="align-middle">
                            <h6 class="text-sm">{{ $contact->email }}</a></h6>
                        </td>
                        <td class="text-center align-middle">
                            <h6 class="text-sm">{{ $contact->cep }}</a></h6>
                        </td>
                        <td class="text-center align-middle">
                            <a id="edit-contact" href="{{ route('contatos.edit', $contact->id) }}" class="text-primary me-2">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            <button type="button" class="btn btn-link p-0 m-0 align-baseline" data-bs-toggle="modal" data-bs-target="#deletecontact{{ $contact->id }}">
                                <i class="fa-solid fa-trash-can text-danger"></i>
                            </button>
                        </td>

                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="deletecontact{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header d-flex align-items-center justify-content-between">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Deletar contato</h5>
                                    <i class="fa-solid fa-circle-xmark text-danger" data-bs-dismiss="modal" style="cursor: pointer;"></i>
                                </div>

                                <div class="modal-body">
                                    <span>Tem certeza que deseja excluir o contato de {{ $contact->name }}?</span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Voltar</button>
                                    <form action="{{ route('contatos.destroy', $contact->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger cursor-pointer">Deletar contato</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <tr>
                    <td class="text-danger">Sem registros com este nome</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    @if(session('msg-success'))
        <script>
            const notyf = new Notyf({
                position: {
                    x: 'right',
                    y: 'top',
                }
            });

            notyf
                .success({
                    message: '{{ session('msg-success') }}',
                    dismissible: true,
                    duration: 4000
                });
        </script>
    @endif
</div>
