@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="mb-10">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Catálogo de Ativos</h1>
        <p class="text-gray-500 mt-2">Especificações técnicas por categoria.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($itens as $categoria => $modelos)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-tag text-2xl text-blue-600"></i>
                        <h2 class="font-bold text-slate-800 uppercase tracking-wider text-sm">{{ $categoria }}</h2>
                    </div>
                    <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md text-[10px] font-bold">
                        {{ $modelos->count() }} itens
                    </span>
                </div>
                
                <div class="p-6">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-slate-100">
                            @foreach($modelos as $modelo)
                                <tr>
                                    <td class="py-3">
                                        <div class="font-bold text-slate-700">{{ $modelo->nome }}</div>
                                        <div class="text-xs text-slate-400">{{ $modelo->fabricante }}</div>
                                    </td>
                                    <td class="py-3 text-right">
                                        @if($modelo->tipo_papel)
                                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-[10px] font-black uppercase">{{ $modelo->tipo_papel }}</span>
                                        @endif
                                        
                                        @if($modelo->voltagem)
                                            <span class="text-slate-500 font-medium">{{ $modelo->voltagem }}</span>
                                        @endif

                                        @if($modelo->cor)
                                            <div class="flex items-center justify-end gap-2">
                                                <span class="text-xs text-slate-600">{{ $modelo->cor }}</span>
                                                <div class="w-3 h-3 rounded-full border shadow-sm" style="background-color: {{ $modelo->cor_hex ?? '#ccc' }}"></div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection