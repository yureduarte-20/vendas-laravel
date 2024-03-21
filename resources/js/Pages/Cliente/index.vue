<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link, router, useForm} from "@inertiajs/vue3";

import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
    clientes: Object
})
const destroy = id => confirm('Tem certeza que deseja continuar?') && router.delete(route('clientes.destroy', { 'cliente': id }))
const search = (text) => {
    router.get(route('clientes.index', {
        search: text
    }), {}, { preserveState: true,
        replace: true })
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clientes</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-2 py-4">
                        <div class="relative overflow-x-auto">
                            <form class="flex items-center max-w-lg mx-auto">
                                <label for="voice-search" class="sr-only">Procurar</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 21 21">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.15 5.6h.01m3.337 1.913h.01m-6.979 0h.01M5.541 11h.01M15 15h2.706a1.957 1.957 0 0 0 1.883-1.325A9 9 0 1 0 2.043 11.89 9.1 9.1 0 0 0 7.2 19.1a8.62 8.62 0 0 0 3.769.9A2.013 2.013 0 0 0 13 18v-.857A2.034 2.034 0 0 1 15 15Z"/>
                                        </svg>
                                    </div>
                                    <input type="text" id="voice-search"
                                           @input="e => search(e.target.value)"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 " placeholder="Digite o que desa procurar" required />
                                </div>
                            </form>

                            <PrimaryButton @click.prevent="router.get(route('clientes.create'))" class="mb-2 float-end">Cadastrar</PrimaryButton>

                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        CPF
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Nome
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Vendas
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Ações

                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr v-for="cliente of clientes.data" class="bg-white border-b ">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{cliente.cpf}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ cliente.nome }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{cliente.vendas_count }}
                                    </td>
                                    <td class="px-6 py-4 flex ">
                                        <a
                                            class="text-green-500 inline-block px-1 mx-1 hover:text-green-700"
                                            @click.prevent="router.get(route('clientes.edit', { 'cliente': cliente.id  }))"
                                        >
                                            Editar
                                        </a>
                                        <a
                                            @click.prevent="destroy(cliente.id)"
                                            class="text-red-500 inline-block px-1 mx-1 hover:text-red-700"

                                            href="#"
                                        >
                                            Deletar
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="list-style-none flex">
                                <li v-for="link of clientes.links">
                                    <Link
                                        class="relative block rounded active:bg-neutral-100 bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none focus:ring-0 active:text-primary-700"
                                        :active="link.active"
                                        :href="link.url"
                                    >{{ link.label.replaceAll('&laquo; Anterior', 'Anterior').replaceAll('&raquo;', '')  }}</Link>
                                </li>
                            </ul>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
