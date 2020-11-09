<template>
    <div class="flex flex-wrap mx-9">
        <div v-for="recipe in recipes" :key="recipe.id" class="w-full md:px-3 md:w-1/2 xl:w-1/3 ">
            <div class="list-item list-item__margins flex flex-row justify-between">
                <img :src="recipe.image_url" :alt="recipe.name"
                     class="h-10 w-10 md:h-20 md:w-20 object-cover self-center">
                <div class="self-center ml-2 md:ml-0">
                    {{ recipe.name }}
                </div>
                <button @click="openModal(recipe)" title="Приготовить"
                        class="self-center inline-flex bg-white tracking-widest focus:outline-none focus:border-blue-300 pr-3">
                    <svg class="w-6 h-6" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="9.5" cy="9.5" r="9" fill="white" stroke="#10B42B"/>
                        <g clip-path="url(#clip0)">
                            <path
                                d="M7.1875 5C6.15188 5 5.3125 5.97938 5.3125 7.1875C5.3125 8.22187 5.92813 9.08875 6.755 9.31625L6.44438 14.3763C6.43858 14.4565 6.4495 14.5371 6.47645 14.613C6.5034 14.6888 6.5458 14.7583 6.60096 14.8169C6.65611 14.8755 6.72283 14.9221 6.7969 14.9536C6.87096 14.9851 6.95076 15.0009 7.03125 15H7.34375C7.6875 15 7.95125 14.7194 7.93062 14.3763L7.62 9.31625C8.44687 9.08813 9.0625 8.22187 9.0625 7.1875C9.0625 5.97938 8.22312 5 7.1875 5ZM13.4894 5L12.9688 8.125H12.5781L12.3175 5H12.0569L11.7962 8.125H11.4056L10.885 5H10.6244V9.0625C10.6244 9.14538 10.6573 9.22487 10.7159 9.28347C10.7745 9.34208 10.854 9.375 10.9369 9.375H11.7506L11.4438 14.3763C11.438 14.4565 11.4489 14.5371 11.4758 14.613C11.5028 14.6888 11.5452 14.7583 11.6003 14.8169C11.6555 14.8755 11.7222 14.9221 11.7963 14.9536C11.8703 14.9851 11.9501 15.0009 12.0306 15H12.3431C12.6869 15 12.9506 14.7194 12.93 14.3763L12.6231 9.375H13.4369C13.5198 9.375 13.5992 9.34208 13.6578 9.28347C13.7165 9.22487 13.7494 9.14538 13.7494 9.0625V5H13.4887H13.4894Z"
                                fill="#10B42B"/>
                        </g>
                        <defs>
                            <clipPath id="clip0">
                                <rect width="10" height="10" fill="white" transform="translate(5 5)"/>
                            </clipPath>
                        </defs>
                    </svg>

                </button>
            </div>
            <jet-recipe-modal :show="opened===recipe"
                              @close="opened = false">
                <template #video>
                    <!--                    <div class="w-full">-->
                    <!--                        <iframe v-if="recipe.video_url" width="100%" height="300px" :src="recipe.video_url"-->
                    <!--                                frameborder="0"-->
                    <!--                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"-->
                    <!--                                allowfullscreen></iframe>-->
                    <!--                    </div>-->
                </template>

                <template #content>
                    <div>
                        <div class="title text-2xl mb-2">
                            {{ recipe.name }}
                        </div>

                        <div class="recipe-card">
                            <div class="title text-xl mb-2">
                                Ингредиенты
                            </div>
                            <ul class="text-gray-600">
                                <li v-for="product in recipe.products" :key="product.id">{{ product.name }} -
                                    {{ product.pivot.unit_value }} {{ product.unit }}
                                </li>
                            </ul>
                        </div>
                        <div class="recipe-card">
                            <div class="title text-xl mb-2">
                                Процесс
                            </div>
                            <ul class="text-gray-600">
                                <li v-for="step in recipe.process" :key="step">{{ step }}</li>
                            </ul>
                        </div>
                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button
                        @click.native="closeModal">
                        Отмена
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2 btn-primary" @click.native="cook(recipe)"
                                       :disabled="form.processing">
                        Приготовить
                    </jet-danger-button>
                </template>
            </jet-recipe-modal>
        </div>
    </div>
</template>
<script>
import JetButton from '../../../../Jetstream/Button'
import JetFormSection from '../../../../Jetstream/FormSection'
import JetInput from '../../../../Jetstream/Input'
import JetInputError from '../../../../Jetstream/InputError'
import JetLabel from '../../../../Jetstream/Label'
import JetActionMessage from '../../../../Jetstream/ActionMessage'
import JetSecondaryButton from '../../../../Jetstream/SecondaryButton'
import JetSearchSection from "../../../../Jetstream/SearchSection";
import JetRecipeModal from "../../../../Jetstream/RecipeModal";
import JetDangerButton from "../../../../Jetstream/DangerButton";
// import JetPagination from "../../Jetstream/Pagination";

export default {
    components: {
        JetSearchSection,
        JetRecipeModal,
        JetDangerButton,
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        // JetPagination
    },
    props: ['recipes'],
    data() {
        return {
            opened: false,
            form: this.$inertia.form({
                '_method': 'GET',
                unit_value: '',
                recipe_id: '',
                product_waste: null,
            }),
        }
    },
    methods: {
        openModal(recipe) {
            this.opened = recipe;
        },
        closeModal() {
            this.opened = false;
        },
        cook(recipe) {
            this.form.recipe_id = recipe.recipe_id;
            this.form.product_waste = true;
            this.form.post(route('my.recipes.cook', recipe.id), {
                preserveScroll: true
            })
        },
    },
}
</script>
