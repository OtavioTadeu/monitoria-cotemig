<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';

// Recebemos a lista (com proteção para array vazio)
const props = defineProps({
    monitores: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    lab: '',
});

// Configuração dos Botões com as Novas Cores
const laboratorios = [
    // Laboratórios padrão usam o ROXO do Cotemig
    { nome: 'Laboratório 1', cor: 'bg-cotemig-roxo hover:bg-purple-900 border border-purple-500' },
    { nome: 'Laboratório 2', cor: 'bg-cotemig-roxo hover:bg-purple-900 border border-purple-500' },
    { nome: 'Laboratório 3', cor: 'bg-cotemig-roxo hover:bg-purple-900 border border-purple-500' },
    { nome: 'Laboratório 4', cor: 'bg-cotemig-roxo hover:bg-purple-900 border border-purple-500' },
    // Laboratórios especiais mantêm destaque, mas harmonizados
    { nome: 'Laboratório de Redes (RAC)', cor: 'bg-orange-700 hover:bg-orange-800 border border-orange-500' },
    { nome: 'Laboratório Apple (MAC)', cor: 'bg-gray-800 hover:bg-black border border-gray-600' },
];

const atualizarLocal = (nomeLaboratorio) => {
    form.lab = nomeLaboratorio;
    form.post(route('monitor.update'), {
        preserveScroll: true,
        onSuccess: () => alert('✅ Local atualizado com sucesso!'),
        onError: () => alert('❌ Erro ao atualizar.'),
    });
};

const formatarHora = (dataString) => {
    if (!dataString) return '-';
    return new Date(dataString).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
};

// --- POLING (Atualização Automática) ---
let polling = null;

onMounted(() => {
    polling = setInterval(() => {
        router.reload({
            only: ['monitores'],
            preserveScroll: true,
            preserveState: true,
        });
    }, 5000); // Atualiza a cada 5 segundos
});

onUnmounted(() => {
    clearInterval(polling);
});
</script>

<template>
    <Head title="Painel Monitoria" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-xl text-cotemig-roxo dark:text-purple-400 leading-tight uppercase tracking-wider">
                    Painel de Monitoria
                </h2>
                <div class="flex items-center space-x-2">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cotemig-verde opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <span class="text-xs text-gray-400 font-mono">SISTEMA ONLINE</span>
                </div>
            </div>
        </template>

        <div class="py-12 bg-gray-900 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                
                <div class="bg-cotemig-cinza border border-gray-700 overflow-hidden shadow-2xl sm:rounded-xl">
                    <div class="p-8 text-gray-100 text-center">
                        <h3 class="text-xl font-bold mb-6 text-white tracking-wide">📍 ONDE VOCÊ ESTÁ AGORA?</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                            <button v-for="lab in laboratorios" :key="lab.nome"
                                @click="atualizarLocal(lab.nome)"
                                :class="[lab.cor, 'py-5 px-4 text-white font-bold rounded-lg shadow-lg transition-all transform hover:-translate-y-1 hover:shadow-purple-500/20']"
                                :disabled="form.processing">
                                {{ lab.nome }}
                            </button>
                        </div>

                        <button @click="atualizarLocal(null)" class="group text-red-400 transition hover:text-red-300">
                            <span class="font-bold border-b-2 border-transparent group-hover:border-red-400">❌ SAIR DO LABORATÓRIO</span>
                            <span class="text-sm block mt-1 opacity-70">(Marcar como Indisponível)</span>
                        </button>
                    </div>
                </div>

                <div class="bg-cotemig-cinza border border-gray-700 overflow-hidden shadow-2xl sm:rounded-xl">
                    <div class="p-0"> <div class="p-6 border-b border-gray-700 bg-gray-800">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                👥 STATUS DA EQUIPE
                            </h3>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-400">
                                <thead class="text-xs text-gray-300 uppercase bg-cotemig-roxo">
                                    <tr>
                                        <th class="px-6 py-4 tracking-wider">Monitor</th>
                                        <th class="px-6 py-4 tracking-wider">Local Atual</th>
                                        <th class="px-6 py-4 tracking-wider">Última Atualização</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    <tr v-for="monitor in props.monitores" :key="monitor.id" class="hover:bg-gray-800 transition duration-150">
                                        <td class="px-6 py-4 font-medium text-white text-base">
                                            {{ monitor.name }}
                                            <span v-if="monitor.id === $page.props.auth.user.id" class="ml-3 text-[10px] font-bold text-cotemig-roxo bg-purple-100 px-2 py-0.5 rounded border border-purple-300">VOCÊ</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span v-if="monitor.current_lab" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-900/30 text-cotemig-verde border border-green-700/50">
                                                <span class="w-2 h-2 rounded-full bg-cotemig-verde animate-pulse"></span>
                                                {{ monitor.current_lab }}
                                            </span>
                                            <span v-else class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-700 text-gray-400 border border-gray-600">
                                                ⚪ INDISPONÍVEL
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-gray-500">
                                            {{ formatarHora(monitor.updated_at) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div v-if="!props.monitores || props.monitores.length === 0" class="text-center p-8 text-gray-500 bg-gray-800">
                                <p>Nenhum monitor conectado.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>