<div>
    <x-slot name="header">
        Asignación de Horarios
    </x-slot>

    <div class="flex flex-1 overflow-hidden h-[calc(100vh-170px)] -mx-6 lg:-mx-10 -mb-10">
        <!-- Main Calendar Area -->
        <main class="flex-1 flex flex-col min-w-0 bg-white border-r border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-wrap justify-between gap-4 items-center">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 leading-tight tracking-tight">Asignación de Horarios</h1>
                        <p class="text-gray-500 text-sm italic">{{ $period }} | Gestión Académica</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="flex items-center gap-2 rounded-lg h-10 px-4 bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold animate-pulse" wire:click="$refresh">
                            <span class="material-symbols-outlined text-lg">refresh</span>
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-auto p-4 custom-calendar-container" wire:ignore>
                <div id="calendar" class="min-h-[600px] h-full"></div>
            </div>
        </main>

        <!-- Conflict Sidebar -->
        <aside class="w-80 flex-col flex bg-gray-50/50 backdrop-blur-sm">
            <div class="p-6 h-full flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Conflictos</h2>
                    <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ count($conflicts) }}</span>
                </div>
                
                <div class="flex flex-col gap-4 overflow-y-auto pr-2">
                    @forelse($conflicts as $conflict)
                        <div class="border {{ $conflict['type'] === 'teacher' ? 'border-red-500/30 bg-red-50' : 'border-orange-500/30 bg-orange-50' }} rounded-xl p-4 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined {{ $conflict['type'] === 'teacher' ? 'text-red-500' : 'text-orange-500' }} text-xl">
                                    {{ $conflict['type'] === 'teacher' ? 'error' : 'warning' }}
                                </span>
                                <p class="font-bold {{ $conflict['type'] === 'teacher' ? 'text-red-600' : 'text-orange-600' }} text-xs uppercase tracking-wider">{{ $conflict['title'] }}</p>
                            </div>
                            <div class="text-xs space-y-2 text-gray-700">
                                <p><span class="font-bold">{{ $conflict['name'] }}</span> {{ $conflict['description'] }}</p>
                                <ul class="list-disc pl-4 space-y-1 text-[10px] font-medium opacity-80">
                                    @foreach($conflict['items'] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button class="w-full mt-4 text-[10px] font-black uppercase tracking-widest {{ $conflict['type'] === 'teacher' ? 'bg-red-600' : 'bg-orange-600' }} text-white rounded-lg h-8 hover:opacity-90 transition-all">
                                Resolver Ahora
                            </button>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-20 text-center opacity-40">
                            <span class="material-symbols-outlined text-5xl mb-2 text-gray-400">check_circle</span>
                            <p class="text-sm font-medium">Sin conflictos detectados</p>
                        </div>
                    @endforelse

                    <div class="mt-4 p-4 border border-gray-200 rounded-xl bg-white shadow-sm">
                        <h3 class="font-bold text-sm mb-2 text-gray-800">Carga Académica</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500">Materias Asignadas</span>
                                <span class="font-bold text-primary">{{ \App\Models\Course::where('period', $period)->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500">Espacios de Horario</span>
                                <span class="font-bold text-gray-900">{{ \App\Models\Schedule::count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    @push('styles')
        <style>
            .fc .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 700; color: #111827; }
            .fc .fc-button-primary { background-color: #f3f4f6 !important; border-color: #e5e7eb !important; color: #374151 !important; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
            .fc .fc-button-primary:hover { background-color: #e5e7eb !important; }
            .fc .fc-button-active { background-color: #137fec !important; border-color: #137fec !important; color: white !important; }
            .fc-event { border-width: 0 0 0 4px !important; border-radius: 8px !important; padding: 4px 6px !important; box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important; }
            .fc-event-main-frame { height: 100%; display: flex; flex-direction: column; justify-content: space-between; }
            .fc-event-title-container { flex-grow: 1; }
            .fc-time-grid-event .fc-time { font-weight: 700 !important; }
            .fc-theme-standard td, .fc-theme-standard th { border-color: #f3f4f6 !important; }
        </style>
    @endpush

    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        function initCalendar() {
            var calendarEl = document.getElementById('calendar');
            if (!calendarEl || calendarEl.children.length > 0) return;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                initialDate: '2024-08-19',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                locale: 'es',
                firstDay: 1,
                allDaySlot: false,
                slotMinTime: '07:00:00',
                slotMaxTime: '21:00:00',
                slotDuration: '01:00:00',
                height: 'parent',
                events: @json($events),
                eventContent: function(arg) {
                    let classroom = arg.event.extendedProps.classroom;
                    let subject = arg.event.extendedProps.subject;
                    return {
                        html: `
                            <div class="flex flex-col h-full overflow-hidden">
                                <p class="font-bold text-[10px] leading-tight truncate">${arg.event.title}</p>
                                <p class="text-[9px] opacity-70 truncate">${subject}</p>
                                <div class="mt-auto flex justify-between items-center bg-white/20 px-1 rounded">
                                    <span class="text-[8px] font-black">${classroom}</span>
                                </div>
                            </div>
                        `
                    }
                }
            });
            calendar.render();
        }

        document.addEventListener('livewire:navigated', initCalendar);
        document.addEventListener('DOMContentLoaded', initCalendar);
    </script>
    @endpush
</div>
