<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Рецепты
            </h2>
        </template>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-3">
            <jet-show :recipe="$page.recipe" :products="$page.products" :product_waste="$page.product_waste"/>
        </div>
    </app-layout>
</template>

<script>
import JetButton from './../../../Jetstream/Button'
import JetInput from './../../../Jetstream/Input'
import JetLabel from './../../../Jetstream/Label'
import JetActionMessage from './../../../Jetstream/ActionMessage'
import JetSecondaryButton from './../../../Jetstream/SecondaryButton'
import JetSearchSection from './../../../Jetstream/SearchSection'
import AppLayout from "./../../../Layouts/AppLayout";
import ResponsiveNavLink from "./../../../Jetstream/ResponsiveNavLink";
import JetDangerButton from "./../../../Jetstream/DangerButton";
import JetInputError from "./../../../Jetstream/InputError";
import JetShow from "./Show";

export default {
    components: {
        ResponsiveNavLink,
        JetSearchSection,
        AppLayout,
        JetActionMessage,
        JetInputError,
        JetDangerButton,
        JetButton,
        JetInput,
        JetLabel,
        JetSecondaryButton,
        JetShow,
    },
    props: {
        'recipe': Object,
        'products': Array,
        'product_waste': Boolean,
    },
}
</script>
