<div>
    <input type="search" class="form-control mb-2" wire:model.live.debounce.150ms="searchTerm">

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="ps-2 text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome</th>
                <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Telefone</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">E-mail</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cep</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ações</th>

            </tr>
            </thead>
            <tbody>
            @if($contacts && $contacts->count() > 0)
                @foreach($contacts as $contact)
                    <tr>
                        <td class="ps-2 align-middle">
                            <h6 class="text-sm"><a href="{{ route('contatos.show', $contact->id) }}">{{ $contact->name }}</a></h6>
                        </td>
                        <td class="align-middle">
                            <h6 class="text-sm"><a href="{{ route('contatos.show', $contact->id) }}">{{ $contact->phone }}</a></h6>
                        </td>
                        <td class="align-middle">
                            <h6 class="text-sm"><a href="{{ route('contatos.show', $contact->id) }}">{{ $contact->email }}</a></h6>
                        </td>
                        <td class="text-center align-middle">
                            <h6 class="text-sm"><a href="{{ route('contatos.show', $contact->id) }}">{{ $contact->cep }}</a></h6>
                        </td>
                        <td class="text-center align-middle">
                            <a id="edit-contact" href="{{ route('contatos.show', $contact->id) }}"><i class="fa-solid fa-pen"></i>
                            <span><i class="fa-solid fa-trash-can text-danger"></i></span>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-danger">Sem registros com este nome</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
