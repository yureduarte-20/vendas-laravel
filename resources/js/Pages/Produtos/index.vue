<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {router, useForm} from "@inertiajs/vue3";
import {Link} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Divider from 'primevue/divider';


const props = defineProps({
    produtos: Object
})

const search = (text) => {
    router.get(route('produtos.index', {
        search: text
    }), {}, { preserveState: true,
        replace: true })
}
const handleDelete = (id) => {
    window.confirm('Tem certeza que deseja apagar este produto?') && router.delete(route('produtos.destroy', {'produto': id}))
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Produtos</h2>
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
                            <PrimaryButton @click.prevent="router.get(route('produtos.create'))" class="mb-2 float-end">
                                Cadastrar
                            </PrimaryButton>

                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        código
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Nome
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        preço base
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Ações

                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr v-for="cliente of produtos.data" class="bg-white border-b ">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-700 whitespace-nowrap">
                                        {{ cliente.id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ cliente.nome }}
                                    </td>
                                    <td class="px-6 py-4">
                                        R$ {{ cliente.preco_base }}
                                    </td>
                                    <td class="px-6 py-4 flex ">
                                        <a
                                            class="text-green-800 inline-block px-1 mx-1 hover:text-green-900"
                                            @click.prevent="router.get(route('produtos.edit', { 'produto': cliente.id  }))"
                                        >
                                            Editar
                                        </a>
                                        <a
                                            @click.prevent="handleDelete(cliente.id)"
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
                                    <li v-for="link of produtos.links">
                                        <Link
                                            :disabled="link.url"
                                            class="relative block rounded active:bg-neutral-100 bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none focus:ring-0 active:text-primary-700"
                                            :aria-current="link.active"
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
