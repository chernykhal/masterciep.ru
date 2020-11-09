<template>
    <div class="mx-9 mt-6">
        <div class="w-full flex flex-wrap">
            <div v-if="recipe.video_url" class="w-full md:w-1/2 md:px-3 ">
                <iframe width="100%" height="100%" :src="recipe.video_url"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
            <div class="w-full md:w-1/2 md:px-3 ">
                <div class="title text-2xl mt-2 md:mt-0">
                    {{ recipe.name }}

                </div>
                <div class="mt-5">
                    <div class="title text-xl mb-2">
                        Ингредиенты
                    </div>
                    <ul class="text-gray-600">
                        <li v-for="product in products" :key="product.id">{{ product.name }} -
                            {{ product.pivot.unit_value }} {{ product.unit }}
                        </li>
                    </ul>
                </div>
                <div class="mt-3">
                    <div class="title text-xl mb-2">
                        Процесс
                    </div>
                    <ul class="text-gray-600">
                        <li v-for="step in recipe.process" :key="step">{{ step }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center gap-3">
            <inertia-link :href="route('my.recipes')"
                          class=" mt-7 btn-primary inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150"
                          title="Перейти к рецептам">
                Еще рецепты
            </inertia-link>
        </div>
    </div>
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


export default {
    components: {
        JetSearchSection,
        JetDangerButton,
        JetActionMessage,
        JetButton,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        // JetPagination
    },
    props: ['recipe', 'products', 'product_waste'],
}
</script>
