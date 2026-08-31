<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-black text-xl text-family-blue leading-tight flex items-center gap-2" style="color: #1e3a8a;">
                    🛡️ {{ __('Panel de Administración General') }}
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-1">
                    Gestión centralizada de usuarios del sistema y reporte global consolidado.
                </p>
            </div>

            <!-- Botón de Exportación PDF Global -->
            <div>
                <button type="button" onclick="document.getElementById('modal-pdf').classList.remove('hidden')" class="btn-search-custom px-4 py-2 rounded-lg text-xs font-bold inline-flex items-center gap-2 cursor-pointer shadow-sm">
                    📄 Exportar PDF Global de Historiales
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Estilos Locales Identificados (Heredados del sistema) -->
    <style>
        .btn-search-custom {
            background-color: #1e3a8a !important;
            color: #ffffff !important;
            border: 1px solid #1e3a8a !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08) !important;
            transition: all 0.2s ease-in-out !important;
        }
        .btn-search-custom:hover {
            background-color: #ffffff !important;
            color: #111827 !important;
            border-color: #d1d5db !important;
        }
        .btn-orange-action {
            background-color: #f6721d !important;
            color: #ffffff !important;
            font-weight: 700;
            border-radius: 0.5rem;
            transition: background-color 0.2s;
        }
        .btn-orange-action:hover {
            background-color: #d85b0d !important;
        }
        .table-row-history {
            transition: background-color 0.15s ease-in-out !important;
        }
        .table-row-history:hover {
            background-color: #fff4ed !important;
        }
    </style>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Banner / Tarjeta de Resumen Visual de KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Usuarios Totales</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalUsers ?? $users->total() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-xl text-blue-800">
                    👥
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Administradores</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalAdmins ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-xl text-orange-600">
                    👑
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Operadores / Personal</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalOperators ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-xl text-emerald-600">
                    📦
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 text-xs font-semibold rounded-lg space-y-1">
                <p class="font-bold">⚠️ Ocurrieron algunos errores en el formulario:</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Sección Principal: Tabla de Gestión de Usuarios -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Barra superior de la tabla (Buscador & Crear Usuario) -->
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="font-extrabold text-sm text-gray-700 uppercase tracking-wider flex items-center gap-2">
                    📋 Listado de Usuarios Registrados
                </div>

                <div class="flex items-center gap-2">
                    <!-- Formulario de búsqueda -->
                    <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar usuario..." class="h-9 text-xs bg-white border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900 focus:border-blue-900 w-64">
                        <button type="submit" class="btn-search-custom h-9 px-3 text-xs font-bold rounded-md">
                            🔍
                        </button>
                    </form>

                    <button type="button" onclick="document.getElementById('modal-create-user').classList.remove('hidden')" class="btn-orange-action text-xs px-4 py-2 font-bold flex items-center gap-1 shadow-sm">
                        ➕ Nuevo Usuario
                    </button>
                </div>
            </div>

            <!-- Tabla Visual de Usuarios -->
            <div class="overflow-x-auto">
                <table class="w-full text-left divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <th class="px-4 py-3.5">ID / Fecha</th>
                            <th class="px-4 py-3.5">Usuario</th>
                            <th class="px-4 py-3.5">Correo Electrónico</th>
                            <th class="px-4 py-3.5 text-center">Rol</th>
                            <th class="px-4 py-3.5 text-right">Acciones de Control</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white text-xs sm:text-sm">
                        @forelse($users as $user)
                            <tr class="table-row-history">
                                <td class="px-4 py-4 font-bold text-gray-800 whitespace-nowrap">
                                    #{{ $user->id }} 
                                    <span class="block text-[10px] text-gray-400 font-normal">
                                        {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                </td>
                                <td class="px-4 py-4 text-gray-600 font-medium">
                                    {{ $user->email }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($user->role === 'admin' || $user->is_admin)
                                        <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold border uppercase tracking-wider bg-blue-50 text-blue-700 border-blue-200">
                                            Administrador
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold border uppercase tracking-wider bg-emerald-50 text-emerald-700 border-emerald-200">
                                            Operador
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right space-x-1 whitespace-nowrap">
                                    <button onclick="openEditModal({{ json_encode($user) }})" title="Editar Datos" class="px-2 py-1 rounded bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 font-bold text-xs transition">
                                        ✏️ Editar
                                    </button>
                                    <button onclick="openPasswordModal({{ $user->id }}, '{{ $user->name }}')" title="Cambiar Contraseña" class="px-2 py-1 rounded bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-bold text-xs transition">
                                        🔑 Clave
                                    </button>
                                    @if(auth()->id() !== $user->id)
                                        <button onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')" title="Eliminar Usuario" class="px-2 py-1 rounded bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold text-xs transition">
                                            🗑️ Eliminar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 font-medium text-xs">
                                    No se encontraron usuarios registrados en la plataforma.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        </div>

    </div>

    <!-- Modal 1: Generar PDF Consolidado de Historiales -->
    <div id="modal-pdf" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
            <div class="flex justify-between items-center border-b pb-3 border-gray-100">
                <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                    📄 Exportar Historial Global a PDF
                </h3>
                <button type="button" onclick="document.getElementById('modal-pdf').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 font-bold text-lg">✕</button>
            </div>
            
            <p class="text-xs text-gray-500 leading-relaxed">
                Selecciona el lapso de tiempo para descargar el reporte consolidado conteniendo la totalidad de tablas de envíos y recolecciones.
            </p>

            <form action="{{ route('admin.reports.pdf') }}" method="POST" target="_blank" class="space-y-4">
                @csrf
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Desde:</label>
                        <input type="date" name="from_date" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Hasta:</label>
                        <input type="date" name="to_date" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Incluir Estatus:</label>
                        <select name="status" class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                            <option value="all">📦 Todos los registros (Historial Completo)</option>
                            <option value="recolectado">Recolectados</option>
                            <option value="programado">Programados</option>
                            <option value="cancelado">Cancelados</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modal-pdf').classList.add('hidden')" class="px-4 py-2 rounded-md border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-search-custom px-4 py-2 rounded-md text-xs font-bold">
                        Descargar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Crear Nuevo Usuario -->
    <div id="modal-create-user" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
            <div class="flex justify-between items-center border-b pb-3 border-gray-100">
                <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                    ➕ Registrar Nuevo Usuario
                </h3>
                <button type="button" onclick="document.getElementById('modal-create-user').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 font-bold text-lg">✕</button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nombre Completo:</label>
                    <input type="text" name="name" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Correo Electrónico:</label>
                    <input type="email" name="email" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Rol de Acceso:</label>
                    <select name="role" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                        <option value="operador">Operador / Personal</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Contraseña:</label>
                    <input type="password" name="password" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Confirmar Contraseña:</label>
                    <input type="password" name="password_confirmation" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modal-create-user').classList.add('hidden')" class="px-4 py-2 rounded-md border border-gray-300 font-semibold text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-orange-action px-4 py-2 rounded-md text-xs font-bold">
                        Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Editar Usuario -->
    <div id="modal-edit-user" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
            <div class="flex justify-between items-center border-b pb-3 border-gray-100">
                <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                    ✏️ Editar Información de Usuario
                </h3>
                <button type="button" onclick="document.getElementById('modal-edit-user').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 font-bold text-lg">✕</button>
            </div>

            <form id="form-edit-user" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nombre Completo:</label>
                    <input type="text" id="edit-name" name="name" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Correo Electrónico:</label>
                    <input type="email" id="edit-email" name="email" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Rol de Acceso:</label>
                    <select id="edit-role" name="role" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                        <option value="operador">Operador / Personal</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modal-edit-user').classList.add('hidden')" class="px-4 py-2 rounded-md border border-gray-300 font-semibold text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-search-custom px-4 py-2 rounded-md text-xs font-bold">
                        Actualizar Datos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 4: Cambiar Contraseña -->
    <div id="modal-password-user" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
            <div class="flex justify-between items-center border-b pb-3 border-gray-100">
                <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                    🔑 Cambiar Contraseña
                </h3>
                <button type="button" onclick="document.getElementById('modal-password-user').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 font-bold text-lg">✕</button>
            </div>

            <p id="password-user-name" class="text-xs text-gray-600 font-semibold"></p>

            <form id="form-password-user" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nueva Contraseña:</label>
                    <input type="password" name="password" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Confirmar Nueva Contraseña:</label>
                    <input type="password" name="password_confirmation" required class="w-full h-9 bg-gray-50 border border-gray-300 rounded-md px-3 focus:ring-1 focus:ring-blue-900">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modal-password-user').classList.add('hidden')" class="px-4 py-2 rounded-md border border-gray-300 font-semibold text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-search-custom px-4 py-2 rounded-md text-xs font-bold">
                        Actualizar Clave
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 5: Confirmar Eliminación -->
    <div id="modal-delete-user" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4 text-center">
            <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl mx-auto">
                🗑️
            </div>
            
            <div>
                <h3 class="font-bold text-base text-gray-900">¿Eliminar Usuario?</h3>
                <p id="delete-user-name" class="text-xs text-gray-500 mt-1"></p>
            </div>

            <form id="form-delete-user" method="POST" class="pt-2">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-2">
                    <button type="button" onclick="document.getElementById('modal-delete-user').classList.add('hidden')" class="px-4 py-2 rounded-md border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition">
                        Sí, Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts para Modales Dinámicos -->
    <script>
        function openEditModal(user) {
            const form = document.getElementById('form-edit-user');
            form.action = `/admin/users/${user.id}`;
            document.getElementById('edit-name').value = user.name;
            document.getElementById('edit-email').value = user.email;
            document.getElementById('edit-role').value = user.role || (user.is_admin ? 'admin' : 'operador');
            document.getElementById('modal-edit-user').classList.remove('hidden');
        }

        function openPasswordModal(id, name) {
            const form = document.getElementById('form-password-user');
            form.action = `/admin/users/${id}/password`;
            document.getElementById('password-user-name').innerText = `Actualizando clave para: ${name}`;
            document.getElementById('modal-password-user').classList.remove('hidden');
        }

        function openDeleteModal(id, name) {
            const form = document.getElementById('form-delete-user');
            form.action = `/admin/users/${id}`;
            document.getElementById('delete-user-name').innerText = `Esta acción eliminará permanentemente al usuario "${name}".`;
            document.getElementById('modal-delete-user').classList.remove('hidden');
        }
    </script>
</x-app-layout>