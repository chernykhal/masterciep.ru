<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Продукты
            </h2>
        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-form>
            </jet-form>
        </div>
    </app-layout>
</template>

<script>
import JetButton from './../../../Jetstream/Button'
import JetInput from './../../../Jetstream/Input'
import JetLabel from './../../../Jetstream/Label'
import JetActionMessage from './../../../Jetstream/ActionMessage'
import JetSecondaryButton from './../../../Jetstream/SecondaryButton'
import JetForm from "./Form";
import AppLayout from "../../../Layouts/AppLayout";
import ResponsiveNavLink from "./../../../Jetstream/ResponsiveNavLink";

export default {
    components: {
        ResponsiveNavLink,
        AppLayout,
        JetActionMessage,
        JetButton,
        JetInput,
        JetLabel,
        JetSecondaryButton,
        JetForm,
    },
}
</script>
