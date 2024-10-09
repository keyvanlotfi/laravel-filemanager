<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">

                    <div class="fas fa-close close" @click.self="$emit('close')"></div>


                    <div v-if="errors" class="mb-4">
                        <div v-for="errorList in errors">
                            <div v-for="error in errorList" class="text-danger">{{ error }}</div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-1">New Name:</div>
                        <input type="text" class="form-control" v-model="name" @keydown.enter="rename" autofocus>
                        <button class="btn btn-sm btn-primary col-12 mt-3" @click="rename">Rename</button>
                    </div>


                </div>
            </div>
        </div>
    </transition>
</template>




<script>
import axios from 'axios';
export default{
    props: ['selected_item', 'prefix'],
    data() {
        return {
            name: null,
            errors: null,
        }
    },
    mounted() {
        // extract name from extension
        const filename = this.selected_item.name;
        const extension = this.selected_item.extension;

        this.name = filename.endsWith(`.${extension}`)
            ? filename.slice(0, -(extension.length + 1))
            : filename;
        // extract name from extension
    },
    methods:{
        rename(){
            axios.post(this.prefix + '/rename', {folder_name: this.name, item: this.selected_item})
                .then(() => {
                    this.$emit('renamed');
                    this.$emit('close');
                })
                .catch(error => {
                    this.$parent.loading = false;
                    this.errors = error.response.data.errors;
                });
        }
    }
}
</script>
