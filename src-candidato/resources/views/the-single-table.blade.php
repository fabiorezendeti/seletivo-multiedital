@extends('layouts.single')

@section('edital_name')
Edital Superior 01/2021
@endsection

@section('model_name')
Inscrições
@endsection

@section('content')
<div id="my-table">
    <div class=" overflow-x-auto">
        <div class="inline-block min-w-full  overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Coluna 1
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!--linha ini-->
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            202000001
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="bg-green-100 text-green-500 rounded-full py-2 px-4">confirmado</span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="#" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                Editar
                            </a>
                            <a href="#" class="bg-red-700 hover:bg-red-900 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                Excluir
                            </a>
                        </td>
                    </tr>
                    <!--linha fim-->
                    <!--linha ini-->
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            202000005
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="bg-orange-100 text-orange-500 rounded-full py-2 px-4">pendente</span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="#" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                Editar
                            </a>
                            <a href="#" class="bg-red-700 hover:bg-red-900 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                Excluir
                            </a>
                        </td>
                    </tr>
                    <!--linha fim-->
                    <!--linha ini-->
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            202000002
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="bg-red-100 text-red-500 rounded-full py-2 px-4">cancelado</span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="#" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                Editar
                            </a>
                            <a href="#" class="bg-red-700 hover:bg-red-900 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                Excluir
                            </a>
                        </td>
                    </tr>
                    <!--linha fim-->
                    <!--linha ini-->
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            202000003
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="bg-gray-100 text-gray-500 rounded-full py-2 px-4">outro status</span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="#" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                Editar
                            </a>
                            <a href="#" class="bg-red-700 hover:bg-red-900 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                Excluir
                            </a>
                        </td>
                    </tr>
                    <!--linha fim-->

                </tbody>
            </table>
            <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between          ">
                <span class="text-xs xs:text-sm text-gray-900">
                    Exibindo X itens de um total de Y
                </span>
                <div class="inline-flex mt-2 xs:mt-0">
                    <a href="#" class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-l">
                        Anterior
                    </a>
                    <a href="#" class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-r">
                        Próximo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection