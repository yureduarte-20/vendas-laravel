<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {router, useForm} from "@inertiajs/vue3";
import {c} from "../../../../public/build/assets/app-C5kg9Pp-.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
    clientes: Object
})

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
                                            @click.prevent="router.delete(route('clientes.destroy', { 'cliente': cliente.id }))"
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

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
