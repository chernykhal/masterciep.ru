<template>
        <div class="mt-3">
            <div class="overflow-hidden">
                <div class="bg-white">
                    <div class="bg-white">
                        <form @submit.prevent="$emit('submitted')" @change="$emit('changed')" class="w-full">
                            <slot name="form"></slot>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import JetLabel from "./Label";
import JetInput from "./Input";
import JetButton from "./Button";

export default {
    components: {JetButton, JetInput, JetLabel},
}

</script>

<style scoped>

</style>
