<x-layout>
    <div class="container-fluid p-5 bg-secondary-subtle text-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="display-1">Bentornato,  {{Auth::user()->name}}</h1>
                <h2 class="display-6">Ecco le informazioni del tuo profilo</h2>
                <p class="lead">Qui puoi visualizzare le informazioni del tuo profilo e modificarle se necessario.</p>
                <a class="btn btn-info" href="{{route('profile.edit')}}">modifica</a>
            </div>
        </div>
    </div>
</x-layout>






