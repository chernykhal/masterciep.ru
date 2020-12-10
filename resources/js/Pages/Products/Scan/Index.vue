<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Продукты
            </h2>
        </template>
        <qrcode-stream @decode="onDecode"></qrcode-stream>
        <qrcode-capture @decode="onDecode"/>
        <p class="decode-result">Last result: <b>{{ result }}</b></p>
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
import JetDialogModal from "./../../../Jetstream/DialogModal";
import JetDangerButton from "./../../../Jetstream/DangerButton";
import JetInputError from "./../../../Jetstream/InputError";
import {QrcodeStream, QrcodeCapture} from 'vue-qrcode-reader'

export default {
    components: {
        ResponsiveNavLink,
        JetDialogModal,
        JetSearchSection,
        AppLayout,
        JetActionMessage,
        JetInputError,
        JetDangerButton,
        JetButton,
        JetInput,
        JetLabel,
        JetSecondaryButton,
        QrcodeStream,
        QrcodeCapture
    },
    props: {
        products: Array,
    },
    data() {
        return {
            result: '',
            search: '',
            type: this.type,
        };
    },
    watch: {
        search(search) {
            this.$inertia.visit(`?search=${search}`,
                {preserveState: true});
        }
    },
    methods: {
        async onDecode(result) {
            this.$inertia.get(route('products.scan.getProductsFromQr'), {
                result: result,
                preserveScroll: true
            })
        }
    }
}
</script>

