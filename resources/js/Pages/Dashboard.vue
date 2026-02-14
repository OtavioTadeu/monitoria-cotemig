<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    lab: '',
});

// Lista de Laboratórios com configurações personalizadas
const laboratorios = [
    { nome: 'Laboratório 1', cor: 'bg-indigo-600 hover:bg-indigo-700' },
    { nome: 'Laboratório 2', cor: 'bg-indigo-600 hover:bg-indigo-700' },
    { nome: 'Laboratório 3', cor: 'bg-indigo-600 hover:bg-indigo-700' },
    { nome: 'Laboratório 4', cor: 'bg-indigo-600 hover:bg-indigo-700' },
    { nome: 'Laboratório de Redes (RAC)', cor: 'bg-orange-600 hover:bg-orange-700' }, // RAC (Laranja)
    { nome: 'Laboratório Apple (MAC)', cor: 'bg-slate-800 hover:bg-slate-900' },     // MAC (Escuro)
];

const atualizarLocal = (nomeLaboratorio) => {
    form.lab = nomeLaboratorio;
    form.post(route('monitor.update'), {
        preserveScroll: true,
        onSuccess: () => alert('✅ Local atualizado para: ' + (nomeLaboratorio || 'Nenhum')),
    });
};
</script>

<template>
    <Head title="Painel do Monitor" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Painel de Controle
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        
                        <h3 class="text-lg font-bold mb-6">Onde você está atendendo agora?</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">
                            
                            <button 
                                v-for="lab in laboratorios" 
                                :key="lab.nome"
                                @click="atualizarLocal(lab.nome)"
                                :class="[lab.cor, 'p-4 text-white font-bold rounded-lg shadow transition transform hover:scale-105']"
                                :disabled="form.processing"
                            >
                                🖥️ {{ lab.nome }}
                            </button>

                        </div>

                        <div class="mt-8 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <button 
                                @click="atualizarLocal(null)"
                                class="px-6 py-2 border border-red-500 text-red-500 rounded hover:bg-red-50 transition"
                            >
                                ❌ Não estou em laboratório (Sair)
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>