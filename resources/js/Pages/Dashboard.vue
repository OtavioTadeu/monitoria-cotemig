<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

// Recebemos a lista (com valor padrão vazio para não quebrar)
const props = defineProps({
    monitores: {
        type: Array,
        default: () => [], // <--- PROTEÇÃO: Se não vier nada, assume lista vazia
    },
});

const form = useForm({
    lab: '',
});

const laboratorios = [
    { nome: 'Laboratório 1', cor: 'bg-indigo-600 hover:bg-indigo-700' },
    { nome: 'Laboratório 2', cor: 'bg-indigo-600 hover:bg-indigo-700' },
    { nome: 'Laboratório 3', cor: 'bg-indigo-600 hover:bg-indigo-700' },
    { nome: 'Laboratório 4', cor: 'bg-indigo-600 hover:bg-indigo-700' },
    { nome: 'Laboratório de Redes (RAC)', cor: 'bg-orange-600 hover:bg-orange-700' },
    { nome: 'Laboratório Apple (MAC)', cor: 'bg-slate-800 hover:bg-slate-900' },
];

const atualizarLocal = (nomeLaboratorio) => {
    form.lab = nomeLaboratorio;
    form.post(route('monitor.update'), {
        preserveScroll: true,
        onSuccess: () => alert('✅ Local atualizado!'),
        onError: () => alert('❌ Erro ao atualizar.'),
    });
};

const formatarHora = (dataString) => {
    if (!dataString) return '-';
    return new Date(dataString).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
};
</script>

<template>
    <Head title="Painel Monitoria" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Painel de Controle
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <h3 class="text-lg font-bold mb-4">📍 Onde você está?</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                            <button v-for="lab in laboratorios" :key="lab.nome"
                                @click="atualizarLocal(lab.nome)"
                                :class="[lab.cor, 'p-4 text-white font-bold rounded shadow transition hover:scale-105']"
                                :disabled="form.processing">
                                {{ lab.nome }}
                            </button>
                        </div>
                        <button @click="atualizarLocal(null)" class="text-red-500 hover:underline font-bold">
                            ❌ Sair (Indisponível)
                        </button>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">👥 Status da Equipe</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3">Nome</th>
                                        <th class="px-6 py-3">Local</th>
                                        <th class="px-6 py-3">Horário</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="monitor in props.monitores" :key="monitor.id" class="border-b dark:border-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ monitor.name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span v-if="monitor.current_lab" class="text-green-600 font-bold">🟢 {{ monitor.current_lab }}</span>
                                            <span v-else class="text-gray-400">⚪ Indisponível</span>
                                        </td>
                                        <td class="px-6 py-4">{{ formatarHora(monitor.updated_at) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div v-if="!props.monitores || props.monitores.length === 0" class="text-center p-4 text-gray-500">
                                Nenhuma informação recebida do servidor. Verifique o arquivo web.php.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>