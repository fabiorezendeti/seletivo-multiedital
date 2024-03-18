<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Local de Prova">
            <x-manager.internal-navbar-itens tip="Locais de Prova" home="admin.process.exam-locations.index" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="grid grid-cols">
            <div class="px-5 py-5 overflow-hidden md:col-span-3" x-data="getCampus()" x-init="getCampus()">
                <x-jet-validation-errors class="mb-4" />
                <form id="form-campus" action="{{route('admin.process.exam-locations.store')}}" method="POST">
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Dados</h2>
                    </div>

                    @csrf
                    <div class="col-span-6  ">
                        <x-jet-label value="Nome" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="local_name"
                            :value="old('local_name') ?? $examLocation->local_name" required autofocus />
                    </div>
                    <div class="col-span-6">
                        <x-jet-label value="Campus" />
                        <x-select-box name="campus_id" x-on:change="getCampus()" x-model="campusId" >
                            @foreach ($campuses as $campus)
                                <option value="{{$campus->id}}">{{$campus->name}}</option>
                            @endforeach
                        </x-select-box>
                    </div>
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Endereço</h2>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                            <x-jet-label value="{{ __('Rua/Avenida') }}" />
                            <x-jet-input class="block mt-1 w-full" id="street" type="text" name="address[street]"
                                :value="old('address')['street'] ?? $examLocation->address['street'] ?? null" required />
                        </div>

                        <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                            <x-jet-label value="{{ __('Número') }}" />
                            <x-jet-input class="block mt-1 w-full" id="number" type="text" name="address[number]"
                                :value="old('address')['number'] ?? $examLocation->address['number'] ?? null" />
                        </div>
                    </div>

                    <div class="col-span-6 ">
                        <x-jet-label value="{{ __('Bairro') }}" />
                        <x-jet-input class="block mt-1 w-full" id="district" type="text" name="address[district]"
                            :value="old('address')['district'] ?? $examLocation->address['district'] ?? null" />
                    </div>

                    <div class="col-span-6 ">
                        <x-jet-label value="{{ __('CEP') }}" />
                        <x-jet-input class="block mt-1 w-full mask-cep" id="zip_code" type="text" name="address[zip_code]"
                            :value="old('address')['zip_code'] ?? $examLocation->address['zip_code'] ?? null" />
                    </div>

                    <div class="col-span-6 ">
                        <x-jet-label value="{{ __('Telefone') }}" />
                        <x-jet-input class="block mt-1 w-full mask-phone" id="phone_number" type="text" name="address[phone_number]"
                            :value="old('address')['phone_number'] ?? $examLocation->address['phone_number'] ?? null" />
                    </div>

                    <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                        <x-jet-label value="Prioridade" />
                        <x-jet-input class="block mt-1 w-full" type="number" name="priority"
                            :value="old('priority') ?? $examLocation->priority" required />
                    </div>

                    @livewire('city-options',['cityId'=> $examLocation->address['city']['id'] ?? null])

                    <div class="col-span-2 p-5">
                        <x-jet-label value="Ativo" />
                        <input type="checkbox" value="1" name="active" @if(old('active') or $examLocation->active) checked="checked"  @endif />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if($examLocation->id)
                            @method('put')
                            <button type="submit" formaction="{{ route('admin.process.exam-locations.update',['exam_location'=>$examLocation]) }}"
                                class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                                Atualizar
                            </button>
                            @else
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                                Cadastrar
                            </button>
                            @endif
                    </div>
                </form>

            </div>
        </div>
    </div>


    @push('js')
    <script>
        function getCampus() {
            return {
                campusId: {{ $campuses->first()->id }},
                examLocation: {{ $examLocation->id ?? 0 }},
                getCampus: function() {
                    if (this.examLocation > 0) return null;
                    axios.get("/admin/campuses/"+this.campusId)
                        .then((response)=>{
                            document.querySelector('#state').value = response.data.city.state.slug
                            document.querySelector('#city_id').value = response.data.city.id
                            document.querySelector('#street').value = response.data.street
                            document.querySelector('#number').value = response.data.number
                            document.querySelector('#district').value = response.data.district
                            document.querySelector('#phone_number').value = response.data.phone_number
                            document.querySelector('#zip_code').value = response.data.zip_code
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu')
                        })
                },
            }
        }
    </script>
@endpush

</x-manager.app-layout>

