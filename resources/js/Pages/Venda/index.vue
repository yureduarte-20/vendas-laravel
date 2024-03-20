<script setup>
import {Link, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    vendas: Object
})
const destroy = id => confirm('Você tem certeza que deseja apagar esta venda?') &&
    router.delete(route('vendas.destroy', {venda: id}))
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
                            <PrimaryButton @click.prevent="router.get(route('vendas.create'))" class="mb-2 float-end">
                                Cadastrar
                            </PrimaryButton>

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

                                <tr v-for="venda of vendas.data" class="bg-white border-b ">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ venda.cpf }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ venda.nome }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ venda.vendas_count }}
                                    </td>
                                    <td class="px-6 py-4 flex ">
                                        <a
                                            class="text-green-500 inline-block px-1 mx-1 hover:text-green-700"
                                            @click.prevent="router.get(route('vendas.edit', { 'venda': venda.id  }))"
                                        >
                                            Editar
                                        </a>
                                        <a
                                            @click.prevent="destroy(venda.id)"
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
                                <li v-for="link of vendas.links">
                                    <Link
                                        class="relative block rounded active:bg-neutral-100 bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none focus:ring-0 active:text-primary-700"
                                        :active="link.active"
                                        :href="link.url"
                                    >{{
                                            link.label.replaceAll('&laquo; Anterior', 'Anterior').replaceAll('&raquo;', '')
                                        }}
                                    </Link>
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
