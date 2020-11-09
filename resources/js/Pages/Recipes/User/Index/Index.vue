<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Рецепты
            </h2>
        </template>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center my-5">Доступные рецепты</h2>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-3">
            <jet-search-section>
                <template #form>
                    <div class="flex items-center py-2 relative search-container mx-9">
                        <input id="search"
                               class="search form-input appearance-none bg-transparent w-full text-gray-600 py-1 px-2 leading-tight focus:outline-none"
                               type="text" placeholder="Поиск" aria-label="Поиск" v-model="search" autocomplete="off"/>
                        <svg class="search-icon h-5 w-5" viewBox="0 0 14 14" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path class="search-path"
                                  d="M12.4359 11.6826L8.88535 8.13203C9.43633 7.41973 9.73438 6.54883 9.73438 5.63281C9.73438 4.53633 9.30645 3.5082 8.53262 2.73301C7.75879 1.95781 6.72793 1.53125 5.63281 1.53125C4.5377 1.53125 3.50684 1.95918 2.73301 2.73301C1.95781 3.50684 1.53125 4.53633 1.53125 5.63281C1.53125 6.72793 1.95918 7.75879 2.73301 8.53262C3.50684 9.30781 4.53633 9.73438 5.63281 9.73438C6.54883 9.73438 7.41836 9.43633 8.13066 8.88672L11.6813 12.4359C11.6917 12.4464 11.704 12.4546 11.7176 12.4603C11.7312 12.4659 11.7458 12.4688 11.7605 12.4688C11.7753 12.4688 11.7899 12.4659 11.8035 12.4603C11.8171 12.4546 11.8294 12.4464 11.8398 12.4359L12.4359 11.8412C12.4464 11.8308 12.4546 11.8184 12.4603 11.8048C12.4659 11.7912 12.4688 11.7766 12.4688 11.7619C12.4688 11.7472 12.4659 11.7326 12.4603 11.719C12.4546 11.7054 12.4464 11.693 12.4359 11.6826ZM7.79844 7.79844C7.21875 8.37676 6.45039 8.69531 5.63281 8.69531C4.81523 8.69531 4.04688 8.37676 3.46719 7.79844C2.88887 7.21875 2.57031 6.45039 2.57031 5.63281C2.57031 4.81523 2.88887 4.04551 3.46719 3.46719C4.04688 2.88887 4.81523 2.57031 5.63281 2.57031C6.45039 2.57031 7.22012 2.8875 7.79844 3.46719C8.37676 4.04688 8.69531 4.81523 8.69531 5.63281C8.69531 6.45039 8.37676 7.22012 7.79844 7.79844Z"
                                  fill="#DFDFDF"/>
                        </svg>
                    </div>
                </template>
            </jet-search-section>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-3">
            <jet-recipes-list :recipes="$page.recipes"/>
            <div class="text-center mt-4 mx-9">
                <inertia-link :href="route('types.index')" title="Перейти к списку продуктов">
                    <u style="color: #10B42B;">Добавьте</u>
                </inertia-link>
                еще продукты или
                <a href="https://sbermarket.ru/" title="Перейти в Сбермаркет">
                    <u style="color: #10B42B;">закажите</u>
                </a>
                их, чтобы приготовить больше вкусненького :)
            </div>
        </div>
    </app-layout>
</template>

<script>
import JetButton from '../../../../Jetstream/Button'
import JetInput from '../../../../Jetstream/Input'
import JetLabel from '../../../../Jetstream/Label'
import JetActionMessage from '../../../../Jetstream/ActionMessage'
import JetSecondaryButton from '../../../../Jetstream/SecondaryButton'
import JetSearchSection from '../../../../Jetstream/SearchSection'
import JetRecipesList from "./RecipesList";
import AppLayout from "../../../../Layouts/AppLayout";
import ResponsiveNavLink from "../../../../Jetstream/ResponsiveNavLink";
import JetDialogModal from "../../../../Jetstream/DialogModal";
import JetDangerButton from "../../../../Jetstream/DangerButton";
import JetInputError from "../../../../Jetstream/InputError";

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
        JetRecipesList,
    },
    props: {
        recipes: Array,
    },
    data() {
        return {
            search: '',
        };
    },
    watch: {
        search(search) {
            this.$inertia.visit(`?search=${search}`,
                {preserveState: true});
        }
    },
}
</script>
