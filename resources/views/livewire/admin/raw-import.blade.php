<div>
    <x-slot name="header">
        Importación Masiva de Requerimientos desde CSV
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-8 pb-20 mt-8">
        <div class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-primary/10 text-primary p-3 rounded-xl">
                            <span class="material-symbols-outlined text-3xl">csv</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 leading-tight">Subir Archivo CSV</h2>
                            <p class="text-gray-500 font-medium">Sube tu reporte de carga académica en formato .csv para
                                generar la estructura.</p>
                        </div>
                    </div>
                </div>

                @if ($message)
                    <div
                        class="bg-blue-50 border border-blue-200 text-blue-700 px-6 py-4 rounded-xl flex items-center gap-3 mt-4 animate-in fade-in duration-500">
                        <span class="material-symbols-outlined">info</span>
                        <p class="font-bold text-sm">{{ $message }}</p>
                    </div>
                @endif

                @error('file')
                    <div
                        class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl flex items-center gap-3 mt-4 animate-in fade-in duration-500">
                        <span class="material-symbols-outlined">error</span>
                        <p class="font-bold text-sm">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <div class="p-8">
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-bold text-gray-700">Seleccionar Archivo:</label>
                    <input type="file" wire:model="file" accept=".csv"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-sm file:font-black file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:transition-all cursor-pointer bg-gray-50 rounded-2xl border border-gray-200 focus:border-primary focus:ring focus:ring-primary/20">

                    <div wire:loading wire:target="file" class="text-primary mt-2 text-sm font-bold animate-pulse">
                        Procesando el archivo CSV, por favor espera...
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest">
                        Periodo Destino: <span class="text-primary">{{ $period }}</span>
                    </div>

                    <button wire:click="generatePreview" wire:loading.attr="disabled"
                        class="flex items-center gap-3 px-10 py-4 bg-gray-900 text-white rounded-2xl font-black shadow-lg hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="generatePreview"
                            class="material-symbols-outlined">visibility</span>
                        <span wire:loading wire:target="generatePreview"
                            class="material-symbols-outlined animate-spin">refresh</span>
                        <span>ACTUALIZAR VISTA PREVIA</span>
                    </button>
                </div>
            </div>
        </div>

        @if (count($previewData) > 0)
            <div
                class="bg-white rounded-3xl border-2 border-primary/20 shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-primary/5">
                    <div>
                        <h3 class="text-lg font-black text-gray-900">Borrador de Importación Detectado</h3>
                        <p class="text-xs font-bold text-primary/60 uppercase tracking-tighter">Valida la información
                            antes de guardar permanentemente</p>
                    </div>

                    <button wire:click="import" wire:loading.attr="disabled"
                        class="flex items-center gap-3 px-8 py-4 bg-primary text-white rounded-2xl font-black shadow-lg shadow-primary/30 hover:scale-105 active:scale-95 transition-all">
                        <span class="material-symbols-outlined" wire:loading.remove wire:target="import">save</span>
                        <span class="material-symbols-outlined animate-spin" wire:loading
                            wire:target="import">refresh</span>
                        <span>CONFIRMAR E IMPORTAR TODO</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest">
                            <tr>
                                <th class="px-8 py-4">Sem.</th>
                                <th class="px-8 py-4">Materia / Clave</th>
                                <th class="px-8 py-4">Maestro Detectado</th>
                                <th class="px-8 py-4 text-center">Salón</th>
                                <th class="px-8 py-4 text-center">Grupos</th>
                                <th class="px-8 py-4 text-center">Slot</th>
                                <th class="px-8 py-4">Métricas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($previewData as $p)
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-8 py-4 font-black text-gray-900">{{ $p['semester'] }}°</td>
                                    <td class="px-8 py-4">
                                        <div class="font-bold text-gray-700 leading-tight">{{ $p['subject_name'] }}
                                        </div>
                                        <div class="text-[10px] font-mono text-primary font-bold">
                                            {{ $p['subject_code'] }}</div>
                                    </td>
                                    <td class="px-8 py-4 font-black text-gray-500 text-xs uppercase italic">
                                        @if ($p['teacher'])
                                            <span class="text-gray-900 not-italic">{{ $p['teacher'] }}</span>
                                        @else
                                            <span class="text-rose-400">PENDIENTE</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg font-black text-xs">
                                            {{ $p['room'] ?? '---' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span
                                            class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded font-black">{{ $p['groups'] }}</span>
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span
                                            class="font-mono bg-amber-50 text-amber-600 px-2 py-0.5 rounded font-black">{{ $p['slot'] }}</span>
                                    </td>
                                    <td class="px-8 py-4 text-[10px] font-bold text-gray-400">
                                        {{ $p['students'] }} Alum. / {{ $p['adjusted'] }} Ajust.
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden opacity-60">
            <div class="px-8 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
                <h3 class="text-sm font-black text-gray-500 uppercase tracking-widest">Historial Reciente (Últimos 10)
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($this->recentCourses as $course)
                            <tr>
                                <td class="px-8 py-3 font-bold text-gray-400">{{ $course->subject->code ?? '' }}</td>
                                <td class="px-8 py-3 font-medium text-gray-600">{{ $course->subject->name ?? '' }}</td>
                                <td class="px-8 py-3 text-gray-400">{{ $course->teacher->name ?? '---' }}</td>
                                <td class="px-8 py-3 text-right">
                                    <span
                                        class="text-[10px] bg-green-50 text-green-600 px-2 py-0.5 rounded uppercase font-black">Cargado</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
