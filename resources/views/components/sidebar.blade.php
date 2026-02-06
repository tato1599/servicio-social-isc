<nav class="flex flex-col space-y-2 p-4 w-64 h-screen bg-white border-r overflow-y-auto">
    @foreach(config('sidebar.modules') as $module)
        @if(true)
        {{-- Por el momento esto lo pueden ver todos, mas adelante se agregará control de permisos --}}
            <div>
                <a href="{{ route($module['route']) }}"
                   class="flex items-center p-3 rounded-lg transition-colors {{ request()->is($module['active']) ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="font-medium">{{ $module['title'] }}</span>
                </a>
                @if(isset($module['submenu']))
                    <div class="ml-4 mt-1 space-y-1 border-l-2 border-gray-100">
                        @foreach($module['submenu'] as $sub)
                            @if(!isset($sub['can']) || auth()->user()->can($sub['can']))
                                <a href="{{ route($sub['route']) }}"
                                   class="flex items-center p-2 pl-4 text-sm rounded-r-lg transition-colors {{ request()->is($sub['active']) ? 'text-blue-600 border-l-2 border-blue-600 -ml-[2px] bg-blue-50/50' : 'text-gray-500 hover:bg-gray-50' }}">
                                    {{ $sub['title'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    @endforeach
</nav>
